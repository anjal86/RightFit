# RightFit Allied Health Services — Website (Production Ready)

A responsive, high-performance, accessible four-page website for **RightFit Allied Health Services**, tailored for Australian NDIS participants, families, and healthcare referrers.

---

## 📄 Site Structure

- **[`index.html`](file:///Users/shrestha/RightFit/index.html)** — Homepage, brand value proposition, 4 service highlights, trust/governance badges, service delivery modes, process overview, and interactive FAQ.
- **[`about.html`](file:///Users/shrestha/RightFit/about.html)** — People-first philosophy, clinical governance standards (AHPRA, SPA, NDIS Safeguards), and Australian Privacy Principles (APP) compliance.
- **[`services.html`](file:///Users/shrestha/RightFit/services.html)** — In-depth breakdown of the four core disciplines, typical clinical goals, delivery modes, and transparent funding guides (NDIS, Medicare CDM, Private Health).
- **[`contact.html`](file:///Users/shrestha/RightFit/contact.html)** — Interactive dual-mode form interface:
  1. **General Enquiry** (quick 3-field message)
  2. **Client Referral (Intake)** (comprehensive intake for Support Coordinators, Plan Managers, GPs, and Participants with consent validation).
- **[`styles.css`](file:///Users/shrestha/RightFit/styles.css)** — Modern, clean design system with accessible contrast levels (WCAG 2.1 AA), sticky header, and responsive layouts across desktop, tablet, and mobile.
- **[`script.js`](file:///Users/shrestha/RightFit/script.js)** — Mobile menu navigation, URL parameter prefilling (`?service=...&type=referral`), form submission handling, honeypot spam protection, and clipboard/download fallbacks.
- **[`sitemap.xml`](file:///Users/shrestha/RightFit/sitemap.xml)** & **[`robots.txt`](file:///Users/shrestha/RightFit/robots.txt)** — Search engine indexing and SEO readiness.

---

## 🎨 Brand Identity

- **Deep Ink Teal**: `#193D44`
- **Forest Green**: `#365849`
- **Sage**: `#799487`
- **Copper Accent**: `#BD815D` (with `#8E502C` for accessible text contrast)
- **Warm Cream**: `#F7F3EC`
- **Ivory Background**: `#FCFAF6`

---

## 🚀 Key Improvements Implemented

1. **Intake & Referral Portal (Essential for NDIS Providers):**
   - Added a dedicated **Participant Referral Form** on `contact.html` with fields for Referrer Role, Organization, Participant Details, Suburb, NDIS Number, Funding Type (Plan-Managed, Self-Managed, etc.), Multi-select Services, and Consent Declarations.
   - Pre-selection support: clicking "Refer for Physiotherapy" from any page automatically opens the referral tab and checks the corresponding discipline.
2. **Resilient Form Handling & Zero Data Loss:**
   - Both forms are configured to submit via `fetch` to any standard POST endpoint (e.g., Formspree, Netlify Forms, or CRM webhook).
   - Built-in honeypot spam trap (`_website_trap`).
   - If offline or before a remote endpoint is connected, the form automatically generates a pre-formatted email mailto link, copies the summary to the user's clipboard, and provides a 1-click text file download.
3. **Accessibility (WCAG 2.1 AA):**
   - High-contrast typography adjustments.
   - Accessible keyboard-trapping and escape handlers on the mobile menu.
   - Semantic ARIA labels, skip-links, and tabpanel roles.
4. **Performance & Modern Image Formats:**
   - Generated high-efficiency **WebP** formats for all artwork assets, achieving an **89–91% reduction in file size** (~550KB down to ~55KB).
   - Responsive `<picture>` elements with fallback to PNG.
5. **SEO & Discoverability:**
   - Australian localized metadata (`lang="en-AU"`, `en_AU` Open Graph locale).
   - Complete `Schema.org` JSON-LD Structured Data for `MedicalBusiness` with medical specialties and service catalog.
   - Social sharing cards (Open Graph and Twitter Cards).
   - Generated valid `sitemap.xml` and `robots.txt`.
6. **Clinical Credibility & Trust:**
   - Trust strip highlighting AHPRA registration, Speech Pathology Australia (SPA), NDIS Quality & Safeguards compliance, and flexible delivery modes (In-Home, School, Clinic, Telehealth).

---

## ⚙️ Connecting a Live Form Endpoint

To connect the forms to a live backend (such as Formspree, Netlify, Basin, or your practice management software):

1. Open `contact.html`.
2. Locate the `<form id="enquiry-form"` and `<form id="referral-form"` tags.
3. Replace `action="https://formspree.io/f/placeholder"` with your actual endpoint URL (e.g. `https://formspree.io/f/xyzaabbc` or your webhook URL).

---

## 🌐 Local Preview

Serve this directory using Python's built-in static server:

```bash
python3 -m http.server 8000
```

Then visit [http://localhost:8000](http://localhost:8000) in your browser.

---

## 📋 Client Communication Note
Per client instructions: **Emmanuel must be included on every project correspondence**. Always keep him CC'd on updates, approvals, and domain configuration.
