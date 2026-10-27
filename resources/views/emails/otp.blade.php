@component('emails.layout', [
  'subjectLine' => $subjectLine ?? 'Your VKONNECT verification code',
  'preheader' => 'Your code is '.$code,
  'role' => $role ?? null,
  'supportEmail' => $supportEmail ?? null,
  'platformName' => $platformName ?? null,
])
  <h1 style="margin:0 0 8px;font-size:20px;color:#0f172a;">{{ $heading }}</h1>
  <p style="margin:0 0 20px;color:#475569;font-size:15px;line-height:1.6;">
    @if($name)Hi {{ $name }},<br>@endif
    Use the code below to {{ $action }}.
  </p>

  <div style="text-align:center;margin:8px 0 20px;">
    <span class="vk-otp" style="display:inline-block;background:#eef2ff;border:1px solid #c7d2fe;border-radius:12px;padding:16px 28px;font-size:32px;font-weight:800;letter-spacing:10px;color:#1D4ED8;">{{ $code }}</span>
  </div>

  <p style="margin:0 0 16px;color:#475569;font-size:13px;line-height:1.6;">
    This code expires in <strong>{{ $ttl }} minutes</strong> and can only be used once.
  </p>

  <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:14px 16px;margin:0 0 8px;">
    <p style="margin:0;color:#92400e;font-size:13px;line-height:1.6;">
      <strong>Security notice:</strong> Never share this code with anyone, including VKollab staff.
      If you didn&rsquo;t request this, you can safely ignore this email.
    </p>
  </div>
@endcomponent
