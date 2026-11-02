<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

requireLogin();
$pdo = getDb();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// Handle Admin Notes & Status Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (verifyCsrfToken($token)) {
        $status = $_POST['status'] ?? 'new';
        $adminNotes = trim($_POST['admin_notes'] ?? '');

        $upd = $pdo->prepare("UPDATE submissions SET status = :s, admin_notes = :notes WHERE id = :id");
        $upd->execute([
            ':s' => $status,
            ':notes' => $adminNotes,
            ':id' => $id
        ]);
        setFlash('success', 'Submission updated successfully.');
        header('Location: view.php?id=' . $id);
        exit;
    }
}

$stmt = $pdo->prepare("SELECT * FROM submissions WHERE id = :id");
$stmt->execute([':id' => $id]);
$sub = $stmt->fetch();

if (!$sub) {
    setFlash('error', 'Submission record not found.');
    header('Location: index.php');
    exit;
}

$flash = getFlash();
?>
<!doctype html>
<html lang="en-AU">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Submission #<?= $sub['id'] ?> | RightFit Intake</title>
  <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="128x128" href="../assets/favicon.png">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../styles.css">
  <style>
    body { background: #f8f6f0; color: var(--ink); }
    .admin-navbar { background: #193d44; color: #fff; padding: 14px 0; }
    .admin-nav-inner { display: flex; align-items: center; justify-content: space-between; }
    .admin-brand img { height: 38px; width: auto; }
    .dossier-container { padding: 40px 0 80px; }
    .back-nav { margin-bottom: 24px; }
    .back-nav a { font-size: 13px; font-weight: 700; color: var(--forest); display: inline-flex; align-items: center; gap: 6px; }
    
    .dossier-grid {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 28px;
    }
    .dossier-card {
      background: #fff;
      border: 1px solid #e1e7e1;
      border-radius: 12px;
      padding: 36px 40px;
      box-shadow: 0 4px 20px rgba(25, 61, 68, 0.04);
      margin-bottom: 24px;
    }
    .dossier-card h2 {
      font-size: 18px;
      font-weight: 700;
      color: var(--forest);
      margin: 0 0 16px;
      padding-bottom: 10px;
      border-bottom: 1px dashed #e1e7e1;
    }
    .data-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 18px 24px;
    }
    .data-item {
      display: flex;
      flex-direction: column;
      gap: 3px;
    }
    .data-item span.label {
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      color: var(--muted);
      font-weight: 700;
    }
    .data-item span.value {
      font-size: 14px;
      color: var(--ink);
      font-weight: 500;
    }
    .message-box {
      background: #fbfaf6;
      border: 1px solid #e8ede6;
      border-radius: 8px;
      padding: 20px;
      font-size: 14px;
      line-height: 1.7;
      color: #3b5048;
      white-space: pre-wrap;
    }
    .badge {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 700;
    }
    .badge-referral { background: #fbeee7; color: #9a4823; border: 1px solid #f2d1c2; }
    .badge-enquiry { background: #edf4f1; color: #235848; border: 1px solid #d4e5de; }

    .sidebar-card {
      background: #fff;
      border: 1px solid #e1e7e1;
      border-radius: 12px;
      padding: 28px;
      box-shadow: 0 4px 20px rgba(25, 61, 68, 0.04);
      margin-bottom: 24px;
    }
    .sidebar-card h3 {
      font-size: 15px;
      font-weight: 700;
      margin: 0 0 16px;
      color: var(--ink);
    }
    .sidebar-card label {
      display: flex;
      flex-direction: column;
      gap: 6px;
      font-size: 13px;
      font-weight: 700;
      color: var(--forest);
      margin-bottom: 16px;
    }
    .sidebar-card select,
    .sidebar-card textarea {
      border: 1px solid #d4ded4;
      border-radius: 6px;
      padding: 10px 12px;
      font-size: 13px;
      width: 100%;
    }
    .sidebar-card textarea {
      resize: vertical;
      min-height: 120px;
    }
    @media (max-width: 900px) {
      .dossier-grid { grid-template-columns: 1fr; }
      .data-grid { grid-template-columns: 1fr; }
      .dossier-card { padding: 24px; }
    }
  </style>
</head>
<body>
  <header class="admin-navbar">
    <div class="container admin-nav-inner">
      <div style="display: flex; align-items: center; gap: 14px;">
        <a href="index.php"><img src="../assets/rightfit-logo-white-silhouette.png" alt="RightFit" style="height: 38px;"></a>
        <span style="font-weight: 700; font-size: 15px;">Intake Dossier</span>
      </div>
      <div>
        <a href="index.php" style="color: #cfded9; font-size: 13px; display: inline-flex; align-items: center; gap: 4px;"><i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i> Return to All Submissions</a>
      </div>
    </div>
  </header>

  <main class="dossier-container">
    <div class="container">
      <div class="back-nav">
        <a href="index.php" style="display: inline-flex; align-items: center; gap: 6px;"><i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i> Back to Submissions List</a>
      </div>

      <?php if ($flash): ?>
        <div class="flash-alert <?= e($flash['type']) ?>" style="padding: 12px 18px; border-radius: 6px; margin-bottom: 24px; background: #eaf6ee; color: #1d6e38;">
          <?= e($flash['message']) ?>
        </div>
      <?php endif; ?>

      <div class="dossier-grid">
        <!-- Main Content Area -->
        <div class="dossier-main">
          <!-- Overview Header -->
          <div class="dossier-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; margin-bottom: 20px;">
              <div>
                <span class="badge <?= $sub['type'] === 'referral' ? 'badge-referral' : 'badge-enquiry' ?>">
                  <?= $sub['type'] === 'referral' ? '<i data-lucide="star" style="width: 12px; height: 12px; vertical-align: -1px;"></i> Clinical Referral' : '<i data-lucide="mail" style="width: 12px; height: 12px; vertical-align: -1px;"></i> General Website Enquiry' ?>
                </span>
                <h1 style="font-size: 26px; font-weight: 700; margin: 12px 0 4px; color: var(--ink);">
                  <?= e($sub['name']) ?>
                </h1>
                <div style="font-size: 13px; color: var(--muted);">
                  Lodged on <?= date('l, d F Y \a\t g:i A', strtotime($sub['created_at'])) ?>
                </div>
              </div>
              <div style="display: flex; gap: 10px;">
                <a class="btn btn-small btn-copper" href="mailto:<?= e($sub['email']) ?>?subject=RightFit%20Allied%20Health%20Follow-up"><i data-lucide="mail"></i> Email</a>
                <?php if (!empty($sub['phone'])): ?>
                  <a class="btn btn-small btn-dark" href="tel:<?= e($sub['phone']) ?>"><i data-lucide="phone"></i> Call</a>
                <?php endif; ?>
              </div>
            </div>

            <?php if ($sub['type'] === 'referral'): ?>
              <!-- Participant Details -->
              <h2>1. Participant Information</h2>
              <div class="data-grid" style="margin-bottom: 28px;">
                <div class="data-item">
                  <span class="label">Participant Full Name</span>
                  <span class="value"><?= e($sub['participant_name']) ?></span>
                </div>
                <div class="data-item">
                  <span class="label">Date of Birth / Age</span>
                  <span class="value"><?= e($sub['participant_dob'] ?: 'Not specified') ?></span>
                </div>
                <div class="data-item">
                  <span class="label">Location / Suburb</span>
                  <span class="value"><?= e($sub['participant_suburb']) ?></span>
                </div>
                <div class="data-item">
                  <span class="label">NDIS Funding Type</span>
                  <span class="value" style="color: #8c5232; font-weight: 700;"><?= e($sub['funding_type']) ?></span>
                </div>
                <div class="data-item">
                  <span class="label">NDIS Participant Number</span>
                  <span class="value"><?= e($sub['ndis_number'] ?: 'Not provided / Pending') ?></span>
                </div>
                <div class="data-item">
                  <span class="label">Delivery Preference</span>
                  <span class="value"><?= e($sub['delivery_preference']) ?></span>
                </div>
              </div>

              <!-- Referrer Details -->
              <h2>2. Referrer Details</h2>
              <div class="data-grid" style="margin-bottom: 28px;">
                <div class="data-item">
                  <span class="label">Referring Role</span>
                  <span class="value"><?= e($sub['referrer_role']) ?></span>
                </div>
                <div class="data-item">
                  <span class="label">Referrer Name</span>
                  <span class="value"><?= e($sub['referrer_name']) ?></span>
                </div>
                <div class="data-item">
                  <span class="label">Organization / Agency</span>
                  <span class="value"><?= e($sub['referrer_org'] ?: 'N/A') ?></span>
                </div>
                <div class="data-item">
                  <span class="label">Referrer Contact</span>
                  <span class="value">
                    <a href="mailto:<?= e($sub['referrer_email']) ?>"><?= e($sub['referrer_email']) ?></a>
                    <?= !empty($sub['referrer_phone']) ? ' · ' . e($sub['referrer_phone']) : '' ?>
                  </span>
                </div>
              </div>

              <!-- Services & Consent -->
              <h2>3. Services &amp; Primary Goals</h2>
              <div style="margin-bottom: 16px;">
                <span class="label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: var(--muted); font-weight: 700;">Services Requested</span>
                <div style="font-size: 15px; font-weight: 700; color: var(--forest); margin-top: 4px;">
                  <?= e($sub['services_requested'] ?: $sub['service']) ?>
                </div>
              </div>

              <div style="margin-bottom: 24px;">
                <span class="label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: var(--muted); font-weight: 700;">Reason for Referral &amp; Clinical Goals</span>
                <div class="message-box" style="margin-top: 8px;">
                  <?= e($sub['message'] ?: 'No notes provided.') ?>
                </div>
              </div>

              <div style="font-size: 12px; color: var(--forest); background: #f0f7f2; padding: 12px 16px; border-radius: 6px; border: 1px solid #d2e7d7;">
                ✓ <strong>Participant Consent Confirmed:</strong> The referrer affirmed that the participant or authorized decision-maker consented to this referral.
              </div>

            <?php else: ?>
              <!-- General Enquiry View -->
              <h2>General Enquiry Details</h2>
              <div class="data-grid" style="margin-bottom: 28px;">
                <div class="data-item">
                  <span class="label">Enquirer Name</span>
                  <span class="value"><?= e($sub['name']) ?></span>
                </div>
                <div class="data-item">
                  <span class="label">Email Address</span>
                  <span class="value"><a href="mailto:<?= e($sub['email']) ?>"><?= e($sub['email']) ?></a></span>
                </div>
                <div class="data-item">
                  <span class="label">Phone Number</span>
                  <span class="value"><?= e($sub['phone'] ?: 'Not provided') ?></span>
                </div>
                <div class="data-item">
                  <span class="label">Discipline of Interest</span>
                  <span class="value"><?= e($sub['service']) ?></span>
                </div>
              </div>

              <h2>Message</h2>
              <div class="message-box">
                <?= e($sub['message']) ?>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Sidebar Actions & Clinical Notes -->
        <div class="dossier-sidebar">
          <div class="sidebar-card">
            <h3>Update Status &amp; Notes</h3>
            <form method="POST" action="view.php?id=<?= $sub['id'] ?>">
              <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

              <label for="status">
                Intake Status
                <select name="status" id="status">
                  <option value="new" <?= $sub['status'] === 'new' ? 'selected' : '' ?>>New / Unreviewed</option>
                  <option value="in_progress" <?= $sub['status'] === 'in_progress' ? 'selected' : '' ?>>In Progress / Contacted</option>
                  <option value="completed" <?= $sub['status'] === 'completed' ? 'selected' : '' ?>>Completed / Onboarded</option>
                  <option value="archived" <?= $sub['status'] === 'archived' ? 'selected' : '' ?>>Archived</option>
                </select>
              </label>

              <label for="admin_notes">
                Internal Clinical Notes
                <textarea name="admin_notes" id="admin_notes" placeholder="e.g. Spoke to Support Coordinator on 3 Nov. Service Agreement dispatched. Allocated to OT team."><?= e($sub['admin_notes'] ?? '') ?></textarea>
              </label>

              <button type="submit" class="btn btn-dark" style="width: 100%;">Save Changes</button>
            </form>
          </div>

          <div class="sidebar-card" style="font-size: 12px; color: var(--muted); line-height: 1.6;">
            <h3>Audit Information</h3>
            <div><strong>IP Address:</strong> <?= e($sub['ip_address'] ?? 'N/A') ?></div>
            <div style="margin-top: 6px;"><strong>User Agent:</strong> <?= e($sub['user_agent'] ?? 'N/A') ?></div>
            <div style="margin-top: 6px;"><strong>Record ID:</strong> #<?= $sub['id'] ?></div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script src="../assets/lucide.min.js"></script>
  <script>
    if (typeof lucide !== 'undefined') {
      lucide.createIcons();
    }
  </script>
</body>
</html>

