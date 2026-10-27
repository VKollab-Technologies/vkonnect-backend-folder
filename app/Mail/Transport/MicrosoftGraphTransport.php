<?php

namespace App\Mail\Transport;

use App\Services\Graph\GraphTokenService;
use App\Services\OtpService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\MessageConverter;

/**
 * Symfony Mailer transport that sends mail through Microsoft Graph
 * (`POST /users/{sender}/sendMail`) using an app-only (client credentials)
 * access token, instead of SMTP. Registered as the "graph" driver in
 * config/mail.php — every existing `Mail::to(...)->queue(...)` call site
 * (OtpMail, NotificationMail) is unaffected; only the transport underneath
 * changes when MAIL_MAILER=graph.
 */
class MicrosoftGraphTransport extends AbstractTransport
{
    public function __construct(private readonly GraphTokenService $tokens)
    {
        parent::__construct();
    }

    public function __toString(): string
    {
        return 'microsoft-graph';
    }

    protected function doSend(SentMessage $message): void
    {
        $sender = config('services.microsoft_graph.sender');

        if (! $sender) {
            throw new TransportException('Microsoft Graph sender mailbox is not configured (MS_GRAPH_SENDER).');
        }

        $email = MessageConverter::toEmail($message->getOriginalMessage());
        $payload = $this->buildPayload($email);

        $this->sendViaGraph($sender, $payload, retryOnUnauthorized: true);
    }

    private function sendViaGraph(string $sender, array $payload, bool $retryOnUnauthorized): void
    {
        $token = $this->tokens->getToken();

        $response = Http::withToken($token)
            ->timeout(15)
            ->post('https://graph.microsoft.com/v1.0/users/'.rawurlencode($sender).'/sendMail', $payload);

        if ($response->successful()) {
            /*
             * "accepted", not "sent" — and the distinction matters.
             *
             * Graph's sendMail returns 202 Accepted, which means Microsoft has
             * TAKEN the message, not that anyone received it. Everything after
             * that point — transport rules, external-recipient policy,
             * quarantine, the recipient's own spam filter — happens
             * asynchronously and reports nothing back here.
             *
             * Logging this as "email sent" made an undelivered message look
             * like a delivered one, which is exactly how a delivery problem
             * gets diagnosed as "the app is working fine". The recipient is
             * recorded (masked) so a specific message can be found in the
             * Exchange message trace, which is the only place the real
             * delivery outcome exists.
             */
            Log::info('Microsoft Graph: accepted for delivery (202)', [
                'subject' => $payload['message']['subject'] ?? null,
                'to' => array_map(
                    fn (array $r) => OtpService::maskEmail($r['emailAddress']['address'] ?? ''),
                    $payload['message']['toRecipients'] ?? [],
                ),
                'status' => $response->status(),
                // Microsoft's own correlation id — quote this when raising a
                // non-delivery case, it identifies the request in their logs.
                'request_id' => $response->header('request-id'),
            ]);

            return;
        }

        // Token may have expired between cache-read and send; refresh once and retry.
        if ($response->status() === 401 && $retryOnUnauthorized) {
            $this->tokens->refreshToken();
            $this->sendViaGraph($sender, $payload, retryOnUnauthorized: false);

            return;
        }

        Log::error('Microsoft Graph: email send failed', [
            'status' => $response->status(),
            'error_code' => $response->json('error.code'),
            'error_message' => $response->json('error.message'),
        ]);

        // Throwing lets the underlying queued mail job fail and retry per the
        // queue worker's --tries/backoff configuration (failed_jobs on exhaustion).
        throw new TransportException(sprintf(
            'Microsoft Graph sendMail failed (HTTP %d): %s',
            $response->status(),
            $response->json('error.message', $response->reason()),
        ));
    }

