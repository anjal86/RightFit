# RightFit Allied Health Services — one shared website

## Architecture (updated 3 November 2026)

**Single design/content source:** `index.html`, `about.html`, `services.html`, `contact.html`, `styles.css`, `script.js`, and the `assets/` directory.

The four `.html` pages are **the canonical editable frontend**. The PHP pages are now tiny wrappers:

- `index.php` → `rfRenderPage('index')`
- `about.php` → `rfRenderPage('about')`
- `services.php` → `rfRenderPage('services')`
- `contact.php` → `rfRenderPage('contact')`

`includes/render_site.php` reads the same HTML template used by GitHub Pages and provides PHP navigation, canonical URL overrides, session-aware CSRF fields, submission type, PHP checkbox arrays, and escaped flash messages. **There is no longer a separate PHP copy of the design, headings, imagery or service cards.** The old PHP header/footer page templates have been retired.

### What was carried over from the better PHP version

- Real `submit.php` action (PHP hosting only), `csrf_token` and `form_type` hidden fields.
- Existing honeypot, NDIS referral fields and the multi-service `services_requested[]` input array.
- PHP's server-side success/error flash feedback and the existing database/admin prototype.
- Security controls retained: HTTPS, server-side CSRF verification, no hardcoded admin password, opt-in intake and admin enable flags.
- Extra apple-touch favicon and Lucide line-icon submit actions.
- All design improvements from the HTML preview: photographs, illustrations, responsive Services page media, homepage viewport hero and one-play loader.

### Preview (GitHub Pages)
https://anjal86.github.io/RightFit/

GitHub Pages runs HTML/CSS/JavaScript only. Its public enquiry/referral forms are **concept previews** and **do not save or transmit completed submissions to the RightFit PHP database**. Never enter real participant health information into the preview.

### PHP production
On PHP/cPanel hosting, deploy the same repository contents to `public_html`. Apache `.htaccess` sets `DirectoryIndex index.php index.html` and redirects `*.html` public page URLs to the PHP wrappers. On other web servers, configure equivalent routing. The PHP wrappers share the current HTML source at runtime: editing `services.html` immediately changes the PHP services page without editing `services.php`.

The PHP **intake endpoint is disabled by default**. To activate it after a security/privacy review, supply `RIGHTFIT_INTAKE_ENABLED=1` in server environment, enable HTTPS, configure the approved database/location and verify real end-to-end submission delivery. The PHP admin remains gated by `RIGHTFIT_ADMIN_ENABLED=1` plus a unique `RIGHTFIT_ADMIN_BOOTSTRAP_PASSWORD` of at least 16 characters in server environment. Existing databases seeded with the previously published password must have that account reset.

The PHP presenter does not fall back to clipboard/email when a real backend request fails. Browser JavaScript displays an explicit error instead. Leave forms as a visual preview until approved and tested.

### Business copy and photography
The original detailed client-review content remains visible until the client approves, corrects or removes it. Placeholder phone numbers, emails, operating hours, funding and practitioner/regulatory claims are **unverified** and **not approved for launch**. Pages retain `noindex` by default. PHP hosts may set `RIGHTFIT_SEO_APPROVED=1` only after verified domain, copy and compliance approval. Stock photos are illustrative, not actual RightFit staff or clients. See `PHOTO_CREDITS.md`.

### Tests
- `node tests/check-static.mjs` validates the preview's internal anchors and assets.
- `node tests/check-shared-render.mjs` serves PHP with its built-in server, compares the complete shared page content against HTML, and verifies intake fields and routing.
- `node tests/check-home-viewport.mjs` checks the homepage first-screen geometry on 7 device sizes.
- `python3 tools/verify_loader.py` checks the slowed, one-play animation frames.
- The GitHub Actions workflow runs these checks along with PHP lint and photo URL validation.

### Client communications
Keep Emmanuel CC'd on all RightFit correspondence when his verified email is supplied. No correspondence is sent by the website.
