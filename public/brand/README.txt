VKONNECT — BRAND LOGOS
======================

Drop your own logo image files into this folder (frontend/public/brand/) using
the EXACT file names below. The app picks them up automatically — no code change
needed. If a file is missing, the app falls back to the built-in placeholder logo
so nothing ever looks broken.

Recommended format: PNG with a transparent background (SVG also works if you
rename to the same base name and keep the extension .png is preferred).

FILES TO ADD
------------

1. vkollab-logo.png
   VKollab Technologies logo (parent company).
   Shown on LIGHT backgrounds (top-left of the public header, dashboard topbar).
   Recommended height ~72px (will be scaled to fit ~36px tall).

2. vkollab-logo-light.png        (optional)
   A WHITE / light version of the VKollab logo for DARK backgrounds
   (the login split-panel). If omitted, the placeholder is used there.

3. vkonnect-logo.png
   VKONNECT full logo (product) for LIGHT backgrounds.

4. vkonnect-logo-light.png       (optional)
   WHITE / light version of the VKONNECT logo for DARK backgrounds
   (sidebar, footer, login panel).

5. vkonnect-icon.png
   The VKONNECT "K" ICON only (square mark, no text). Used where space is tight
   (loading screen, favicon-style spots). Recommended a square image, e.g. 256x256.

OPTIONAL — BROWSER TAB ICON (favicon)
-------------------------------------
Replace vkonnect-mark.svg in this folder with your icon, or add a favicon.ico and
update <link rel="icon"> in frontend/index.html.

After adding files, just refresh the browser (no rebuild needed in dev).
