<?php
/**
 * RightFit Allied Health Services — Site Header Partial
 * 
 * Variables:
 * @var string $pageTitle       Page <title> tag
 * @var string $pageDescription Page meta description
 * @var string $currentPage     Active navigation id ('home', 'about', 'services', 'contact')
 * @var bool   $showLoader      Whether to display the full animated splash loader
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/auth.php';

$pageTitle = $pageTitle ?? 'RightFit Allied Health Services | Physiotherapy, BSP, OT & Speech';
$pageDescription = $pageDescription ?? 'RightFit Allied Health Services — Empowering ability and enabling independence. Person-centred Physiotherapy, Behaviour Support (BSP), Occupational Therapy, and Speech Pathology across Australia.';
$currentPage = $currentPage ?? 'home';
$showLoader = $showLoader ?? false;

function navClass(string $page, string $current): string {
    return $page === $current ? 'nav-link current' : 'nav-link';
}
function navAria(string $page, string $current): string {
    return $page === $current ? ' aria-current="page"' : '';
}
?>
<!doctype html>
<html lang="en-AU">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#193d44">
  <meta name="description" content="<?= e($pageDescription) ?>">
  <link rel="canonical" href="<?= APP_URL ?>/<?= $currentPage === 'home' ? '' : e($currentPage) ?>">

  <!-- Open Graph / Facebook / LinkedIn -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="<?= e(APP_NAME) ?>">
  <meta property="og:title" content="<?= e($pageTitle) ?>">
  <meta property="og:description" content="<?= e($pageDescription) ?>">
  <meta property="og:image" content="<?= APP_URL ?>/assets/rightfit-logo-white.png">
  <meta property="og:url" content="<?= APP_URL ?>/">
  <meta property="og:locale" content="en_AU">

  <!-- Twitter -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= e($pageTitle) ?>">
  <meta name="twitter:description" content="<?= e($pageDescription) ?>">
  <meta name="twitter:image" content="<?= APP_URL ?>/assets/rightfit-logo-white.png">

  <!-- Structured Data (JSON-LD) -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "MedicalBusiness",
    "name": "RightFit Allied Health Services",
    "description": "Person-centred Physiotherapy, Behaviour Support (BSP), Occupational Therapy, and Speech Pathology allied health services.",
    "slogan": "Empowering Ability. Enabling Independence.",
    "url": "<?= APP_URL ?>/",
    "logo": "<?= APP_URL ?>/assets/rightfit-logo-white.png",
    "medicalSpecialty": [
      "Physiotherapy",
      "OccupationalTherapy",
      "SpeechPathology"
    ],
    "areaServed": {
      "@type": "Country",
      "name": "Australia"
    },
    "availableService": [
      {
        "@type": "MedicalTherapy",
        "name": "Physiotherapy",
        "description": "Supporting movement, physical function and mobility in daily life."
      },
      {
        "@type": "MedicalTherapy",
        "name": "Positive Behaviour Support (BSP)",
        "description": "Person-centred behaviour support strategies and positive intervention plans."
      },
      {
        "@type": "MedicalTherapy",
        "name": "Occupational Therapy",
        "description": "Building capacity, daily living skills, and environmental modifications."
      },
      {
        "@type": "MedicalTherapy",
        "name": "Speech Pathology",
        "description": "Comprehensive speech, language, swallowing, and communication support."
      }
    ]
  }
  </script>

  <title><?= e($pageTitle) ?></title>
  <link rel="icon" href="assets/favicon.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <?php if ($showLoader): ?>
  <!-- Initial Website Loader (Accelerated Animation - Once Per Visit) -->
  <div id="site-loader" class="site-loader" aria-hidden="true">
    <div class="loader-media">
      <picture>
        <source srcset="assets/loader.webp" type="image/webp">
        <img src="assets/loader.gif" alt="RightFit Allied Health Services" class="loader-gif" width="340" height="191">
      </picture>
    </div>
    <div class="loader-caption">
      <span class="badge-dot"></span> RightFit Allied Health
    </div>
  </div>
  <?php endif; ?>

  <a class="skip-link" href="#main">Skip to main content</a>

  <!-- Utility Bar -->
  <div class="top-bar">
    <div class="container top-bar-inner">
      <div class="top-bar-left">
        <span class="top-bar-tag"><span class="badge-dot" aria-hidden="true"></span> Welcoming NDIS Plan-Managed &amp; Self-Managed Clients</span>
        <span class="top-bar-tag">In-Home, School &amp; Telehealth Available</span>
      </div>
      <div class="top-bar-right">
        <a href="tel:1300000000" aria-label="Call RightFit Allied Health Services on 1300 000 000">📞 1300 RIGHTFIT</a>
        <a href="contact.php?type=referral" class="top-bar-referral">Make a Referral ↗</a>
      </div>
    </div>
  </div>

  <!-- Primary Header -->
  <header class="site-header" id="top">
    <div class="container header-inner">
      <a class="brand" href="index.php" aria-label="RightFit Allied Health Services, home">
        <picture>
          <source srcset="assets/rightfit-logo-white.webp" type="image/webp">
          <img src="assets/rightfit-logo-white.png" alt="RightFit Allied Health Services Logo" width="220" height="92">
        </picture>
      </a>

      <nav class="desktop-nav" aria-label="Main navigation">
        <a class="<?= navClass('home', $currentPage) ?>" href="index.php"<?= navAria('home', $currentPage) ?>>Home</a>
        <a class="<?= navClass('about', $currentPage) ?>" href="about.php"<?= navAria('about', $currentPage) ?>>About us</a>
        <a class="<?= navClass('services', $currentPage) ?>" href="services.php"<?= navAria('services', $currentPage) ?>>Our services</a>
        <a class="<?= navClass('contact', $currentPage) ?>" href="contact.php"<?= navAria('contact', $currentPage) ?>>Contact &amp; Referrals</a>
      </nav>

      <div class="header-actions">
        <a class="btn btn-small btn-outline desktop-cta" href="contact.php">General Enquiry</a>
        <a class="btn btn-small btn-copper desktop-cta" href="contact.php?type=referral">Make a Referral ↗</a>
        <button class="menu-button" id="menu-button" aria-label="Open navigation" aria-controls="mobile-nav" aria-expanded="false">
          <span></span><span></span><span></span>
        </button>
      </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <nav class="mobile-nav" id="mobile-nav" aria-label="Mobile navigation" hidden>
      <a class="<?= navClass('home', $currentPage) ?>" href="index.php"<?= navAria('home', $currentPage) ?>>Home</a>
      <a class="<?= navClass('about', $currentPage) ?>" href="about.php"<?= navAria('about', $currentPage) ?>>About us</a>
      <a class="<?= navClass('services', $currentPage) ?>" href="services.php"<?= navAria('services', $currentPage) ?>>Our services</a>
      <a class="<?= navClass('contact', $currentPage) ?>" href="contact.php"<?= navAria('contact', $currentPage) ?>>Contact &amp; Referrals</a>
      <a class="btn btn-copper mobile-nav-cta" href="contact.php?type=referral">Make a Referral ↗</a>
      <a class="btn btn-light mobile-nav-cta" href="tel:1300000000">📞 Call 1300 RIGHTFIT</a>
    </nav>
  </header>

  <main id="main">
