# RightFit Allied Health Services — Website & Admin Portal (cPanel Ready)

A modern, accessible, high-performance PHP website and secure administrative intake dashboard for **RightFit Allied Health Services**, tailored for Australian NDIS participants, families, support coordinators, and healthcare referrers.

Designed for **direct cPanel deployment** (drag-and-drop into `public_html`), requiring **zero command-line build steps, zero Composer dependencies, and zero configuration** out-of-the-box.

---

## 🏗️ Architecture & Features

### 1. Frontend Pages (PHP Partials)
- **[`index.php`](file:///Users/shrestha/RightFit/index.php)** — Homepage, brand value proposition, 4 service highlights, trust/governance badges, service delivery modes, process overview, animated once-per-visit preloader, and interactive FAQ.
- **[`about.php`](file:///Users/shrestha/RightFit/about.php)** — Person-centred philosophy, clinical governance standards (AHPRA, SPA, NDIS Safeguards Commission), and Australian Privacy Principles (APP) compliance.
- **[`services.php`](file:///Users/shrestha/RightFit/services.php)** — Comprehensive clinical guides for **Physiotherapy**, **Positive Behaviour Support (BSP)**, **Occupational Therapy**, and **Speech Pathology**, with sticky category filters, mobile service delivery details, and Australian NDIS pricing guides.
- **[`contact.php`](file:///Users/shrestha/RightFit/contact.php)** — Interactive intake & enquiry portal:
  - **General Enquiry**: Fast 3-field note with service selector.
  - **Participant Referral Form**: Full clinical intake (Referrer Details, Participant Details, Suburb, NDIS Number, Funding Type, Multi-Discipline Checkboxes, Clinical Goals, Consent Confirmation).
  - CSRF protection & anti-spam honeypot.
  - Real-time AJAX feedback with clipboard/download fallback.
- **[`includes/header.php`](file:///Users/shrestha/RightFit/includes/header.php)** & **[`includes/footer.php`](file:///Users/shrestha/RightFit/includes/footer.php)** — Reusable template partials with dynamic active page indicator and automatic copyright year.

### 2. Backend & Admin Portal
- **[`submit.php`](file:///Users/shrestha/RightFit/submit.php)** — Unified secure intake endpoint:
  - Handles both JSON AJAX and standard POST submissions.
  - Invisible honeypot trap against automated spam bots.
  - Sanitization and server-side validation.
  - Immediate database insertion and optional instant email notification via PHP `mail()`.
- **[`admin/login.php`](file:///Users/shrestha/RightFit/admin/login.php)** — Secure staff authentication portal:
  - Brute-force rate limiting (locks IP after 5 failed attempts within 15 minutes).
  - Secure session handling (`HttpOnly`, `SameSite=Lax`).
  - CSRF verification on login.
- **[`admin/index.php`](file:///Users/shrestha/RightFit/admin/index.php)** — Administrative Intake Dashboard:
  - Key performance metrics: *Pending Action*, *Total Referrals*, *General Enquiries*, *Total Records*.
  - Live search by participant name, referrer name, email, or suburb.
  - Filters by record type (`enquiry` vs `referral`) and status (`new`, `in_review`, `contacted`, `closed`).
  - Inline one-click status updater.
- **[`admin/view.php`](file:///Users/shrestha/RightFit/admin/view.php)** — Clinical Referral Dossier:
  - Clean two-column participant and referrer dossier.
  - One-click phone dialing and direct email composer.
  - Editable internal clinical notes (saved in real time).
  - Submission audit log (Timestamp, IP address, Client browser agent).
- **[`admin/export.php`](file:///Users/shrestha/RightFit/admin/export.php)** — Streaming CSV export with UTF-8 BOM, compatible with Microsoft Excel, Google Sheets, and CRM tools.
- **[`admin/settings.php`](file:///Users/shrestha/RightFit/admin/settings.php)** — Admin password manager with current password verification and bcrypt hashing.

---

## 🔐 Default Admin Credentials

| Parameter | Value |
|---|---|
| **Portal URL** | `https://your-domain.com.au/admin/login.php` |
| **Username** | `admin` |
| **Default Password** | `RightFit2026!` |

> [!IMPORTANT]
> Immediately log in and change this default password via the **Settings** menu (`admin/settings.php`) upon initial deployment to your production server.

---

## 🚀 cPanel Deployment (Step-by-Step)

Uploading to standard cPanel shared hosting takes under 3 minutes:

1. **Log in to your cPanel Dashboard**.
2. Open **File Manager** and navigate into the `public_html` directory (or your addon domain's document root).
3. **Upload all files and folders** from this repository:
   - Folders: `admin/`, `assets/`, `data/`, `includes/`
   - Files: `.htaccess`, `config.php`, `index.php`, `about.php`, `services.php`, `contact.php`, `submit.php`, `styles.css`, `script.js`, `robots.txt`, `sitemap.xml`
4. **Ensure File Permissions**:
   - Folders: `755`
   - Files: `644`
   - `data/` folder and `data/rightfit.sqlite`: `755` (or writable by the web server user `775`/`777` if Apache runs as an unprivileged process without suPHP).
5. **Security Verification**:
   - The included `data/.htaccess` denies all public HTTP access to SQLite database files.
   - The root `.htaccess` protects `config.php`, `data/`, and `includes/` from external web visitors, and enables clean extension-less URLs (`/about` $\to$ `about.php`).
6. **Visit Your Domain**:
   - Visit `https://your-domain.com.au/`
   - Visit `https://your-domain.com.au/admin/` to log into the intake dashboard.

---

## 🗄️ Database Configuration (SQLite vs MySQL)

The system works out of the box with zero setup using **SQLite** (`data/rightfit.sqlite`), which automatically initializes its tables upon the very first request.

If your host prefers **MySQL / MariaDB** (via cPanel MySQL Databases & phpMyAdmin):
1. In cPanel, create a new MySQL database and user.
2. Open **[`config.php`](file:///Users/shrestha/RightFit/config.php)** and update the configuration block:
   ```php
   define('DB_DRIVER', 'mysql'); // Switch from 'sqlite' to 'mysql'
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'yourcpanel_rightfit');
   define('DB_USER', 'yourcpanel_rfuser');
   define('DB_PASS', 'YourSecurePasswordHere');
   ```
3. The database tables (`submissions`, `admins`, `login_attempts`) and default admin account will automatically self-install on the first page load!

---

## 📧 Email Notification Setup

To receive an instant email alert every time a participant referral or enquiry is lodged:
1. Open **[`config.php`](file:///Users/shrestha/RightFit/config.php)**.
2. Set:
   ```php
   define('NOTIFY_ADMIN_EMAIL', true);
   define('ADMIN_NOTIFICATION_EMAIL', 'referrals@rightfitalliedhealth.com.au');
   ```
3. The system will use standard PHP `mail()` configured on your cPanel mail server.

---

## 🎨 Visual Design System & Brand Palette

- **Deep Ink Teal**: `#193D44`
- **Forest Green**: `#365849`
- **Sage**: `#799487`
- **Copper Accent**: `#BD815D` (Accessible text contrast: `#8E502C`)
- **Warm Cream**: `#F7F3EC`
- **Ivory Background**: `#FCFAF6`

---

## 💻 Local Development & Testing

To run the full PHP site and admin panel locally:

```bash
php -S localhost:8000
```

1. Public Site: [http://localhost:8000](http://localhost:8000)
2. Submit Enquiry / Referral: [http://localhost:8000/contact.php](http://localhost:8000/contact.php)
3. Admin Portal: [http://localhost:8000/admin/login.php](http://localhost:8000/admin/login.php)

---

## 📋 Client Communication Requirement
Per project instructions: **Emmanuel must be included on every external project correspondence**. Keep him CC'd on updates, approvals, credentials, and domain configuration.
