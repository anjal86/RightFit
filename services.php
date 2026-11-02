<?php
$pageTitle = "Allied Health Services Australia | Physiotherapy, BSP, OT & Speech | RightFit";
$pageDescription = "Comprehensive, evidence-based allied health across Australia: Physiotherapy, Positive Behaviour Support (BSP), Occupational Therapy, and Speech Pathology. NDIS registered rates, Medicare & private.";
$currentPage = "services";
$showLoader = false;
require_once __DIR__ . "/includes/header.php";
?>

<!-- Page Hero -->
    <section class="page-hero" aria-labelledby="services-heading">
      <div class="container page-hero-grid">
        <div>
          <div class="eyebrow">OUR ALLIED HEALTH DISCIPLINES</div>
          <h1 id="services-heading">Different needs.<br><em>Shared purpose.</em></h1>
          <p>
            Four integrated disciplines delivering person-centred allied health care across Australia. We help NDIS participants, families, and healthcare teams build functional capacity, independence, and wellbeing.
          </p>
        </div>
        <div class="page-hero-stamp service-stamp">
          <div class="mini-four">
            <picture><source srcset="assets/physiotherapy-art.webp" type="image/webp"><img src="assets/physiotherapy-art.png" alt="" width="100" height="100"></picture>
            <picture><source srcset="assets/behaviour-support-art.webp" type="image/webp"><img src="assets/behaviour-support-art.png" alt="" width="100" height="100"></picture>
            <picture><source srcset="assets/occupational-therapy-art.webp" type="image/webp"><img src="assets/occupational-therapy-art.png" alt="" width="100" height="100"></picture>
            <picture><source srcset="assets/speech-pathology-art.webp" type="image/webp"><img src="assets/speech-pathology-art.png" alt="" width="100" height="100"></picture>
          </div>
        </div>
      </div>
    </section>

    <!-- Services Detailed Section -->
    <section class="services-page-section">
      <div class="container">
        <!-- Quick Jump Filter Nav -->
        <nav class="service-nav-bar" aria-label="Quick jump to allied health discipline">
          <a class="service-nav-pill" href="#physiotherapy"><i data-lucide="activity"></i> Physiotherapy</a>
          <a class="service-nav-pill" href="#behaviour-support"><i data-lucide="sprout"></i> Behaviour Support (BSP)</a>
          <a class="service-nav-pill" href="#occupational-therapy"><i data-lucide="heart-handshake"></i> Occupational Therapy</a>
          <a class="service-nav-pill" href="#speech-pathology"><i data-lucide="message-square"></i> Speech Pathology</a>
          <a class="service-nav-pill" href="#funding"><i data-lucide="credit-card"></i> NDIS &amp; Funding Guide</a>
        </nav>

        <!-- 01 Physiotherapy -->
        <article class="service-showcase-card" id="physiotherapy">
          <div class="service-showcase-grid">
            <div class="service-content">
              <div class="service-badge-row">
                <span class="clinical-badge badge-ndis">NDIS Category 15: Improved Daily Living</span>
                <span class="clinical-badge badge-ahpra">AHPRA Registered Clinicians</span>
                <span class="clinical-badge badge-delivery">In-Home, Hydro &amp; Telehealth</span>
              </div>
              <div class="eyebrow">ALLIED HEALTH DISCIPLINE 01</div>
              <h2>Physiotherapy</h2>
              <div class="service-tagline">Movement, Mobility &amp; Physical Independence</div>
              <p class="service-description">
                Our AHPRA-registered physiotherapists work with children, adolescents, and adults across Australia to optimise movement, manage neurological conditions, improve respiratory function, and build physical confidence in natural daily environments.
              </p>

              <!-- Two Column Clinical Breakdown -->
              <div class="clinical-columns">
                <div class="clinical-col">
                  <h4>📋 Key Assessments &amp; Reports</h4>
                  <ul class="clinical-list">
                    <li><span class="check-mark"><i data-lucide="check"></i></span> <span>Comprehensive Functional Mobility &amp; Gait Analysis</span></li>
                    <li><span class="check-mark"><i data-lucide="check"></i></span> <span>Assistive Technology (AT) Prescriptions (Wheelchairs, Walkers)</span></li>
                    <li><span class="check-mark"><i data-lucide="check"></i></span> <span>Falls Risk, Balance &amp; Postural Alignment Assessments</span></li>
                    <li><span class="check-mark"><i data-lucide="check"></i></span> <span>Detailed NDIS Review &amp; Plan Reassessment Reports</span></li>
                  </ul>
                </div>
                <div class="clinical-col">
                  <h4><i data-lucide="activity"></i> Therapeutic Interventions</h4>
                  <ul class="clinical-list">
                    <li><span class="check-mark"><i data-lucide="check"></i></span> <span>Neurological Rehabilitation (Cerebral Palsy, Stroke, MS)</span></li>
                    <li><span class="check-mark"><i data-lucide="check"></i></span> <span>Aquatic Physiotherapy (Hydrotherapy) &amp; Pool Programs</span></li>
                    <li><span class="check-mark"><i data-lucide="check"></i></span> <span>Strength, Conditioning &amp; Motor Milestone Programs</span></li>
                    <li><span class="check-mark"><i data-lucide="check"></i></span> <span>Chest Physiotherapy &amp; Respiratory Care Protocols</span></li>
                  </ul>
                </div>
              </div>

              <!-- Action Bar -->
              <div class="service-action-bar">
                <div class="service-action-buttons">
                  <a class="btn btn-copper btn-small" href="contact.php?service=physiotherapy&amp;type=referral">Refer for Physiotherapy <i data-lucide="arrow-up-right"></i></a>
                  <a class="btn btn-outline btn-small" href="contact.php?service=physiotherapy">General Enquiry <i data-lucide="arrow-up-right"></i></a>
                </div>
                <div class="turnaround-pill">
                  <span class="badge-dot" aria-hidden="true"></span> Prompt Intake: Referrals reviewed within 1–2 business days
                </div>
              </div>
            </div>

            <!-- Artwork Side -->
            <div class="service-art-wrapper">
              <picture>
                <source srcset="assets/physiotherapy-art.webp" type="image/webp">
                <img src="assets/physiotherapy-art.png" alt="RightFit Physiotherapy movement and mobility illustration" class="service-art-image" width="280" height="280" loading="lazy">
              </picture>
              <div class="service-art-caption">01 / Movement &amp; Physical Strength</div>
            </div>
          </div>
        </article>

        <!-- 02 Behaviour Support (BSP) -->
        <article class="service-showcase-card" id="behaviour-support">
          <div class="service-showcase-grid reverse">
            <div class="service-content">
              <div class="service-badge-row">
                <span class="clinical-badge badge-ndis">NDIS Category 11: Improved Relationships</span>
                <span class="clinical-badge badge-ahpra">NDIS Commission Compliant</span>
                <span class="clinical-badge badge-delivery">Home, School &amp; Community</span>
              </div>
              <div class="eyebrow">ALLIED HEALTH DISCIPLINE 02</div>
              <h2>Positive Behaviour Support <span class="service-subhead">(BSP)</span></h2>
              <div class="service-tagline">Understanding Behaviour, Upholding Dignity &amp; Promoting Wellbeing</div>
              <p class="service-description">
                Operating strictly under the NDIS Quality and Safeguards Commission, our Behaviour Support Practitioners view all behaviour as communication. We develop respectful, proactive strategies that improve quality of life and reduce or eliminate regulated restrictive practices.
              </p>

              <!-- Two Column Clinical Breakdown -->
              <div class="clinical-columns">
                <div class="clinical-col">
                  <h4>📋 Key Assessments &amp; Lodgements</h4>
                  <ul class="clinical-list">
                    <li><span class="check-mark"><i data-lucide="check"></i></span> <span>Functional Behaviour Assessments (FBA) &amp; Root-Cause Analysis</span></li>
                    <li><span class="check-mark"><i data-lucide="check"></i></span> <span>Interim Behaviour Support Plans (developed &amp; lodged within 1 month)</span></li>
                    <li><span class="check-mark"><i data-lucide="check"></i></span> <span>Comprehensive Behaviour Support Plans (lodged within 6 months)</span></li>
                    <li><span class="check-mark"><i data-lucide="check"></i></span> <span>Restrictive Practice Elimination &amp; Human Rights Reviews</span></li>
                  </ul>
                </div>
                <div class="clinical-col">
                  <h4>🌱 Evidence-Based Interventions</h4>
                  <ul class="clinical-list">
                    <li><span class="check-mark"><i data-lucide="check"></i></span> <span>Proactive Environmental Modifications &amp; Calming Spaces</span></li>
                    <li><span class="check-mark"><i data-lucide="check"></i></span> <span>Coaching &amp; Upskilling for Support Workers, Carers, and Educators</span></li>
                    <li><span class="check-mark"><i data-lucide="check"></i></span> <span>Emotional Regulation, Coping Tools &amp; Sensory Strategies</span></li>
                    <li><span class="check-mark"><i data-lucide="check"></i></span> <span>Safe Crisis De-escalation Plans (Non-aversive Techniques)</span></li>
                  </ul>
                </div>
              </div>

              <!-- Action Bar -->
              <div class="service-action-bar">
                <div class="service-action-buttons">
                  <a class="btn btn-copper btn-small" href="contact.php?service=behaviour-support&amp;type=referral">Refer for Behaviour Support <i data-lucide="arrow-up-right"></i></a>
                  <a class="btn btn-outline btn-small" href="contact.php?service=behaviour-support">General Enquiry <i data-lucide="arrow-up-right"></i></a>
                </div>
                <div class="turnaround-pill">
                  <span class="badge-dot" aria-hidden="true"></span> NDIS Line Items: 11_022_0110_7_3 &amp; 11_023_0110_7_3
                </div>
              </div>
            </div>

            <!-- Artwork Side -->
            <div class="service-art-wrapper">
              <picture>
                <source srcset="assets/behaviour-support-art.webp" type="image/webp">
                <img src="assets/behaviour-support-art.png" alt="RightFit Behaviour Support blossoming mind and ladder illustration" class="service-art-image" width="280" height="280" loading="lazy">
              </picture>
              <div class="service-art-caption">02 / Positive Support &amp; Wellbeing</div>
            </div>
          </div>
        </article>

        <!-- 03 Occupational Therapy -->
        <article class="service-showcase-card" id="occupational-therapy">
          <div class="service-showcase-grid">
            <div class="service-content">
              <div class="service-badge-row">
                <span class="clinical-badge badge-ndis">NDIS Category 15: Improved Daily Living</span>
                <span class="clinical-badge badge-ahpra">AHPRA &amp; OTA Registered</span>
                <span class="clinical-badge badge-delivery">In-Home, School &amp; Workplace</span>
              </div>
              <div class="eyebrow">ALLIED HEALTH DISCIPLINE 03</div>
              <h2>Occupational Therapy</h2>
              <div class="service-tagline">Building Everyday Capacity, Autonomy &amp; Practical Living Skills</div>
              <p class="service-description">
                Our Occupational Therapists enable participants to engage fully in meaningful daily routines — from meal preparation, personal care, and domestic life to school, vocational, and community activities. We tailor strategies to your unique sensory and physical profile.
              </p>

              <!-- Two Column Clinical Breakdown -->
              <div class="clinical-columns">
                <div class="clinical-col">
                  <h4>📋 Key Assessments &amp; Reports</h4>
                  <ul class="clinical-list">
                    <li><span class="check-mark"><i data-lucide="check"></i></span> <span>Comprehensive Functional Capacity Assessments (FCA)</span></li>
                    <li><span class="check-mark"><i data-lucide="check"></i></span> <span>SIL &amp; SDA Housing Eligibility Assessment Reports</span></li>
                    <li><span class="check-mark"><i data-lucide="check"></i></span> <span>Complex &amp; Minor Home Modification (CHM/MHM) Prescriptions</span></li>
                    <li><span class="check-mark"><i data-lucide="check"></i></span> <span>Assistive Technology (AT) Assessment &amp; Trial Reports</span></li>
                  </ul>
                </div>
                <div class="clinical-col">
                  <h4>🙌 Direct Therapy &amp; Training</h4>
                  <ul class="clinical-list">
                    <li><span class="check-mark"><i data-lucide="check"></i></span> <span>Activities of Daily Living (ADL) Training (cooking, hygiene)</span></li>
                    <li><span class="check-mark"><i data-lucide="check"></i></span> <span>Sensory Profiling &amp; Neuroaffirming Regulation Plans</span></li>
                    <li><span class="check-mark"><i data-lucide="check"></i></span> <span>Fine Motor, Handwriting &amp; Executive Function Development</span></li>
                    <li><span class="check-mark"><i data-lucide="check"></i></span> <span>Ergonomic Seating, Pressure Care &amp; Transfer Strategies</span></li>
                  </ul>
                </div>
              </div>

              <!-- Action Bar -->
              <div class="service-action-bar">
                <div class="service-action-buttons">
                  <a class="btn btn-copper btn-small" href="contact.php?service=occupational-therapy&amp;type=referral">Refer for Occupational Therapy <i data-lucide="arrow-up-right"></i></a>
                  <a class="btn btn-outline btn-small" href="contact.php?service=occupational-therapy">General Enquiry <i data-lucide="arrow-up-right"></i></a>
                </div>
                <div class="turnaround-pill">
                  <span class="badge-dot" aria-hidden="true"></span> Prompt Intake: Capacity for FCA reports &amp; ongoing therapy
                </div>
              </div>
            </div>

            <!-- Artwork Side -->
            <div class="service-art-wrapper">
              <picture>
                <source srcset="assets/occupational-therapy-art.webp" type="image/webp">
                <img src="assets/occupational-therapy-art.png" alt="RightFit Occupational Therapy everyday independence celebratory figures illustration" class="service-art-image" width="280" height="280" loading="lazy">
              </picture>
              <div class="service-art-caption">03 / Everyday Independence &amp; Participation</div>
            </div>
          </div>
        </article>

        <!-- 04 Speech Pathology -->
        <article class="service-showcase-card" id="speech-pathology">
          <div class="service-showcase-grid reverse">
            <div class="service-content">
              <div class="service-badge-row">
                <span class="clinical-badge badge-ndis">NDIS Category 15: Improved Daily Living</span>
                <span class="clinical-badge badge-ahpra">Speech Pathology Australia CPSP</span>
                <span class="clinical-badge badge-delivery">Clinic, School &amp; Telehealth</span>
              </div>
              <div class="eyebrow">ALLIED HEALTH DISCIPLINE 04</div>
              <h2>Speech Pathology</h2>
              <div class="service-tagline">Communication, Connection, Swallowing &amp; Mealtime Safety</div>
              <p class="service-description">
                Communication and safe mealtimes are fundamental human rights. Our Certified Practising Speech Pathologists (CPSP) assess and treat speech, language, voice, fluency, and swallowing disorders (dysphagia) across children, adolescents, and adults.
              </p>

              <!-- Two Column Clinical Breakdown -->
              <div class="clinical-columns">
                <div class="clinical-col">
                  <h4>📋 Key Assessments &amp; Reports</h4>
                  <ul class="clinical-list">
                    <li><span class="check-mark"><i data-lucide="check"></i></span> <span>Comprehensive Speech Sound &amp; Language Assessments</span></li>
                    <li><span class="check-mark"><i data-lucide="check"></i></span> <span>Clinical Dysphagia &amp; Safe Swallowing Assessments</span></li>
                    <li><span class="check-mark"><i data-lucide="check"></i></span> <span>NDIS Mealtime Management Plans (MMPs) &amp; Protocols</span></li>
                    <li><span class="check-mark"><i data-lucide="check"></i></span> <span>High &amp; Low-Tech AAC Device Evaluations &amp; Trials</span></li>
                  </ul>
                </div>
                <div class="clinical-col">
                  <h4>💬 Direct Therapy &amp; Training</h4>
                  <ul class="clinical-list">
                    <li><span class="check-mark"><i data-lucide="check"></i></span> <span>AAC Setup &amp; Training (Proloquo2Go, TouchChat, Eye Gaze)</span></li>
                    <li><span class="check-mark"><i data-lucide="check"></i></span> <span>Receptive &amp; Expressive Language Development Interventions</span></li>
                    <li><span class="check-mark"><i data-lucide="check"></i></span> <span>Social Communication, Self-Advocacy &amp; Pragmatics</span></li>
                    <li><span class="check-mark"><i data-lucide="check"></i></span> <span>Mealtime Texture Modification &amp; Safe Feeding Coaching</span></li>
                  </ul>
                </div>
              </div>

              <!-- Action Bar -->
              <div class="service-action-bar">
                <div class="service-action-buttons">
                  <a class="btn btn-copper btn-small" href="contact.php?service=speech-pathology&amp;type=referral">Refer for Speech Pathology <i data-lucide="arrow-up-right"></i></a>
                  <a class="btn btn-outline btn-small" href="contact.php?service=speech-pathology">General Enquiry <i data-lucide="arrow-up-right"></i></a>
                </div>
                <div class="turnaround-pill">
                  <span class="badge-dot" aria-hidden="true"></span> Rapid Turnaround: Mealtime Management Plans &amp; AAC Trials
                </div>
              </div>
            </div>

            <!-- Artwork Side -->
            <div class="service-art-wrapper">
              <picture>
                <source srcset="assets/speech-pathology-art.webp" type="image/webp">
                <img src="assets/speech-pathology-art.png" alt="RightFit Speech Pathology communication bubbles illustration" class="service-art-image" width="280" height="280" loading="lazy">
              </picture>
              <div class="service-art-caption">04 / Communication &amp; Connection</div>
            </div>
          </div>
        </article>
      </div>
    </section>

    <!-- Funding & Payments Section -->
    <section class="funding-section" id="funding">
      <div class="container">
        <div class="section-heading-row">
          <div>
            <div class="eyebrow">AUSTRALIAN FUNDING OPTIONS</div>
            <h2 class="section-title">Transparent pricing &amp;<br><em>how to fund your care.</em></h2>
          </div>
          <p>We work seamlessly with Australian healthcare funding models with zero hidden out-of-pocket surprise fees.</p>
        </div>

        <div class="funding-grid">
          <div class="funding-card">
            <span class="funding-badge">NDIS Funding</span>
            <h3>Plan-Managed &amp; Self-Managed</h3>
            <p>
              We bill strictly in accordance with current NDIS Price Limits. Therapy is covered under <strong>Capacity Building – Improved Daily Living</strong> (Category 15) and <strong>Improved Relationships</strong> (Category 11). Travel is claimed transparently per NDIS MMM guidelines.
            </p>
          </div>

          <div class="funding-card">
            <span class="funding-badge">Medicare Australia</span>
            <h3>Chronic Disease Management (CDM/EPC)</h3>
            <p>
              Eligible patients referred by an Australian General Practitioner under a GP Management Plan (GPMP / Team Care Arrangement) can receive Medicare rebates for up to 5 allied health therapy visits per calendar year.
            </p>
          </div>

          <div class="funding-card">
            <span class="funding-badge">Private &amp; Self-Funded</span>
            <h3>Private Health Insurance &amp; Direct</h3>
            <p>
              No doctor's referral is required for private sessions. You can claim through your Australian Private Health Insurance extras cover with our itemised invoices, or pay directly via card or bank transfer.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Clinical Governance & Delivery Strip -->
    <section class="trust-strip" aria-label="Clinical Governance Overview">
      <div class="container trust-grid">
        <div class="trust-item">
          <div class="trust-icon" aria-hidden="true"><i data-lucide="shield-check"></i></div>
          <div>
            <h4>AHPRA &amp; SPA Registered</h4>
            <p>Adhering to National Registration standards</p>
          </div>
        </div>
        <div class="trust-item">
          <div class="trust-icon" aria-hidden="true"><i data-lucide="file-check-2"></i></div>
          <div>
            <h4>NDIS Commission Aligned</h4>
            <p>Strict worker screening &amp; safeguarding</p>
          </div>
        </div>
        <div class="trust-item">
          <div class="trust-icon" aria-hidden="true"><i data-lucide="car"></i></div>
          <div>
            <h4>Mobile Across Australia</h4>
            <p>Therapy where you live, learn, and play</p>
          </div>
        </div>
        <div class="trust-item">
          <div class="trust-icon" aria-hidden="true"><i data-lucide="video"></i></div>
          <div>
            <h4>Nationwide Telehealth</h4>
            <p>Access our experienced clinicians anywhere</p>
          </div>
        </div>
      </div>
    </section>

    <!-- FAQ Accordion -->
    <section class="faq-section" aria-labelledby="services-faq-title">
      <div class="container">
        <div class="faq-header">
          <div class="eyebrow">SERVICES &amp; CLINICAL INTAKE FAQ</div>
          <h2 class="section-title" id="services-faq-title">Frequently asked <em>questions.</em></h2>
          <p class="supporting-copy" style="margin: 0 auto;">Everything you need to know about referrals, assessments, and therapy delivery with RightFit.</p>
        </div>

        <div class="faq-list">
          <details class="faq-item">
            <summary class="faq-question">
              <span>What NDIS line items does RightFit bill under?</span>
              <span class="faq-icon" aria-hidden="true">+</span>
            </summary>
            <div class="faq-answer">
              <p>For Physiotherapy, Occupational Therapy, and Speech Pathology, we bill under <strong>Capacity Building – Improved Daily Living</strong> (line item <code>15_056_0128_1_3</code> or <code>15_048_0128_1_3</code>). For Positive Behaviour Support, we bill under <strong>Capacity Building – Improved Relationships</strong> (line item <code>11_022_0110_7_3</code> Specialist Behavioural Intervention Support and <code>11_023_0110_7_3</code> Behaviour Management Plan). We always operate strictly within standard NDIS Price Limits.</p>
            </div>
          </details>

          <details class="faq-item">
            <summary class="faq-question">
              <span>How does clinician travel work under the NDIS?</span>
              <span class="faq-icon" aria-hidden="true">+</span>
            </summary>
            <div class="faq-answer">
              <p>When our clinicians visit your home, school, or community setting, travel is billed in accordance with the NDIS Pricing Arrangements (up to 30 minutes in metropolitan areas MMM1–3, or up to 60 minutes in regional areas MMM4–5). We cluster appointments geographically whenever possible to keep travel costs as low as possible for participants.</p>
            </div>
          </details>

          <details class="faq-item">
            <summary class="faq-question">
              <span>Can I refer for multiple services at the same time?</span>
              <span class="faq-icon" aria-hidden="true">+</span>
            </summary>
            <div class="faq-answer">
              <p>Yes. Many of our participants receive multi-disciplinary care (e.g. Occupational Therapy and Speech Pathology, or Physiotherapy and Behaviour Support). On our online referral form, simply check all the services you are interested in, and our clinical intake coordinator will coordinate a cohesive service agreement.</p>
            </div>
          </details>

          <details class="faq-item">
            <summary class="faq-question">
              <span>What is a Functional Capacity Assessment (FCA) and why is it important?</span>
              <span class="faq-icon" aria-hidden="true">+</span>
            </summary>
            <div class="faq-answer">
              <p>A Functional Capacity Assessment is a comprehensive clinical evaluation conducted by an Occupational Therapist. It evaluates how your disability impacts your ability to perform daily living tasks, self-care, social participation, and community access. It is considered the gold-standard evidence for NDIS plan reviews to justify funding for support workers, therapy budgets, and assistive technology.</p>
            </div>
          </details>

          <details class="faq-item">
            <summary class="faq-question">
              <span>How long does it take for a Behaviour Support Plan (BSP) to be lodged?</span>
              <span class="faq-icon" aria-hidden="true">+</span>
            </summary>
            <div class="faq-answer">
              <p>If regulated restrictive practices are identified, our practitioner develops and lodges an <strong>Interim Behaviour Support Plan</strong> with the NDIS Quality and Safeguards Commission within 1 month. A <strong>Comprehensive Behaviour Support Plan</strong>, based on a full Functional Behaviour Assessment (FBA), is completed and lodged within 6 months.</p>
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
            <div class="eyebrow">READY TO GET STARTED?</div>
            <h2>Let's connect you with the right clinician.</h2>
            <p>Our clinical intake coordinators are ready to match your goals with our qualified therapists across Australia.</p>
            <div class="cta-buttons">
              <a class="btn btn-copper" href="contact.php?type=referral">Submit a Client Referral <i data-lucide="arrow-up-right"></i></a>
              <a class="btn btn-dark" href="contact.php">General Enquiry <i data-lucide="arrow-up-right"></i></a>
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
