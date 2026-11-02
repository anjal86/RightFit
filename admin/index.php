<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

requireLogin();
$pdo = getDb();
$flash = getFlash();

// 1. Handle Quick Status Update or Delete via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (verifyCsrfToken($token)) {
        $action = $_POST['action'] ?? '';
        $id = (int)($_POST['id'] ?? 0);

        if ($action === 'update_status' && $id > 0) {
            $newStatus = $_POST['status'] ?? 'new';
            $allowed = ['new', 'in_progress', 'completed', 'archived'];
            if (in_array($newStatus, $allowed, true)) {
                $stmt = $pdo->prepare("UPDATE submissions SET status = :s WHERE id = :id");
                $stmt->execute([':s' => $newStatus, ':id' => $id]);
                setFlash('success', "Status updated to " . ucfirst(str_replace('_', ' ', $newStatus)) . ".");
            }
        } elseif ($action === 'delete' && $id > 0) {
            $stmt = $pdo->prepare("DELETE FROM submissions WHERE id = :id");
            $stmt->execute([':id' => $id]);
            setFlash('success', "Submission #$id deleted successfully.");
        }
    }
    header('Location: index.php' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : ''));
    exit;
}

// 2. Fetch Metrics
$totalCount = (int)$pdo->query("SELECT COUNT(*) FROM submissions")->fetchColumn();
$referralCount = (int)$pdo->query("SELECT COUNT(*) FROM submissions WHERE type = 'referral'")->fetchColumn();
$enquiryCount = (int)$pdo->query("SELECT COUNT(*) FROM submissions WHERE type = 'enquiry'")->fetchColumn();
$newCount = (int)$pdo->query("SELECT COUNT(*) FROM submissions WHERE status = 'new'")->fetchColumn();

// 3. Filters & Search
$filterType = $_GET['type'] ?? '';
$filterStatus = $_GET['status'] ?? '';
$search = trim($_GET['q'] ?? '');

$where = [];
$params = [];

if (!empty($filterType) && in_array($filterType, ['referral', 'enquiry'], true)) {
    $where[] = "type = :type";
    $params[':type'] = $filterType;
}

if (!empty($filterStatus) && in_array($filterStatus, ['new', 'in_progress', 'completed', 'archived'], true)) {
    $where[] = "status = :status";
    $params[':status'] = $filterStatus;
}

if (!empty($search)) {
    $where[] = "(name LIKE :q OR email LIKE :q OR phone LIKE :q OR participant_name LIKE :q OR participant_suburb LIKE :q OR funding_type LIKE :q)";
    $params[':q'] = "%$search%";
}

