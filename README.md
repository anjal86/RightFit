# RightFit Allied Health Services — Safe public preview

Live: https://anjal86.github.io/RightFit/

## Included
Four responsive static pages: Home, About, Services and Contact. Four client-supplied service areas and botanical illustrations are featured in the original deep teal, sage, copper and cream palette.

## Preview safeguards
- Contact numbers, email, hours, locations, WhatsApp and maps were NOT verified by the client. The preview displays no invented business details.
- Practitioner credentials, NDIS registration/funding, pricing, response times and service delivery regions were NOT verified. The public copy avoids these claims.
- This preview does NOT collect or submit participant information. There are no active enquiry or referral forms.
- Pages are marked noindex until client copy, domain and security controls are approved. Canonical and sitemap URLs currently point to the GitHub Pages preview.

## Production prerequisites
Obtain verified business contact details, service areas, opening hours, funding arrangements, qualifications, map location and accurate business domain. Add approved copy; switch canonical/sitemap URLs and remove noindex only when production-ready. Implement a reviewed, secure health-data intake service and test real delivery before activating referrals. Do not use email-draft fallbacks for health records.

## Inactive PHP prototype
GitHub Pages does not execute PHP. The four public PHP page routes redirect to the static HTML preview. `submit.php` is disabled unless `RIGHTFIT_INTAKE_ENABLED=1` is explicitly set in the server environment; leave disabled until security/privacy approval. No public default admin password is seeded: `RIGHTFIT_ADMIN_BOOTSTRAP_PASSWORD` must be set to a fresh server-side secret of at least 16 characters before initial setup. Admin login also requires `RIGHTFIT_ADMIN_ENABLED=1`. Any database ever seeded with the previously public password must be reset/rotated before use; modifying the file does not rotate existing credentials.

## Tests
Run `node tests/check-static.mjs` from the root. No dependencies needed.

## Client communication
Always include Emmanuel in external project correspondence; add his verified email to CC. No correspondence is sent by this repository.
