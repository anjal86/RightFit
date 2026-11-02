<?php
/**
 * RightFit Allied Health Services — Site Footer Partial
 */
?>
  </main>

  <!-- Footer -->
  <footer class="footer">
    <div class="container footer-top">
      <div class="footer-intro">
        <span class="footer-leaf" aria-hidden="true">✳</span>
        <h2>Every step forward<br><em>matters.</em></h2>
        <p>Empowering Ability. Enabling Independence. Person-centred allied health care across Australia.</p>
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
          <a class="btn btn-copper btn-small" href="contact.php?type=referral">Make a Referral <i data-lucide="arrow-up-right"></i></a>
          <a class="btn btn-light btn-small" href="tel:1300000000"><i data-lucide="phone"></i> 1300 RIGHTFIT</a>
        </div>
      </div>

      <div class="footer-nav-wrap">
        <div class="footer-nav">
          <h3>Explore</h3>
          <a href="index.php">Home</a>
          <a href="about.php">About RightFit</a>
          <a href="services.php">Our services</a>
          <a href="contact.php">Contact &amp; Enquiries</a>
          <a href="contact.php?type=referral">Make a Referral</a>
        </div>
        <div class="footer-nav">
          <h3>Our Services</h3>
          <a href="services.php#physiotherapy">Physiotherapy</a>
          <a href="services.php#behaviour-support">Behaviour Support (BSP)</a>
          <a href="services.php#occupational-therapy">Occupational Therapy</a>
          <a href="services.php#speech-pathology">Speech Pathology</a>
        </div>
        <div class="footer-nav">
          <h3>Funding &amp; Standards</h3>
          <a href="services.php#funding">NDIS Pricing &amp; Plans</a>
          <a href="services.php#funding">Medicare &amp; Private</a>
          <a href="about.php#standards">Clinical Governance</a>
          <a href="about.php#privacy">Privacy &amp; Data Rights</a>
        </div>
      </div>
    </div>

    <div class="container footer-bottom">
      <span>© <span id="year"><?= date('Y') ?></span> RightFit Allied Health Services. All rights reserved.</span>
      <span class="preview-meta">AHPRA &amp; SPA Registered Clinicians · Australian Privacy Principles (APP) Compliant</span>
      <a href="#top">Back to top <i data-lucide="arrow-up" style="font-size: 11px;"></i></a>
    </div>
  </footer>

  <script src="assets/lucide.min.js"></script>
  <script>
    if (typeof lucide !== 'undefined') {
      lucide.createIcons();
    }
  </script>
  <script src="script.js" defer></script>
</body>
</html>

