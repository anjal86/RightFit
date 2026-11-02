<?php
$pageTitle = "About RightFit Allied Health Services | Empowering Ability";
$pageDescription = "Learn about RightFit Allied Health Services — our person-centred philosophy, multidisciplinary allied health clinicians, and commitment to accessible care across Australia.";
$currentPage = "about";
$showLoader = false;
require_once __DIR__ . "/includes/header.php";
?>

<!-- Page Hero -->
    <section class="page-hero" aria-labelledby="about-heading">
      <div class="container page-hero-grid">
        <div>
          <div class="eyebrow">OUR APPROACH</div>
          <h1 id="about-heading">People first.<br><em>Always.</em></h1>
          <p>
            Meaningful allied health care begins with active listening. We recognise each individual’s unique strengths, life context, and vision for independence.
          </p>
        </div>
        <div class="page-hero-stamp">
          <picture>
            <source srcset="assets/occupational-therapy-art.webp" type="image/webp">
            <img src="assets/occupational-therapy-art.png" alt="RightFit illustration representing supportive companionship" width="220" height="220">
          </picture>
        </div>
      </div>
    </section>

    <!-- Intro Grid -->
    <section class="section intro-section">
      <div class="container intro-grid">
        <div class="section-overline">
          <span class="star-icon" aria-hidden="true">✳</span>
          <span>WHAT RIGHTFIT STANDS FOR</span>
        </div>
        <div>
          <h2 class="section-title">There is no single template for <em>making progress.</em></h2>
          <p class="lead">
            At RightFit, our name represents our core principle: support must fit around the individual, rather than forcing the person to adapt to a rigid system.
          </p>
          <p class="supporting-copy">
            We unite four complementary disciplines — Physiotherapy, Positive Behaviour Support (BSP), Occupational Therapy, and Speech Pathology. By collaborating under one cohesive framework, we ensure our participants experience seamless, integrated care without fragmented communication.
          </p>
        </div>
      </div>
    </section>

    <!-- Values & Philosophy -->
    <section class="section values-section about-values">
      <div class="container values-grid">
        <div class="values-visual">
          <div class="values-visual-inner">
            <div class="halo"></div>
            <picture>
              <source srcset="assets/physiotherapy-art.webp" type="image/webp">
              <img src="assets/physiotherapy-art.png" alt="Movement and growth artwork" loading="lazy">
            </picture>
            <span class="values-caption">YOUR GOALS ARE ALWAYS THE STARTING POINT</span>
          </div>
        </div>
        <div class="values-copy">
          <div class="eyebrow">OUR CLINICAL PILLARS</div>
          <h2 class="section-title">Support that feels <em>human.</em></h2>
          <div class="values-list">
            <div>
              <span class="values-numeric">01</span>
              <div>
                <h3>Unconditional respect &amp; dignity</h3>
                <p>We honor every person's autonomy, cultural identity, communication style, and lived experience.</p>
              </div>
            </div>
            <div>
              <span class="values-numeric">02</span>
              <div>
                <h3>True partnership &amp; transparency</h3>
                <p>We work hand-in-hand with participants, family members, nominated carers, and support teams with honest, jargon-free dialogue.</p>
              </div>
            </div>
            <div>
              <span class="values-numeric">03</span>
              <div>
                <h3>Capacity building for life</h3>
                <p>Our therapies don't just treat symptoms — they empower participants with self-advocacy, confidence, and tangible practical skills.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Professional Standards & Governance -->
    <section class="section" id="standards" style="background: #f7f6f1; border-top: 1px solid var(--line);">
      <div class="container">
        <div class="section-heading-row">
          <div>
            <div class="eyebrow">CLINICAL EXCELLENCE</div>
            <h2 class="section-title">Rigorous standards &amp;<br><em>trusted governance.</em></h2>
          </div>
          <p>Our clinical practice adheres strictly to national Australian health codes and disability safeguarding regulations.</p>
        </div>

        <div class="delivery-grid">
          <div class="delivery-card">
            <div class="delivery-icon" aria-hidden="true">🛡️</div>
            <h3>AHPRA Registered Clinicians</h3>
            <p>Our Physiotherapists and Occupational Therapists are registered with the Australian Health Practitioner Regulation Agency, adhering to strict professional standards.</p>
          </div>
          <div class="delivery-card">
            <div class="delivery-icon" aria-hidden="true">🗣️</div>
            <h3>Speech Pathology Australia</h3>
            <p>Our Speech Pathologists hold Certified Practising Speech Pathologist (CPSP) accreditation with Speech Pathology Australia (SPA).</p>
          </div>
          <div class="delivery-card">
            <div class="delivery-icon" aria-hidden="true">📋</div>
            <h3>NDIS Safeguards Commission</h3>
            <p>Our Behaviour Support Practitioners operate under the NDIS Quality and Safeguards Commission rules, prioritising positive behaviour support and reducing restrictive practices.</p>
          </div>
          <div class="delivery-card" id="privacy">
            <div class="delivery-icon" aria-hidden="true">🔒</div>
            <h3>Privacy &amp; Data Rights</h3>
            <p>We strictly protect participant health records and personal information in full compliance with the Privacy Act 1988 and the Australian Privacy Principles (APP).</p>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
      <div class="container">
        <div class="cta-box">
          <div>
            <div class="eyebrow">CONNECT WITH RIGHTFIT</div>
            <h2>Let's find the right starting point.</h2>
            <p>Speak with our intake team, submit a client referral, or simply start an obligation-free conversation.</p>
            <div class="cta-buttons">
              <a class="btn btn-copper" href="contact.php?type=referral">Submit a Referral ↗</a>
              <a class="btn btn-dark" href="contact.php">General Enquiry ↗</a>
            </div>
          </div>
          <div class="cta-illustration" aria-hidden="true">
            <div class="cta-circle"></div>
            <span>R</span>
          </div>
        </div>
      </div>
    </section>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
