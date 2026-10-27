@component('emails.layout', [
  'subjectLine' => $subjectLine,
  'preheader' => 'Your '.$roleLabel.' account on '.($platformName ?? 'VKONNECT').' is ready — sign in details inside',
  'role' => $role,
  'supportEmail' => $supportEmail ?? null,
  'platformName' => $platformName ?? null,
])
  <h1 style="margin:0 0 8px;font-size:20px;color:#0f172a;">Welcome to {{ $platformName ?? 'VKONNECT' }}, {{ $name }}!</h1>
  <p style="margin:0 0 20px;color:#475569;font-size:15px;line-height:1.6;">
    @if($invitedBy){{ $invitedBy }} has created@else An administrator has created @endif a <strong>{{ $roleLabel }}</strong> account for you.
    Use the sign-in details below to access the portal.
  </p>

  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;border-radius:10px;margin:0 0 16px;">
    <tr>
      <td style="padding:16px 18px;">
        <p style="margin:0 0 10px;color:#0f172a;font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1px;">Your account</p>
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
          <tr>
            <td style="padding:4px 0;color:#64748b;font-size:13px;width:150px;">Full Name</td>
            <td style="padding:4px 0;color:#0f172a;font-size:13px;font-weight:600;">{{ $name }}</td>
          </tr>
          <tr>
            <td style="padding:4px 0;color:#64748b;font-size:13px;">EMP ID</td>
            <td style="padding:4px 0;color:#0f172a;font-size:13px;font-weight:600;">{{ $vkollabId }}</td>
          </tr>
          <tr>
            <td style="padding:4px 0;color:#64748b;font-size:13px;">Email</td>
            <td style="padding:4px 0;color:#0f172a;font-size:13px;font-weight:600;">{{ $email }}</td>
          </tr>
          @if($department)
          <tr>
            <td style="padding:4px 0;color:#64748b;font-size:13px;">Department</td>
            <td style="padding:4px 0;color:#0f172a;font-size:13px;font-weight:600;">{{ $department }}</td>
          </tr>
          @endif
          <tr>
            <td style="padding:4px 0;color:#64748b;font-size:13px;">Role</td>
            <td style="padding:4px 0;color:#0f172a;font-size:13px;font-weight:600;">{{ $roleLabel }}</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  {{-- Credentials sit in their own visually distinct block, not folded into
       the details table above: this is the one part of the mail that must be
       acted on and then made obsolete, so it should not read as reference
       information alongside the department and EMP ID. --}}
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#fffbeb;border:1px solid #fcd34d;border-radius:10px;margin:0 0 20px;">
    <tr>
      <td style="padding:16px 18px;">
        <p style="margin:0 0 10px;color:#92400e;font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1px;">Temporary password</p>
        <p style="margin:0 0 12px;font-family:'Courier New',Courier,monospace;font-size:18px;font-weight:700;color:#0f172a;letter-spacing:1px;word-break:break-all;">{{ $temporaryPassword }}</p>
        <p style="margin:0;color:#92400e;font-size:13px;line-height:1.6;">
          Sign in with your <strong>EMP ID</strong> ({{ $vkollabId }}) and this password, then change it straight away from
          your account page. This password is temporary &mdash; treat it as valid for first sign-in only.
        </p>
      </td>
    </tr>
  </table>

  <div style="text-align:center;margin:0 0 20px;">
    <a href="{{ $loginUrl }}" style="display:inline-block;background:#1D4ED8;color:#ffffff;text-decoration:none;padding:14px 40px;border-radius:10px;font-weight:700;font-size:15px;">
      SIGN IN TO {{ strtoupper($platformName ?? 'VKONNECT') }}
    </a>
  </div>

  <p style="margin:0 0 16px;color:#64748b;font-size:13px;line-height:1.6;text-align:center;word-break:break-all;">
    Or paste this into your browser: <a href="{{ $loginUrl }}" style="color:#1D4ED8;">{{ $loginUrl }}</a>
  </p>

  <p style="margin:0;color:#64748b;font-size:13px;line-height:1.6;">
    Didn&rsquo;t expect this? Let us know at <a href="mailto:{{ $supportEmail ?? 'alumni@vkollab.com' }}" style="color:#1D4ED8;">{{ $supportEmail ?? 'alumni@vkollab.com' }}</a>
    so the account can be removed.
  </p>
@endcomponent