    private function buildPayload(Email $email): array
    {
        $htmlBody = $email->getHtmlBody();
        $textBody = $email->getTextBody();

        $body = $htmlBody !== null
            ? ['contentType' => 'HTML', 'content' => $htmlBody]
            : ['contentType' => 'Text', 'content' => (string) $textBody];

        $message = [
            'subject' => (string) $email->getSubject(),
            'body' => $body,
            'toRecipients' => $this->addressList($email->getTo()),
        ];

        if ($cc = $this->addressList($email->getCc())) {
            $message['ccRecipients'] = $cc;
        }

        if ($bcc = $this->addressList($email->getBcc())) {
            $message['bccRecipients'] = $bcc;
        }

        if ($replyTo = $this->addressList($email->getReplyTo())) {
            $message['replyTo'] = $replyTo;
        }

        if ($attachments = $this->buildAttachments($email)) {
            $message['attachments'] = $attachments;
        }

        /*
         * Keep a copy in the sender mailbox's Sent Items.
         *
         * Graph defaults this to true; the original code passed the STRING
         * 'false', and a later change made it a real boolean false. Either way
         * the effect was the same in practice: nothing appeared in Sent Items,
         * so there was no tenant-side record that a message had ever been
         * composed.
         *
         * That is the wrong trade for this app. Graph's 202 means accepted, not
         * delivered, and the application log records only a masked recipient —
         * so with no Sent Items copy there was no artifact anyone could open
         * when an alumnus said an email never arrived. Message trace shows
         * routing; Sent Items shows the actual message.
         *
         * Note this puts OTP codes in the mailbox. They expire in 10 minutes
         * (OtpService::TTL_MINUTES) and are single-use, so a stored copy is
         * spent by the time anyone reads it — but the alumni@ mailbox should be
         * treated as sensitive and access-controlled accordingly.
         */
        return ['message' => $message, 'saveToSentItems' => true];
    }

    /** @param Address[] $addresses */
    private function addressList(array $addresses): array
    {
        return array_map(fn (Address $address) => [
            'emailAddress' => array_filter([
                'address' => $address->getAddress(),
                'name' => $address->getName() ?: null,
            ]),
        ], $addresses);
    }

    private function buildAttachments(Email $email): array
    {
        $attachments = [];

        foreach ($email->getAttachments() as $attachment) {
            $headers = $attachment->getPreparedHeaders();
            $filename = $headers->getHeaderParameter('Content-Disposition', 'filename') ?? 'attachment';
            $isInline = $headers->has('Content-Disposition')
                && $headers->get('Content-Disposition')->getBody() === 'inline';

            $entry = [
                '@odata.type' => '#microsoft.graph.fileAttachment',
                'name' => $filename,
                'contentType' => $attachment->getMediaType().'/'.$attachment->getMediaSubtype(),
                'contentBytes' => base64_encode($attachment->getBody()),
            ];

            // Inline images referenced via <img src="cid:...">, need isInline+contentId
            // so Outlook/Gmail render them inline instead of a downloadable attachment.
            if ($isInline && $headers->has('Content-ID')) {
                $entry['isInline'] = true;
                $entry['contentId'] = trim($headers->get('Content-ID')->getBody(), '<>');
            }

            $attachments[] = $entry;
        }

        // Every branded email references the VKollab logo and the VKONNECT
        // logo via fixed `cid:` references. Deliberately embedded HERE — at
        // send-time, in the transport — rather than on the Mailable itself: a
        // Mailable is serialized onto the queue, and Symfony's embed callback
        // (or any closure) can't survive that round-trip. Reading the file
        // fresh here avoids storing any unserializable state on the queued job.
        $htmlBody = $email->getHtmlBody();
        foreach (['vkollab-logo', 'vkonnect-logo'] as $cid) {
            if ($htmlBody && str_contains($htmlBody, "cid:{$cid}")) {
                $logoPath = resource_path("images/{$cid}.png");
                if (is_readable($logoPath)) {
                    $attachments[] = [
                        '@odata.type' => '#microsoft.graph.fileAttachment',
                        'name' => "{$cid}.png",
                        'contentType' => 'image/png',
                        'contentBytes' => base64_encode(file_get_contents($logoPath)),
                        'isInline' => true,
                        'contentId' => $cid,
                    ];
                }
            }
        }

        return $attachments;
    }
}
