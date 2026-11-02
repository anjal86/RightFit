# RightFit Allied Health Services — Client Review Website

## Live preview
https://anjal86.github.io/RightFit/

### Design restored on 3 November 2026
The original detailed website design, four HTML pages, original CSS and JS, all supplied illustrations, and original PHP public page templates have been restored from commit `5634b8a35f8f94ffad247510186c6cd9179baf71`.

**This is a client-review design, not approved business information.** The previous design's text, contact details, service claims, FAQs, pricing/funding descriptions, clinician-registration references, response times and locations remain visible **for client review only**. The client should decide which statements to keep, amend or remove. The prior contact numbers and email addresses have not been verified; do not use them for real enquiries. All pages use a temporary `noindex` tag until approval and production-domain setup.

### Online enquiries and referrals
The public GitHub Pages site **does not have a working referral backend**. It uses the original form interface and email-draft fallback. The action URLs are placeholders; no healthcare submissions are delivered or stored on GitHub Pages. Do not input actual NDIS numbers, dates of birth or private clinical information in the preview.

The legacy PHP intake endpoint remains **disabled by default** with `RIGHTFIT_INTAKE_ENABLED=1` needed to activate it after a privacy/security review, HTTPS and CSRF validation. The PHP admin prototype remains disabled by default and requires a unique administrator bootstrap password of at least 16 characters from `RIGHTFIT_ADMIN_BOOTSTRAP_PASSWORD` plus `RIGHTFIT_ADMIN_ENABLED=1`; the previously public default password is blocked. Never place secrets in Git.

### SEO and launch
Canonical and Open Graph URLs currently use the GitHub Pages preview. Update those and `sitemap.xml` to the verified custom domain after client approval, and remove the `noindex` meta tags when ready for public indexing.

### QA
Run `node tests/check-static.mjs` to validate the review pages and local assets. GitHub Actions also validates PHP syntax.

### Information required from client
Confirmed phone/WhatsApp, business emails, opening hours, appointment availability, addresses and map link, service areas, verified credentials/NDIS status, and final approval for all website copy.

### Communication
Always CC Emmanuel on external project correspondence (when his email address is provided).