$whereSql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
$query = "SELECT * FROM submissions $whereSql ORDER BY id DESC LIMIT 200";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$submissions = $stmt->fetchAll();
?>
<!doctype html>
<html lang="en-AU">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Clinical Intake Dashboard | RightFit Allied Health</title>
  <link rel="icon" href="../assets/favicon.png">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../styles.css">
  <style>
    body {
      background: #f8f6f0;
      color: var(--ink);
    }
    .admin-navbar {
      background: #193d44;
      color: #fff;
      padding: 14px 0;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .admin-nav-inner {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 20px;
    }
    .admin-brand {
      display: flex;
      align-items: center;
      gap: 14px;
      font-weight: 700;
      font-size: 15px;
      color: #fff;
    }
    .admin-brand img {
      height: 38px;
      width: auto;
      filter: brightness(0) invert(1);
    }
    .admin-links {
      display: flex;
      align-items: center;
      gap: 20px;
      font-size: 13px;
    }
    .admin-links a {
      color: #cfded9;
      transition: color 0.2s;
    }
    .admin-links a:hover {
      color: #fff;
    }
    .admin-container {
      padding: 40px 0 80px;
    }
    /* Metrics Row */
    .metrics-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 20px;
      margin-bottom: 32px;
    }
    .metric-card {
      background: #fff;
      border: 1px solid #e1e7e1;
      border-radius: 10px;
      padding: 24px;
      box-shadow: 0 2px 10px rgba(25, 61, 68, 0.04);
      display: flex;
      flex-direction: column;
      gap: 6px;
    }
    .metric-card span.label {
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 0.12em;
      color: var(--muted);
      font-weight: 700;
    }
    .metric-card span.value {
      font-size: 32px;
      font-weight: 700;
      color: var(--ink);
      line-height: 1;
    }
    .metric-card.highlight {
      border-left: 4px solid var(--copper);
    }
    /* Filter Bar */
    .filter-card {
      background: #fff;
      border: 1px solid #e1e7e1;
      border-radius: 10px;
      padding: 20px 24px;
      margin-bottom: 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      flex-wrap: wrap;
    }
    .filter-form {
      display: flex;
      align-items: center;
      gap: 12px;
      flex-wrap: wrap;
      flex: 1;
    }
    .filter-form input,
    .filter-form select {
      border: 1px solid #d4ded4;
      border-radius: 6px;
      padding: 8px 12px;
      font-size: 13px;
      background: #fff;
      outline: none;
    }
    .filter-form input[type="text"] {
      min-width: 240px;
    }
    /* Table Styling */
    .table-card {
      background: #fff;
      border: 1px solid #e1e7e1;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(25, 61, 68, 0.05);
    }
    .table-responsive {
      width: 100%;
      overflow-x: auto;
    }
    table.data-table {
      width: 100%;
      border-collapse: collapse;
      text-align: left;
      font-size: 13px;
    }
    table.data-table th {
      background: #f5f8f5;
      padding: 14px 18px;
      font-weight: 700;
      color: var(--forest);
      border-bottom: 1px solid #e1e7e1;
      white-space: nowrap;
    }
    table.data-table td {
      padding: 14px 18px;
      border-bottom: 1px solid #edf2ed;
      vertical-align: middle;
    }
    table.data-table tr:hover {
      background: #fafbfa;
    }
    /* Badges */
    .badge {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 0.02em;
      white-space: nowrap;
    }
    .badge-referral { background: #fbeee7; color: #9a4823; border: 1px solid #f2d1c2; }
    .badge-enquiry { background: #edf4f1; color: #235848; border: 1px solid #d4e5de; }
    .badge-new { background: #eef7f0; color: #1e7039; }
    .badge-in_progress { background: #fdf6ea; color: #9c6c13; }
    .badge-completed { background: #eef3f7; color: #225f8a; }
    .badge-archived { background: #f1f3f2; color: #62746d; }
    /* Actions */
    .action-group {
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }
    .btn-action {
      background: transparent;
      border: 1px solid #d5dfd5;
      padding: 5px 10px;
      font-size: 11px;
      font-weight: 600;
      border-radius: 4px;
      color: var(--ink);
      text-decoration: none;
      cursor: pointer;
      transition: all 0.15s;
    }
    .btn-action:hover {
      background: var(--ink);
      color: #fff;
      border-color: var(--ink);
    }
    .btn-action.btn-delete:hover {
      background: #b53826;
      border-color: #b53826;
      color: #fff;
    }
    .status-select {
      font-size: 11px;
      padding: 4px 8px;
      border-radius: 4px;
      border: 1px solid #d5dfd5;
      background: #fff;
    }
    .flash-alert {
      padding: 12px 18px;
      border-radius: 6px;
      margin-bottom: 24px;
      font-size: 14px;
      font-weight: 500;
    }
    .flash-alert.success { background: #eaf6ee; color: #1d6e38; border: 1px solid #cce8d4; }
    .flash-alert.error { background: #fdf2ef; color: #a13b22; border: 1px solid #f6cfc3; }
  </style>
</head>
<body>
  <!-- Admin Navbar -->
  <header class="admin-navbar">
    <div class="container admin-nav-inner">
      <div class="admin-brand">
        <a href="index.php"><img src="../assets/rightfit-logo-white.png" alt="RightFit"></a>
        <span>Intake Portal</span>
      </div>
      <div class="admin-links">
        <span>Logged in as <strong><?= e($_SESSION['admin_username']) ?></strong></span>
        <a href="settings.php">Password &amp; Settings</a>
        <a href="../index.php" target="_blank">View Live Site ↗</a>
        <a href="logout.php" style="color: #ffb8a6;">Log Out</a>
      </div>
    </div>
  </header>

  <main class="admin-container">
    <div class="container">
      <?php if ($flash): ?>
        <div class="flash-alert <?= e($flash['type']) ?>" role="alert">
          <?= e($flash['message']) ?>
        </div>
      <?php endif; ?>

      <!-- Metrics Row -->
      <div class="metrics-grid">
        <div class="metric-card highlight">
          <span class="label">Pending Action</span>
          <span class="value"><?= $newCount ?></span>
        </div>
        <div class="metric-card">
          <span class="label">Total Referrals</span>
          <span class="value"><?= $referralCount ?></span>
        </div>
        <div class="metric-card">
          <span class="label">General Enquiries</span>
          <span class="value"><?= $enquiryCount ?></span>
        </div>
        <div class="metric-card">
          <span class="label">Total Records</span>
          <span class="value"><?= $totalCount ?></span>
        </div>
      </div>

      <!-- Filter & Export Card -->
      <div class="filter-card">
        <form class="filter-form" method="GET" action="index.php">
          <input type="text" name="q" placeholder="Search participant, referrer, email..." value="<?= e($search) ?>">

          <select name="type">
            <option value="">All Types</option>
            <option value="referral" <?= $filterType === 'referral' ? 'selected' : '' ?>>Client Referrals</option>
            <option value="enquiry" <?= $filterType === 'enquiry' ? 'selected' : '' ?>>General Enquiries</option>
          </select>

          <select name="status">
            <option value="">All Statuses</option>
            <option value="new" <?= $filterStatus === 'new' ? 'selected' : '' ?>>New</option>
            <option value="in_progress" <?= $filterStatus === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
            <option value="completed" <?= $filterStatus === 'completed' ? 'selected' : '' ?>>Completed</option>
            <option value="archived" <?= $filterStatus === 'archived' ? 'selected' : '' ?>>Archived</option>
          </select>

          <button type="submit" class="btn btn-small btn-dark">Filter</button>
          <?php if (!empty($search) || !empty($filterType) || !empty($filterStatus)): ?>
            <a href="index.php" class="btn-action">Reset</a>
          <?php endif; ?>
        </form>

        <a href="export.php<?= !empty($_SERVER['QUERY_STRING']) ? '?' . e($_SERVER['QUERY_STRING']) : '' ?>" class="btn btn-small btn-copper">
          Export CSV 📥
        </a>
      </div>

      <!-- Submissions Table -->
      <div class="table-card">
        <div class="table-responsive">
          <table class="data-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Type</th>
                <th>Participant / Contact</th>
                <th>Services &amp; Funding</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($submissions)): ?>
                <tr>
                  <td colspan="7" style="text-align: center; padding: 48px; color: var(--muted);">
                    No enquiries or referrals found matching your criteria.
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($submissions as $sub): ?>
                  <tr>
                    <td><strong>#<?= $sub['id'] ?></strong></td>
                    <td style="white-space: nowrap; color: var(--muted);">
                      <?= date('d M Y, H:i', strtotime($sub['created_at'])) ?>
                    </td>
                    <td>
                      <?php if ($sub['type'] === 'referral'): ?>
                        <span class="badge badge-referral">⭐ Referral</span>
                      <?php else: ?>
                        <span class="badge badge-enquiry">✉️ Enquiry</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <strong><?= e($sub['name']) ?></strong>
                      <?php if (!empty($sub['participant_suburb'])): ?>
                        <div style="font-size: 11px; color: var(--muted);"><?= e($sub['participant_suburb']) ?></div>
                      <?php endif; ?>
                      <div style="font-size: 11px; margin-top: 2px;">
                        <a href="mailto:<?= e($sub['email']) ?>"><?= e($sub['email']) ?></a>
                        <?= !empty($sub['phone']) ? ' · ' . e($sub['phone']) : '' ?>
                      </div>
                    </td>
                    <td>
                      <div style="max-width: 260px; line-height: 1.4;">
                        <strong><?= e($sub['service']) ?></strong>
                        <?php if (!empty($sub['funding_type'])): ?>
                          <div style="font-size: 11px; color: #8c5232; margin-top: 2px;"><?= e($sub['funding_type']) ?></div>
                        <?php endif; ?>
                      </div>
                    </td>
                    <td>
                      <form method="POST" action="index.php<?= !empty($_SERVER['QUERY_STRING']) ? '?' . e($_SERVER['QUERY_STRING']) : '' ?>" style="margin: 0;">
                        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                        <input type="hidden" name="action" value="update_status">
                        <input type="hidden" name="id" value="<?= $sub['id'] ?>">
                        <select name="status" class="status-select" onchange="this.form.submit()">
                          <option value="new" <?= $sub['status'] === 'new' ? 'selected' : '' ?>>New</option>
                          <option value="in_progress" <?= $sub['status'] === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                          <option value="completed" <?= $sub['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                          <option value="archived" <?= $sub['status'] === 'archived' ? 'selected' : '' ?>>Archived</option>
                        </select>
                      </form>
                    </td>
                    <td>
                      <div class="action-group">
                        <a href="view.php?id=<?= $sub['id'] ?>" class="btn-action">View Full ↗</a>
                        <form method="POST" action="index.php" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete submission #<?= $sub['id'] ?>? This action cannot be undone.');">
                          <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                          <input type="hidden" name="action" value="delete">
                          <input type="hidden" name="id" value="<?= $sub['id'] ?>">
                          <button type="submit" class="btn-action btn-delete">Delete</button>
                        </form>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>
</body>
</html>
