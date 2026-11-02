<?php
$pageTitle = "Contact & Participant Referrals | RightFit Allied Health Services";
$pageDescription = "Contact RightFit Allied Health Services or submit a client referral. We welcome Support Coordinators, Plan Managers, GPs, and Participants for Physio, BSP, OT, and Speech Pathology.";
$currentPage = "contact";
$showLoader = false;
require_once __DIR__ . "/includes/header.php";
?>

<?php $flash = getFlash(); if ($flash): ?>
    <div class="container" style="padding-top: 24px;">
      <div class="form-feedback <?= $flash['type'] === 'success' ? 'success' : 'error' ?>" style="display: block; font-weight: 500;">
        <?= e($flash['message']) ?>
      </div>
    </div>
    <?php endif; ?>
<!-- Page Hero -->
    <section class="page-hero contact-hero" aria-labelledby="contact-heading">
      <div class="container page-hero-grid">
        <div>
          <div class="eyebrow">CONTACT &amp; INTAKE REFERRALS</div>
          <h1 id="contact-heading">Let's talk about<br><em>what matters.</em></h1>
          <p>
            Whether you are enquiring for yourself, referring a client as a Support Coordinator, or seeking care for a loved one, we are here to support your journey.
          </p>
        </div>
        <div class="page-hero-stamp">
          <picture>
            <source srcset="assets/speech-pathology-art.webp" type="image/webp">
            <img src="assets/speech-pathology-art.png" alt="RightFit communication and connection illustration" width="220" height="220">
          </picture>
        </div>
      </div>
    </section>

    <!-- Contact & Referral Form Section -->
    <section class="section contact-section">
      <div class="container contact-grid">
        <!-- Contact Info Side -->
        <div class="contact-info">
          <div class="eyebrow">DIRECT CONTACT</div>
          <h2 class="section-title">The first step is <em>reaching out.</em></h2>
          <p>
            Our clinical intake team is ready to answer questions about therapy modalities, clinician capacity, service agreements, and NDIS price guides.
          </p>

          <div class="info-row">
            <span class="info-symbol" aria-hidden="true">📞</span>
            <div>
              <h3>Telephone</h3>
              <p><a href="tel:1300000000">1300 RIGHTFIT (1300 000 000)</a></p>
              <p style="font-size: 12px; color: var(--muted); margin-top: 4px;">Monday – Friday, 8:30am – 5:00pm AEST</p>
            </div>
          </div>

          <div class="info-row">
            <span class="info-symbol" aria-hidden="true">✉️</span>
            <div>
              <h3>Email Enquiries</h3>
              <p><a href="mailto:enquiries@rightfitalliedhealth.com.au">enquiries@rightfitalliedhealth.com.au</a></p>
              <p style="font-size: 12px; color: var(--muted); margin-top: 4px;">Direct intake: <a href="mailto:referrals@rightfitalliedhealth.com.au">referrals@rightfitalliedhealth.com.au</a></p>
            </div>
          </div>

          <div class="info-row">
            <span class="info-symbol" aria-hidden="true">📍</span>
            <div>
              <h3>Service Locations</h3>
              <p>Mobile in-home, school &amp; community visits across regional &amp; metropolitan areas.</p>
              <p style="font-size: 12px; color: var(--muted); margin-top: 4px;">Clinic consultations &amp; Australia-wide Telehealth.</p>
            </div>
          </div>

          <div class="info-row">
            <span class="info-symbol" aria-hidden="true">⚡</span>
            <div>
              <h3>Intake Turnaround</h3>
              <p>Referrals are reviewed and acknowledged within 1–2 business days by our intake team.</p>
            </div>
          </div>

          <div class="contact-privacy">
            <strong>🔒 Australian Privacy Principles (APP) Commitment</strong>
            <p>
              We treat all health data in strict accordance with the Privacy Act 1988. Information submitted via this portal is handled securely and solely for clinical intake and service assessment.
            </p>
          </div>
        </div>

        <!-- Form Panel with Tabs -->
        <div class="form-panel" id="form-container">
          <div class="form-tabs" role="tablist" aria-label="Enquiry or Referral selection">
            <button class="form-tab-btn active" id="tab-enquiry" role="tab" aria-selected="true" aria-controls="enquiry-form-section">
              General Enquiry
            </button>
            <button class="form-tab-btn" id="tab-referral" role="tab" aria-selected="false" aria-controls="referral-form-section">
              Make a Referral (Intake)
            </button>
          </div>

          <!-- Tab 1: General Enquiry Form -->
          <div id="enquiry-form-section" role="tabpanel" aria-labelledby="tab-enquiry">
            <h2>General Enquiry</h2>
            <p class="form-intro">Have a question before getting started? Send us a quick note and our team will get right back to you.</p>

            <form id="enquiry-form" method="POST" action="submit.php">
              <!-- CSRF & Submission Identity -->
              <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
              <input type="hidden" name="form_type" value="enquiry">
              <!-- Spam Prevention Honeypot -->
              <input type="text" name="_website_trap" class="website-trap" tabindex="-1" autocomplete="off">

              <div class="form-row">
                <label for="name">
                  <span>Your Full Name <span class="req">*</span></span>
                  <input id="name" name="name" type="text" required maxlength="120" autocomplete="name" placeholder="First and last name">
                </label>
                <label for="email">
                  <span>Email Address <span class="req">*</span></span>
                  <input id="email" name="email" type="email" required maxlength="200" autocomplete="email" placeholder="you@example.com">
                </label>
              </div>

              <div class="form-row">
                <label for="phone">
                  <span>Phone Number</span>
                  <input id="phone" name="phone" type="tel" maxlength="40" autocomplete="tel" placeholder="0400 000 000">
                </label>
                <label for="service">
                  <span>Service of Interest</span>
                  <select name="service" id="service">
                    <option value="General enquiry">General enquiry / Not sure</option>
                    <option value="Physiotherapy">Physiotherapy</option>
                    <option value="Behaviour Support (BSP)">Behaviour Support (BSP)</option>
                    <option value="Occupational Therapy">Occupational Therapy</option>
                    <option value="Speech Pathology">Speech Pathology</option>
                  </select>
                </label>
              </div>

              <label for="message">
                <span>How can we help? <span class="req">*</span></span>
                <textarea id="message" name="message" rows="5" maxlength="1500" required placeholder="Please describe what support you are looking for..."></textarea>
              </label>

              <div class="form-actions">
                <button class="btn btn-copper" type="submit">Submit Enquiry ↗</button>
              </div>

              <div class="form-feedback" id="enquiry-feedback" role="status" aria-live="polite"></div>
            </form>
          </div>

          <!-- Tab 2: Client Referral Form (Intake) -->
          <div id="referral-form-section" role="tabpanel" aria-labelledby="tab-referral" hidden>
            <h2>Participant Referral</h2>
            <p class="form-intro">For Support Coordinators, Plan Managers, Health Professionals, and Self-Referring Participants.</p>

            <form id="referral-form" method="POST" action="submit.php">
              <!-- CSRF & Submission Identity -->
              <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
              <input type="hidden" name="form_type" value="referral">
              <!-- Spam Prevention Honeypot -->
              <input type="text" name="_website_trap" class="website-trap" tabindex="-1" autocomplete="off">

              <!-- Section 1: Referrer Details -->
              <div class="form-section-legend">1. Referrer Information</div>
              <div class="form-row">
                <label for="referrer-role">
                  <span>I am referring as: <span class="req">*</span></span>
                  <select id="referrer-role" name="referrer_role" required>
                    <option value="Support Coordinator">Support Coordinator</option>
                    <option value="Plan Manager">Plan Manager</option>
                    <option value="Participant (Self-referral)">Participant (Self-referral)</option>
                    <option value="Family Member / Nominee">Family Member / Nominee</option>
                    <option value="GP / Medical Professional">GP / Medical Professional</option>
                    <option value="Other">Other</option>
                  </select>
                </label>
                <label for="referrer-name">
                  <span>Referrer Name <span class="req">*</span></span>
                  <input id="referrer-name" name="referrer_name" type="text" required placeholder="Your full name">
                </label>
              </div>

              <div class="form-row">
                <label for="referrer-org">
                  <span>Organization / Agency (if applicable)</span>
                  <input id="referrer-org" name="referrer_org" type="text" placeholder="e.g. Acme Support Services">
                </label>
                <label for="referrer-email">
                  <span>Referrer Email <span class="req">*</span></span>
                  <input id="referrer-email" name="referrer_email" type="email" required placeholder="referrer@agency.com.au">
                </label>
              </div>

              <div class="form-row">
                <label for="referrer-phone">
                  <span>Referrer Contact Phone <span class="req">*</span></span>
                  <input id="referrer-phone" name="referrer_phone" type="tel" required placeholder="0400 000 000">
                </label>
              </div>

              <!-- Section 2: Participant Details -->
              <div class="form-section-legend">2. Participant Details</div>
              <div class="form-row">
                <label for="participant-name">
                  <span>Participant Name <span class="req">*</span></span>
                  <input id="participant-name" name="participant_name" type="text" required placeholder="Participant's full name">
                </label>
                <label for="participant-dob">
                  <span>Date of Birth or Age</span>
                  <input id="participant-dob" name="participant_dob" type="text" placeholder="e.g. 15/04/1995 or 28 years">
                </label>
              </div>

              <div class="form-row">
                <label for="participant-suburb">
                  <span>Participant Suburb &amp; Postcode <span class="req">*</span></span>
                  <input id="participant-suburb" name="participant_suburb" type="text" required placeholder="e.g. Parramatta, NSW 2150">
                </label>
                <label for="funding-type">
                  <span>Funding Type <span class="req">*</span></span>
                  <select id="funding-type" name="funding_type" required>
                    <option value="NDIS - Plan-Managed">NDIS - Plan-Managed</option>
                    <option value="NDIS - Self-Managed">NDIS - Self-Managed</option>
                    <option value="NDIS - NDIA Managed">NDIS - NDIA Managed</option>
                    <option value="Medicare CDM Plan">Medicare CDM / GP Plan</option>
                    <option value="Private / Self-Funded">Private / Self-Funded</option>
                    <option value="Home Care Package">Home Care Package</option>
                  </select>
                </label>
              </div>

              <div class="form-row">
                <label for="ndis-number">
                  <span>NDIS Number (if applicable)</span>
                  <input id="ndis-number" name="ndis_number" type="text" placeholder="9-digit NDIS number">
                </label>
                <label for="delivery-preference">
                  <span>Preferred Service Delivery</span>
                  <select id="delivery-preference" name="delivery_preference">
                    <option value="In-Home Mobile Visit">In-Home Mobile Visit</option>
                    <option value="School / Day Care Visit">School / Day Care Visit</option>
                    <option value="Clinic Session">Clinic Session</option>
                    <option value="Telehealth Video Consultation">Telehealth Video Consultation</option>
                    <option value="Flexible / No Preference">Flexible / No Preference</option>
                  </select>
                </label>
              </div>

              <!-- Section 3: Services Requested -->
              <div class="form-section-legend">3. Services Requested (Select All That Apply)</div>
              <div class="checkbox-group">
                <label class="checkbox-label" for="ref-physiotherapy">
                  <input type="checkbox" id="ref-physiotherapy" name="services_requested[]" value="Physiotherapy">
                  <span>Physiotherapy</span>
                </label>
                <label class="checkbox-label" for="ref-behaviour-support">
                  <input type="checkbox" id="ref-behaviour-support" name="services_requested[]" value="Behaviour Support (BSP)">
                  <span>Behaviour Support (BSP)</span>
                </label>
                <label class="checkbox-label" for="ref-occupational-therapy">
                  <input type="checkbox" id="ref-occupational-therapy" name="services_requested[]" value="Occupational Therapy">
                  <span>Occupational Therapy</span>
                </label>
                <label class="checkbox-label" for="ref-speech-pathology">
                  <input type="checkbox" id="ref-speech-pathology" name="services_requested[]" value="Speech Pathology">
                  <span>Speech Pathology</span>
                </label>
              </div>

              <!-- Section 4: Goals and Notes -->
              <div class="form-section-legend">4. Key Goals &amp; Clinical Information</div>
              <label for="referral-notes">
                <span>Reason for Referral &amp; Primary Goals</span>
                <textarea id="referral-notes" name="referral_notes" rows="4" maxlength="2000" placeholder="Please outline primary goals, diagnoses, known challenges, or key milestones to focus on..."></textarea>
              </label>

              <!-- Section 5: Consent Declaration -->
              <label class="checkbox-label" for="consent-confirmed" style="margin-top: 10px;">
                <input type="checkbox" id="consent-confirmed" name="consent_confirmed" required>
                <span><strong>Consent Confirmed <span class="req">*</span>:</strong> The participant or their legal decision-maker has given consent to make this referral and share information with RightFit.</span>
              </label>

              <div class="form-actions">
                <button class="btn btn-copper" type="submit">Submit Participant Referral ↗</button>
              </div>

              <div class="form-feedback" id="referral-feedback" role="status" aria-live="polite"></div>
            </form>
          </div>
        </div>
      </div>
    </section>

    <!-- Bottom Link -->
    <section class="contact-bottom">
      <div class="container contact-bottom-grid">
        <span>LOOKING TO LEARN MORE ABOUT OUR DISCIPLINES?</span>
        <a href="services.php">Explore all four allied health service areas <span aria-hidden="true">↗</span></a>
      </div>
    </section>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
