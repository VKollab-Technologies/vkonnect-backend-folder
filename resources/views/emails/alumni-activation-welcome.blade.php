@component('emails.layout', [
  'subjectLine' => $subjectLine,
  'preheader' => 'Your VKONNECT Alumni Portal account is now active',
  'role' => $role ?? 'alumni',
  'supportEmail' => $supportEmail ?? null,
  'platformName' => $platformName ?? null,
])
  <h1 style="margin:0 0 8px;font-size:20px;color:#0f172a;">Welcome to VKONNECT, {{ $name }}!</h1>
  <p style="margin:0 0 20px;color:#475569;font-size:15px;line-height:1.6;">
    Thank you for activating your Alumni Portal account. You&rsquo;re now part of the VKollab alumni network &mdash;
    explore job opportunities, announcements, and stay connected with fellow alumni.
  </p>

  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;border-radius:10px;margin:0 0 24px;">
    <tr>
      <td style="padding:16px 18px;">
        <p style="margin:0 0 10px;color:#0f172a;font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1px;">Your alumni information</p>
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
          <tr>
            <td style="padding:4px 0;color:#64748b;font-size:13px;width:150px;">Full Name</td>
            <td style="padding:4px 0;color:#0f172a;font-size:13px;font-weight:600;">{{ $name }}</td>
          </tr>
          <tr>
            <td style="padding:4px 0;color:#64748b;font-size:13px;">EMP ID</td>
            <td style="padding:4px 0;color:#0f172a;font-size:13px;font-weight:600;">{{ $vkollabId }}</td>
          </tr>
          @if($department)
          <tr>
            <td style="padding:4px 0;color:#64748b;font-size:13px;">Department</td>
            <td style="padding:4px 0;color:#0f172a;font-size:13px;font-weight:600;">{{ $department }}</td>
          </tr>
          @endif
          @if($designation)
          <tr>
            <td style="padding:4px 0;color:#64748b;font-size:13px;">Designation</td>
            <td style="padding:4px 0;color:#0f172a;font-size:13px;font-weight:600;">{{ $designation }}</td>
          </tr>
          @endif
          @if($joinedOn)
          <tr>
            <td style="padding:4px 0;color:#64748b;font-size:13px;">Date of Joining</td>
            <td style="padding:4px 0;color:#0f172a;font-size:13px;font-weight:600;">{{ $joinedOn }}</td>
          </tr>
          @endif
          @if($exitedOn)
          <tr>
            <td style="padding:4px 0;color:#64748b;font-size:13px;">Date of Relieving</td>
            <td style="padding:4px 0;color:#0f172a;font-size:13px;font-weight:600;">{{ $exitedOn }}</td>
          </tr>
          @endif
        </table>
      </td>
    </tr>
  </table>

  <div style="text-align:center;margin:0 0 20px;">
    <a href="{{ $loginUrl }}" style="display:inline-block;background:#1D4ED8;color:#ffffff;text-decoration:none;padding:14px 40px;border-radius:10px;font-weight:700;font-size:15px;">
      LOGIN TO VKONNECT
    </a>
  </div>

  <p style="margin:0;color:#64748b;font-size:13px;line-height:1.6;">
    Questions? Reach us anytime at <a href="mailto:{{ $supportEmail ?? 'alumni@vkollab.com' }}" style="color:#1D4ED8;">{{ $supportEmail ?? 'alumni@vkollab.com' }}</a>.
  </p>
@endcomponent
