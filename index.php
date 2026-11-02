<?php
$pageTitle = "RightFit Allied Health Services | Physiotherapy, BSP, OT & Speech";
$pageDescription = "RightFit Allied Health Services — Empowering ability and enabling independence. Person-centred Physiotherapy, Behaviour Support (BSP), Occupational Therapy, and Speech Pathology across Australia.";
$currentPage = "home";
$showLoader = true;
require_once __DIR__ . "/includes/header.php";
?>

<!-- Hero Section -->
    <section class="hero" aria-labelledby="hero-heading">
      <div class="container hero-grid">
        <div class="hero-copy">
          <div class="eyebrow"><span class="eyebrow-line"></span> RIGHTFIT ALLIED HEALTH SERVICES</div>
          <h1 id="hero-heading">Every person.<br>Every possibility.<br><em>Every step forward.</em></h1>
          <p class="hero-intro">
            Empowering ability. Enabling independence. Thoughtful allied health support designed around your unique strengths, goals, and everyday life.
          </p>
          <div class="hero-buttons">
            <a class="btn btn-copper" href="contact.php?type=referral">Make a Referral <span aria-hidden="true">↗</span></a>
            <a class="btn btn-outline" href="services.php">Explore our services</a>
          </div>
          <div class="hero-note">
            <span class="note-mark" aria-hidden="true">✳</span>
            <span>Person-centred care.<br>Meaningful everyday progress.</span>
          </div>
        </div>

        <div class="hero-visual" role="img" aria-label="RightFit leafy supportive illustrations representing holistic allied health care">
          <div class="hero-orbit hero-orbit-1"></div>
          <div class="hero-orbit hero-orbit-2"></div>
          <span class="hero-bloom bloom-one" aria-hidden="true">✳</span>
          <span class="hero-bloom bloom-two" aria-hidden="true">✦</span>
          <div class="hero-art-card hero-art-primary">
            <picture>
              <source srcset="assets/occupational-therapy-art.webp" type="image/webp">
              <img src="assets/occupational-therapy-art.png" alt="Occupational Therapy illustration" width="285" height="309">
            </picture>
          </div>
          <div class="hero-art-card hero-art-secondary">
            <picture>
              <source srcset="assets/behaviour-support-art.webp" type="image/webp">
              <img src="assets/behaviour-support-art.png" alt="Positive Behaviour Support illustration" width="185" height="196">
            </picture>
          </div>
          <div class="hero-visual-label">
            <span class="tiny-circle" aria-hidden="true"></span>
            Care that meets you where you are.
          </div>
        </div>
      </div>

      <div class="hero-footer container">
        <span>SCROLL TO EXPLORE</span>
        <span class="hero-footer-line"></span>
        <span>01 / GET TO KNOW RIGHTFIT</span>
      </div>
    </section>

    <!-- Trust Strip -->
    <section class="trust-strip" aria-label="Key Service Highlights">
      <div class="container trust-grid">
        <div class="trust-item">
          <div class="trust-icon" aria-hidden="true">🩺</div>
          <div>
            <h4>AHPRA &amp; SPA Registered</h4>
            <p>Qualified, vetted clinicians</p>
          </div>
        </div>
        <div class="trust-item">
          <div class="trust-icon" aria-hidden="true">🤝</div>
          <div>
            <h4>NDIS Registered / Aligned</h4>
            <p>Plan &amp; self-managed welcome</p>
          </div>
        </div>
        <div class="trust-item">
          <div class="trust-icon" aria-hidden="true">🏡</div>
          <div>
            <h4>Mobile &amp; In-Home Visits</h4>
            <p>Support in natural settings</p>
          </div>
        </div>
        <div class="trust-item">
          <div class="trust-icon" aria-hidden="true">⚡</div>
          <div>
            <h4>Prompt Intake &amp; Booking</h4>
            <p>Fast turnaround on referrals</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Intro Section -->
    <section class="section intro-section" id="about-preview">
      <div class="container intro-grid">
        <div class="section-overline">
          <span class="star-icon" aria-hidden="true">✳</span>
          <span>HELLO, WE'RE RIGHTFIT</span>
        </div>
        <div>
          <h2 class="section-title">Better support begins with <em>being understood.</em></h2>
          <p class="lead">
            We believe allied health support should start with the person — their unique strengths, everyday priorities, and what makes life fulfilling.
          </p>
          <p class="supporting-copy">
            Our multidisciplinary team works collaboratively across Physiotherapy, Behaviour Support, Occupational Therapy, and Speech Pathology. We listen closely, respect your pace, and build practical strategies that foster lasting independence.
          </p>
          <a class="inline-link" href="about.php">More about our philosophy &amp; team <span aria-hidden="true">↗</span></a>
        </div>
      </div>
    </section>

    <!-- Services Grid Section -->
    <section class="section services-section" id="services">
      <div class="container">
        <div class="section-heading-row">
          <div>
            <div class="eyebrow">WHAT WE OFFER</div>
            <h2 class="section-title">Care in many forms.<br><em>One shared purpose.</em></h2>
          </div>
          <p>
            Explore our four integrated disciplines, each tailored to empower ability and support independence at every life stage.
          </p>
        </div>

        <div class="services-grid">
          <!-- 01 Physiotherapy -->
          <article class="service-card physio">
            <div class="service-card-top">
              <span class="service-index">01 / MOVEMENT &amp; MOBILITY</span>
              <a href="services.php#physiotherapy" class="circle-arrow" aria-label="Read more about Physiotherapy">↗</a>
            </div>
            <div class="service-art">
              <picture>
                <source srcset="assets/physiotherapy-art.webp" type="image/webp">
                <img src="assets/physiotherapy-art.png" alt="" loading="lazy" width="220" height="150">
              </picture>
            </div>
            <h3>Physiotherapy</h3>
            <p>Supporting gross motor development, physical rehabilitation, strength, balance, and confident movement in everyday life.</p>
            <div class="service-more">
              <a href="services.php#physiotherapy" class="text-link">Learn more <span aria-hidden="true">→</span></a>
            </div>
          </article>

          <!-- 02 Behaviour Support -->
          <article class="service-card behaviour">
            <div class="service-card-top">
              <span class="service-index">02 / POSITIVE SUPPORT</span>
              <a href="services.php#behaviour-support" class="circle-arrow" aria-label="Read more about Behaviour Support">↗</a>
            </div>
            <div class="service-art">
              <picture>
                <source srcset="assets/behaviour-support-art.webp" type="image/webp">
                <img src="assets/behaviour-support-art.png" alt="" loading="lazy" width="220" height="150">
              </picture>
            </div>
            <h3>Behaviour Support <span class="service-subhead">(BSP)</span></h3>
            <p>Evidence-based Positive Behaviour Support (PBS) that understands needs, reduces restrictive practices, and enhances quality of life.</p>
            <div class="service-more">
              <a href="services.php#behaviour-support" class="text-link">Learn more <span aria-hidden="true">→</span></a>
            </div>
          </article>

          <!-- 03 Occupational Therapy -->
          <article class="service-card occupational">
            <div class="service-card-top">
              <span class="service-index">03 / EVERYDAY INDEPENDENCE</span>
              <a href="services.php#occupational-therapy" class="circle-arrow" aria-label="Read more about Occupational Therapy">↗</a>
            </div>
            <div class="service-art">
              <picture>
                <source srcset="assets/occupational-therapy-art.webp" type="image/webp">
                <img src="assets/occupational-therapy-art.png" alt="" loading="lazy" width="220" height="150">
              </picture>
            </div>
            <h3>Occupational Therapy</h3>
            <p>Building functional skills, sensory regulation, assistive technology recommendations, and home modifications for daily living.</p>
            <div class="service-more">
              <a href="services.php#occupational-therapy" class="text-link">Learn more <span aria-hidden="true">→</span></a>
            </div>
          </article>

          <!-- 04 Speech Pathology -->
          <article class="service-card speech">
            <div class="service-card-top">
              <span class="service-index">04 / COMMUNICATION</span>
              <a href="services.php#speech-pathology" class="circle-arrow" aria-label="Read more about Speech Pathology">↗</a>
            </div>
            <div class="service-art">
              <picture>
                <source srcset="assets/speech-pathology-art.webp" type="image/webp">
                <img src="assets/speech-pathology-art.png" alt="" loading="lazy" width="220" height="150">
              </picture>
            </div>
            <h3>Speech Pathology</h3>
            <p>Fostering verbal communication, AAC (Augmentative and Alternative Communication), social connection, and safe swallowing.</p>
            <div class="service-more">
              <a href="services.php#speech-pathology" class="text-link">Learn more <span aria-hidden="true">→</span></a>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- Delivery Modes Section -->
    <section class="delivery-section">
      <div class="container">
        <div class="section-heading-row">
          <div>
            <div class="eyebrow">FLEXIBLE CARE</div>
            <h2 class="section-title">Support where you feel<br><em>most comfortable.</em></h2>
          </div>
          <p>We bring high-quality allied health care directly to your everyday environment.</p>
        </div>

        <div class="delivery-grid">
          <div class="delivery-card">
            <div class="delivery-icon" aria-hidden="true">🏡</div>
            <h3>In-Home Visits</h3>
            <p>Therapy delivered in your living environment, making goals directly relevant to real routines and daily tasks.</p>
          </div>
          <div class="delivery-card">
            <div class="delivery-icon" aria-hidden="true">🏫</div>
            <h3>School &amp; Day Care</h3>
            <p>Collaborating with educators and support staff so strategies are consistently applied during learning and social times.</p>
          </div>
          <div class="delivery-card">
            <div class="delivery-icon" aria-hidden="true">🏢</div>
            <h3>Clinic Consultations</h3>
            <p>Dedicated clinical spaces equipped with specialised assessment and therapy tools in welcoming environments.</p>
          </div>
          <div class="delivery-card">
            <div class="delivery-icon" aria-hidden="true">💻</div>
            <h3>Telehealth Australia-Wide</h3>
            <p>Secure, convenient video consultations ensuring access to our experienced clinicians regardless of location.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Values Section -->
    <section class="section values-section">
      <div class="container values-grid">
        <div class="values-visual">
          <div class="values-visual-inner">
            <div class="halo"></div>
            <picture>
              <source srcset="assets/behaviour-support-art.webp" type="image/webp">
              <img src="assets/behaviour-support-art.png" alt="Growth and connection illustration" loading="lazy">
            </picture>
            <span class="values-caption">GROWTH LOOKS DIFFERENT FOR EVERYONE</span>
          </div>
        </div>
        <div class="values-copy">
          <div class="eyebrow">A DIFFERENT WAY TO CARE</div>
          <h2 class="section-title">Your journey is <em>uniquely yours.</em></h2>
          <p class="lead">No two people have the same goals, strengths, or circumstances. Good support creates space for that.</p>
          <div class="values-list">
            <div>
              <span class="values-numeric">01</span>
              <div>
                <h3>We start by listening</h3>
                <p>Understanding what matters to you comes before deciding what therapy and support should look like.</p>
              </div>
            </div>
            <div>
              <span class="values-numeric">02</span>
              <div>
                <h3>We work collaboratively</h3>
                <p>Transparent communication with participants, families, support coordinators, and plan managers shapes a clear path forward.</p>
              </div>
            </div>
            <div>
              <span class="values-numeric">03</span>
              <div>
                <h3>We celebrate everyday progress</h3>
                <p>Consistent, small milestones compound into meaningful confidence, active participation, and independence.</p>
              </div>
            </div>
          </div>
          <a class="inline-link" href="about.php">Explore our philosophy &amp; standards <span aria-hidden="true">↗</span></a>
        </div>
      </div>
    </section>

    <!-- Statement Section -->
    <section class="statement-section" aria-label="Brand Philosophy Statement">
      <div class="container statement-grid">
        <div class="statement-small">
          <span class="statement-spark" aria-hidden="true">✳</span>
          <p>THE RIGHT SUPPORT<br>MAKES ALL THE DIFFERENCE.</p>
        </div>
        <div>
          <p class="statement-copy">It is not about fitting people into a plan. It is about <em>finding the right fit</em> for the person.</p>
          <span class="statement-attrib">RIGHTFIT ALLIED HEALTH SERVICES</span>
        </div>
      </div>
    </section>

    <!-- Getting Started Process -->
    <section class="section process-section">
      <div class="container">
        <div class="section-heading-row">
          <div>
            <div class="eyebrow">GETTING STARTED</div>
            <h2 class="section-title">A seamless intake step<br><em>towards what is next.</em></h2>
          </div>
          <p>Whether you are self-referring or submitting a referral on behalf of a participant, our process is clear and supportive.</p>
        </div>

        <div class="steps-grid">
          <div class="step">
            <span class="step-number">01</span>
            <div class="step-drawing" aria-hidden="true">✦</div>
            <h3>Submit a referral</h3>
            <p>Complete our quick online referral form or contact us directly with the support areas needed.</p>
          </div>
          <div class="step">
            <span class="step-number">02</span>
            <div class="step-drawing" aria-hidden="true">◎</div>
            <h3>Intake consultation</h3>
            <p>We review the referral, discuss priorities, confirm funding, and match you with the right clinician.</p>
          </div>
          <div class="step">
            <span class="step-number">03</span>
            <div class="step-drawing" aria-hidden="true">↗</div>
            <h3>Begin your therapy plan</h3>
            <p>Your clinician conducts the initial assessment and co-creates a goal-oriented support plan.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section" aria-labelledby="faq-title">
      <div class="container">
        <div class="faq-header">
          <div class="eyebrow">COMMON QUESTIONS</div>
          <h2 class="section-title" id="faq-title">Frequently asked <em>questions.</em></h2>
          <p class="supporting-copy" style="margin: 0 auto;">Everything you need to know about working with RightFit Allied Health Services.</p>
        </div>

        <div class="faq-list">
          <details class="faq-item">
            <summary class="faq-question">
              <span>What funding types does RightFit accept?</span>
              <span class="faq-icon" aria-hidden="true">+</span>
            </summary>
            <div class="faq-answer">
              <p>We accept NDIS participants who are <strong>Plan-Managed</strong> or <strong>Self-Managed</strong> across Capacity Building (e.g. Improved Daily Living, Improved Relationships) and Core budgets. We also welcome private-paying clients, Medicare Chronic Disease Management (CDM/EPC) plans from your GP, and private health insurance extras.</p>
            </div>
          </details>

          <details class="faq-item">
            <summary class="faq-question">
              <span>Do I need a doctor's referral to see an allied health therapist?</span>
              <span class="faq-icon" aria-hidden="true">+</span>
            </summary>
            <div class="faq-answer">
              <p>No doctor's referral is required for private or NDIS-funded sessions. You or your Support Coordinator can submit a referral directly. A GP referral is only required if you plan to claim Medicare rebates under a Chronic Disease Management (CDM) plan.</p>
            </div>
          </details>

          <details class="faq-item">
            <summary class="faq-question">
              <span>How quickly can you see a new participant?</span>
              <span class="faq-icon" aria-hidden="true">+</span>
            </summary>
            <div class="faq-answer">
              <p>We prioritise responsive intake. In most cases, our clinical intake coordinator reviews referrals within 1–2 business days to confirm capacity, discuss goals, and schedule an initial consultation without lengthy wait times.</p>
            </div>
          </details>

          <details class="faq-item">
            <summary class="faq-question">
              <span>Can your clinicians come to our home or school?</span>
              <span class="faq-icon" aria-hidden="true">+</span>
            </summary>
            <div class="faq-answer">
              <p>Yes. A core part of our philosophy is providing community and mobile therapy where you are most comfortable — at home, school, day programmes, or workplaces. Telehealth sessions are also available Australia-wide.</p>
            </div>
          </details>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
      <div class="container">
        <div class="cta-box">
          <div>
            <div class="eyebrow">LET'S CONNECT</div>
            <h2>Ready to find the right fit?</h2>
            <p>Our team is here to answer your questions, explore support options, and start your journey.</p>
            <div class="cta-buttons">
              <a class="btn btn-copper" href="contact.php?type=referral">Make a Referral <span aria-hidden="true">↗</span></a>
              <a class="btn btn-dark" href="contact.php">General Enquiry <span aria-hidden="true">↗</span></a>
            </div>
          </div>
          <div class="cta-illustration" aria-hidden="true">
            <div class="cta-circle"></div>
            <span>R</span>
            <span class="cta-sun">✳</span>
          </div>
        </div>
      </div>
    </section>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
