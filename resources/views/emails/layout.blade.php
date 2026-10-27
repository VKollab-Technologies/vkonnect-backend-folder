<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="color-scheme" content="light">
  <meta name="supported-color-schemes" content="light">
  <!--[if mso]>
  <noscript>
    <xml>
      <o:OfficeDocumentSettings>
        <o:PixelsPerInch>96</o:PixelsPerInch>
      </o:OfficeDocumentSettings>
    </xml>
  </noscript>
  <style>table, td { border-collapse: collapse; } .fallback-font { font-family: Arial, sans-serif !important; }</style>
  <![endif]-->
  <title>{{ $subjectLine ?? 'VKONNECT Alumni Portal' }}</title>
  <style>
    body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
    table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
    img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }
    body { margin: 0; padding: 0; width: 100% !important; height: 100% !important; }
    @media only screen and (max-width: 600px) {
      .vk-container { width: 100% !important; max-width: 100% !important; border-radius: 0 !important; }
      .vk-px { padding-left: 20px !important; padding-right: 20px !important; }
      .vk-otp { font-size: 26px !important; letter-spacing: 6px !important; padding: 14px 18px !important; }
    }
  </style>
</head>
<body style="margin:0;padding:0;background:#f8fafc;font-family:'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#0f172a;">
  <?php
    // Role-aware accent — the shared design system stays a single layout, but
    // the header badge/accent color shift per realm so each role's mail has
    // its own visual identity without a separate template file per role.
    $roleMeta = [
      'alumni' => ['badge' => 'Alumni Portal', 'accent' => '#38BDF8'],
      'hr_admin' => ['badge' => 'HR Administration', 'accent' => '#34D399'],
      'super_admin' => ['badge' => 'Platform Administration', 'accent' => '#FBBF24'],
    ][$role ?? 'alumni'] ?? ['badge' => 'Alumni Network', 'accent' => '#60A5FA'];
    $supportEmailValue = $supportEmail ?? 'alumni@vkollab.com';
    $platformNameValue = $platformName ?? 'VKONNECT';
  ?>
  <div style="display:none;max-height:0;overflow:hidden;opacity:0;">{{ $preheader ?? '' }}&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;</div>

  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;padding:32px 0;">
    <tr>
      <td align="center">
        <table role="presentation" width="580" cellpadding="0" cellspacing="0" class="vk-container" style="width:580px;max-width:92%;background:#ffffff;border-radius:16px;overflow:hidden;border:1px solid #e2e8f0;">

          <!-- Brand header: VKollab logo (left) · VKONNECT logo (right) -->
          <tr>
            <td class="vk-px" style="background:linear-gradient(135deg,#071B4D 0%,#1D4ED8 100%);padding:24px 32px;">
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td align="left" valign="middle" style="width:50%;">
                    @if(is_readable(resource_path('images/vkollab-logo.png')))
                      <img src="cid:vkollab-logo" width="130" alt="VKollab Technologies" style="display:block;border:0;max-width:130px;height:auto;">
                    @else
                      <span style="color:#ffffff;font-size:15px;font-weight:700;">VKollab</span>
                    @endif
                  </td>
                  <td align="right" valign="middle" style="width:50%;">
                    @if(is_readable(resource_path('images/vkonnect-logo.png')))
                      <img src="cid:vkonnect-logo" width="130" alt="VKONNECT" style="display:block;border:0;max-width:130px;height:auto;margin-left:auto;">
                    @else
                      <span style="color:#ffffff;font-size:20px;font-weight:800;letter-spacing:-0.5px;">VK<span style="color:#60A5FA;">ONNECT</span></span>
                    @endif
                  </td>
                </tr>
              </table>
              <div style="margin-top:10px;">
                <span style="display:inline-block;background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);border-radius:999px;padding:3px 10px;color:{{ $roleMeta['accent'] }};font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;">{{ $roleMeta['badge'] }}</span>
              </div>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td class="vk-px" style="padding:32px;">
              {!! $slot !!}
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td class="vk-px" style="padding:24px 32px;border-top:1px solid #e2e8f0;background:#f8fafc;">
              <p style="margin:0 0 10px;color:#334155;font-size:12px;font-weight:700;line-height:1.7;">
                VKollab Technologies Pvt Ltd
              </p>
              <p style="margin:0 0 4px;color:#64748b;font-size:12px;line-height:1.7;">
                Need help? Contact us at
                <a href="mailto:{{ $supportEmailValue }}" style="color:#1D4ED8;text-decoration:none;">{{ $supportEmailValue }}</a>
                &middot;
                <a href="{{ config('app.frontend_url') }}" style="color:#1D4ED8;text-decoration:none;">{{ preg_replace('#^https?://#', '', rtrim(config('app.frontend_url'), '/')) }}</a>
              </p>
              <p style="margin:0;color:#94a3b8;font-size:11px;">
                &copy; {{ date('Y') }} {{ $platformNameValue }} Alumni Portal. All Rights Reserved.
              </p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
