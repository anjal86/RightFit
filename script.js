/**
 * RightFit Allied Health Services — Interactive Functionality
 * Provides accessible navigation, URL parameter prefilling, intake/referral tab management,
 * and resilient form handling with clipboard/download fallback.
 */

(function () {
  'use strict';

  // 0. Initial Website Loader (Plays Full Animation Once Per Visit)
  const loader = document.getElementById('site-loader');
  if (loader) {
    const pageKey = 'rf_loader_seen_' + (window.location.pathname.replace(/\/$/, '') || 'index');
    const urlParams = new URLSearchParams(window.location.search);
    const forceReplay = urlParams.has('replay') || urlParams.has('loader');
    const alreadySeen = sessionStorage.getItem(pageKey) === '1';

    if (alreadySeen && !forceReplay) {
      // Already played in full during this visit — dismiss immediately
      loader.remove();
    } else {
      // Plays the full animation cycle once (2250ms = 125 frames at 18ms/frame)
      const fullAnimationDuration = 2250;
      const startTime = Date.now();

      const dismissLoader = () => {
        const elapsed = Date.now() - startTime;
        const remaining = Math.max(0, fullAnimationDuration - elapsed);

        setTimeout(() => {
          loader.classList.add('loaded');
          try {
            sessionStorage.setItem(pageKey, '1');
          } catch (_) {}
          setTimeout(() => {
            if (loader.parentNode) loader.remove();
          }, 550);
        }, remaining);
      };

      if (document.readyState === 'complete') {
        dismissLoader();
      } else {
        window.addEventListener('load', dismissLoader);
        // Safety failsafe timeout in case of network stall
        setTimeout(dismissLoader, 3000);
      }
    }
  }

  // 1. Dynamic Copyright Year
  const yearEl = document.getElementById('year');
  if (yearEl) {
    yearEl.textContent = String(new Date().getFullYear());
  }

  // 2. Sticky Header Elevation on Scroll
  const header = document.querySelector('.site-header');
  if (header) {
    const handleScroll = () => {
      if (window.scrollY > 20) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
    };
    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll();
  }

  // 3. Accessible Mobile Navigation
  const menuBtn = document.getElementById('menu-button');
  const mobileNav = document.getElementById('mobile-nav');

  if (menuBtn && mobileNav) {
    const closeMenu = () => {
      mobileNav.hidden = true;
      menuBtn.setAttribute('aria-expanded', 'false');
      menuBtn.setAttribute('aria-label', 'Open navigation');
    };

    const openMenu = () => {
      mobileNav.hidden = false;
      menuBtn.setAttribute('aria-expanded', 'true');
      menuBtn.setAttribute('aria-label', 'Close navigation');
    };

    menuBtn.addEventListener('click', () => {
      const isExpanded = menuBtn.getAttribute('aria-expanded') === 'true';
      if (isExpanded) {
        closeMenu();
      } else {
        openMenu();
      }
    });

    // Close on Escape
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && !mobileNav.hidden) {
        closeMenu();
        menuBtn.focus();
      }
    });

    // Close on clicking outside
    document.addEventListener('click', (e) => {
      if (!mobileNav.hidden && !mobileNav.contains(e.target) && !menuBtn.contains(e.target)) {
        closeMenu();
      }
    });

    // Close when resized to desktop breakpoint
    window.addEventListener('resize', () => {
      if (window.innerWidth > 860 && !mobileNav.hidden) {
        closeMenu();
      }
    });
  }

  // 4. Contact & Referral Form Management
  const tabEnquiry = document.getElementById('tab-enquiry');
  const tabReferral = document.getElementById('tab-referral');
  const enquirySection = document.getElementById('enquiry-form-section');
  const referralSection = document.getElementById('referral-form-section');

  function switchTab(target) {
    if (!tabEnquiry || !tabReferral || !enquirySection || !referralSection) return;

    if (target === 'referral') {
      tabReferral.classList.add('active');
      tabReferral.setAttribute('aria-selected', 'true');
      tabEnquiry.classList.remove('active');
      tabEnquiry.setAttribute('aria-selected', 'false');
      referralSection.hidden = false;
      enquirySection.hidden = true;
    } else {
      tabEnquiry.classList.add('active');
      tabEnquiry.setAttribute('aria-selected', 'true');
      tabReferral.classList.remove('active');
      tabReferral.setAttribute('aria-selected', 'false');
      enquirySection.hidden = false;
      referralSection.hidden = true;
    }
  }

  if (tabEnquiry && tabReferral) {
    tabEnquiry.addEventListener('click', () => switchTab('enquiry'));
    tabReferral.addEventListener('click', () => switchTab('referral'));
  }

  // 5. Pre-select Service & Form Type from URL query params
  const urlParams = new URLSearchParams(window.location.search);
  const serviceParam = urlParams.get('service');
  const typeParam = urlParams.get('type');

  if (typeParam === 'referral') {
    switchTab('referral');
  }

  // Service Mapping
  const serviceMap = {
    'physiotherapy': 'Physiotherapy',
    'behaviour-support': 'Behaviour Support (BSP)',
    'occupational-therapy': 'Occupational Therapy',
    'speech-pathology': 'Speech Pathology'
  };

  const serviceSelect = document.getElementById('service');
  if (serviceSelect && serviceParam && serviceMap[serviceParam]) {
    serviceSelect.value = serviceMap[serviceParam];
  }

  // Also check checkboxes on referral form if prefilled
  if (serviceParam) {
    const chk = document.getElementById(`ref-${serviceParam}`);
    if (chk) chk.checked = true;
  }

  // 6. Generic Form Submission & Clipboard/Download Fallback
  function setupFormHandler(formId, feedbackId, getDraftTextFn, defaultSubject) {
    const form = document.getElementById(formId);
    const feedback = document.getElementById(feedbackId);
    if (!form || !feedback) return;

    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      // Check anti-spam honeypot
      const trap = form.querySelector('.website-trap');
      if (trap && trap.value.trim() !== '') {
        // Silently discard bot submission
        feedback.className = 'form-feedback success';
        feedback.textContent = 'Thank you. Your message has been received.';
        form.reset();
        return;
      }

      if (!form.reportValidity()) return;

      const formData = new FormData(form);
      const draftText = getDraftTextFn(formData);

      feedback.className = 'form-feedback';

      // Check if form has a valid remote endpoint (e.g., https://formspree.io/... or https://...)
      const action = form.getAttribute('action');
      const isRemoteEndpoint = action && action.startsWith('http') && !action.includes('example.com');

      if (isRemoteEndpoint) {
        feedback.textContent = 'Submitting your information securely...';
        feedback.style.display = 'block';

        try {
          const res = await fetch(action, {
            method: 'POST',
            body: formData,
            headers: { 'Accept': 'application/json' }
          });

          if (res.ok) {
            feedback.className = 'form-feedback success';
            feedback.innerHTML = '<strong>Thank you!</strong> Your submission has been received. Our clinical intake team will be in touch shortly.';
            form.reset();
            return;
          } else {
            throw new Error('Server returned error status');
          }
        } catch (err) {
          console.warn('Endpoint submission failed or unconfigured, offering direct email/copy fallback.', err);
        }
      }

      // Concept / Local Fallback handling:
      // Try copying to clipboard and generate a direct mailto link
      let copied = false;
      if (navigator.clipboard && window.isSecureContext) {
        try {
          await navigator.clipboard.writeText(draftText);
          copied = true;
        } catch (_) {}
      }

      const mailtoUrl = `mailto:enquiries@rightfitalliedhealth.com.au?subject=${encodeURIComponent(defaultSubject)}&body=${encodeURIComponent(draftText)}`;

      feedback.className = 'form-feedback success';
      feedback.innerHTML = `
        <strong>Your enquiry draft is ready!</strong><br>
        ${copied ? 'Details have been copied to your clipboard. ' : ''}
        You can send this directly via email or download a backup copy below:
        <div class="fallback-btn-group">
          <a class="btn btn-small btn-dark" href="${mailtoUrl}">Open in Email Client ↗</a>
          <button type="button" class="btn btn-small btn-light" id="${formId}-download-btn">Download Text (.txt)</button>
        </div>
      `;

      const downloadBtn = document.getElementById(`${formId}-download-btn`);
      if (downloadBtn) {
        downloadBtn.addEventListener('click', () => {
          const blob = new Blob([draftText], { type: 'text/plain;charset=utf-8' });
          const url = URL.createObjectURL(blob);
          const a = document.createElement('a');
          a.href = url;
          a.download = `rightfit-${formId}-draft.txt`;
          document.body.appendChild(a);
          a.click();
          a.remove();
          setTimeout(() => URL.revokeObjectURL(url), 1000);
        });
      }
    });
  }

  // Configure General Enquiry Handler
  setupFormHandler(
    'enquiry-form',
    'enquiry-feedback',
    (data) => {
      return `RightFit Allied Health Services — General Enquiry\n` +
             `------------------------------------------------\n` +
             `Name: ${data.get('name') || ''}\n` +
             `Email: ${data.get('email') || ''}\n` +
             `Phone: ${data.get('phone') || 'Not provided'}\n` +
             `Service of Interest: ${data.get('service') || 'General'}\n\n` +
             `Message:\n${data.get('message') || ''}\n`;
    },
    'RightFit Allied Health — General Website Enquiry'
  );

  // Configure Intake Referral Form Handler
  setupFormHandler(
    'referral-form',
    'referral-feedback',
    (data) => {
      const services = data.getAll('services_requested').join(', ') || 'Not specified';
      return `RightFit Allied Health Services — Client Referral Form\n` +
             `======================================================\n\n` +
             `[1. REFERRER DETAILS]\n` +
             `Referrer Role: ${data.get('referrer_role') || 'Self'}\n` +
             `Referrer Name: ${data.get('referrer_name') || ''}\n` +
             `Organization / Clinic: ${data.get('referrer_org') || 'N/A'}\n` +
             `Email: ${data.get('referrer_email') || ''}\n` +
             `Phone: ${data.get('referrer_phone') || ''}\n\n` +
             `[2. PARTICIPANT DETAILS]\n` +
             `Participant Name: ${data.get('participant_name') || ''}\n` +
             `Date of Birth / Age: ${data.get('participant_dob') || 'Not specified'}\n` +
             `Suburb & Postcode: ${data.get('participant_suburb') || ''}\n` +
             `Funding Type: ${data.get('funding_type') || ''}\n` +
             `NDIS Number (if applicable): ${data.get('ndis_number') || 'N/A'}\n\n` +
             `[3. SERVICES & PREFERENCES]\n` +
             `Requested Services: ${services}\n` +
             `Delivery Preference: ${data.get('delivery_preference') || 'Any'}\n\n` +
             `[4. PRIMARY GOALS & CONTEXT]\n` +
             `${data.get('referral_notes') || 'No additional notes provided.'}\n\n` +
             `Participant Consent Confirmed: ${data.get('consent_confirmed') ? 'Yes' : 'No'}\n`;
    },
    'RightFit Allied Health — New Client Referral'
  );

  // 7. Interactive Details/Summary Accordion (Single-open option)
  const faqItems = document.querySelectorAll('.faq-item');
  if (faqItems.length > 0) {
    faqItems.forEach((item) => {
      item.addEventListener('toggle', () => {
        if (item.open) {
          faqItems.forEach((other) => {
            if (other !== item && other.open) {
              other.open = false;
            }
          });
        }
      });
    });
  }
})();
