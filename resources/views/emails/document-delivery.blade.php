@component('emails.layout', [
  'subjectLine' => $subjectLine,
  'preheader' => $documentTitle.' is attached to this email',
  'role' => $role ?? 'alumni',
  'supportEmail' => $supportEmail ?? null,
  'platformName' => $platformName ?? null,
])
  <h1 style="margin:0 0 8px;font-size:20px;color:#0f172a;">Here&rsquo;s your document, {{ $name }}</h1>
  <p style="margin:0 0 20px;color:#475569;font-size:15px;line-height:1.6;">
    You asked {{ $platformName ?? 'VKONNECT' }} to send you this document, and it is
    <strong>attached to this email</strong>. Keep this message and you can open the file any time &mdash;
    there is nothing to click and nothing that expires.
  </p>

  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;border-radius:10px;margin:0 0 20px;">
    <tr>
      <td style="padding:16px 18px;">
        <p style="margin:0 0 10px;color:#0f172a;font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1px;">Document</p>
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
          <tr>
            <td style="padding:4px 0;color:#64748b;font-size:13px;width:150px;">Name</td>
            <td style="padding:4px 0;color:#0f172a;font-size:13px;font-weight:600;">{{ $documentTitle }}</td>
          </tr>
          @if($categoryName)
          <tr>
            <td style="padding:4px 0;color:#64748b;font-size:13px;">Category</td>
            <td style="padding:4px 0;color:#0f172a;font-size:13px;font-weight:600;">{{ $categoryName }}</td>
          </tr>
          @endif
          @if($financialYear)
          <tr>
            <td style="padding:4px 0;color:#64748b;font-size:13px;">Financial Year</td>
            <td style="padding:4px 0;color:#0f172a;font-size:13px;font-weight:600;">{{ $financialYear }}</td>
          </tr>
          @endif
          <tr>
            <td style="padding:4px 0;color:#64748b;font-size:13px;">Attached file</td>
            <td style="padding:4px 0;color:#0f172a;font-size:13px;font-weight:600;font-family:'Courier New',Courier,monospace;word-break:break-all;">{{ $fileName }}</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  {{-- The attachment IS the delivery, so the one thing worth saying is that
       this copy is now the alumnus's to keep — and, because it is theirs to
       keep, that it is a personal record rather than something to forward. --}}
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;margin:0 0 20px;">
    <tr>
      <td style="padding:14px 18px;">
        <p style="margin:0;color:#334155;font-size:13px;line-height:1.6;">
          This copy is yours to keep &mdash; save it wherever you keep your personal records. It contains your own
          details, so treat it as confidential and avoid forwarding it.
        </p>
      </td>
    </tr>
  </table>

  <p style="margin:0;color:#64748b;font-size:13px;line-height:1.6;">
    Didn&rsquo;t request this? The document has not been shared with anyone else &mdash; it was sent only to your
    registered email address. Let us know at
    <a href="mailto:{{ $supportEmail ?? 'alumni@vkollab.com' }}" style="color:#1D4ED8;">{{ $supportEmail ?? 'alumni@vkollab.com' }}</a>.
  </p>
@endcomponent
