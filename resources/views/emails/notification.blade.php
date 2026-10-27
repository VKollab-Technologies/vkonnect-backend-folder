@component('emails.layout', [
  'role' => $role ?? null,
  'supportEmail' => $supportEmail ?? null,
  'platformName' => $platformName ?? null,
])
  <h1 style="margin:0 0 8px;font-size:20px;color:#0f172a;">{{ $heading }}</h1>
  <p style="margin:0 0 20px;color:#475569;font-size:15px;line-height:1.6;">{{ $body }}</p>

  @if($actionUrl)
    <div style="margin:8px 0 8px;">
      <a href="{{ $actionUrl }}" style="display:inline-block;background:#1D4ED8;color:#ffffff;text-decoration:none;padding:12px 22px;border-radius:10px;font-weight:600;font-size:14px;">
        {{ $actionLabel ?? 'Open VKONNECT' }}
      </a>
    </div>
  @endif
@endcomponent
