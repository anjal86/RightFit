<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

requireLogin();
$pdo = getDb();
$userId = $_SESSION['admin_user_id'];
$error = '';
$flash = getFlash();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verifyCsrfToken($token)) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $currentPass = $_POST['current_password'] ?? '';
        $newPass = $_POST['new_password'] ?? '';
        $confirmPass = $_POST['confirm_password'] ?? '';

        $stmt = $pdo->prepare("SELECT * FROM admins WHERE id = :id");
        $stmt->execute([':id' => $userId]);
        $admin = $stmt->fetch();

        if (!$admin || !password_verify($currentPass, $admin['password_hash'])) {
            $error = 'Your current password was entered incorrectly.';
        } elseif (strlen($newPass) < 8) {
            $error = 'The new password must be at least 8 characters long.';
        } elseif ($newPass !== $confirmPass) {
            $error = 'The new password and confirmation password do not match.';
        } else {
            $newHash = password_hash($newPass, PASSWORD_DEFAULT);
            $upd = $pdo->prepare("UPDATE admins SET password_hash = :p WHERE id = :id");
            $upd->execute([':p' => $newHash, ':id' => $userId]);

            setFlash('success', 'Your administrator password has been updated successfully.');
            header('Location: settings.php');
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="en-AU">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Settings | RightFit Allied Health</title>
  <link rel="icon" href="../assets/favicon.png">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../styles.css">
  <style>
    body { background: #f8f6f0; color: var(--ink); }
    .admin-navbar { background: #193d44; color: #fff; padding: 14px 0; }
    .admin-nav-inner { display: flex; align-items: center; justify-content: space-between; }
    .admin-brand img { height: 38px; filter: brightness(0) invert(1); }
    .settings-container { padding: 40px 0 80px; }
    .settings-card {
      background: #fff;
      border: 1px solid #e1e7e1;
      border-radius: 12px;
      padding: 36px 40px;
      max-width: 520px;
      margin: 0 auto;
      box-shadow: 0 4px 20px rgba(25, 61, 68, 0.04);
    }
    .settings-card h1 {
      font-size: 22px;
      font-weight: 700;
      color: var(--ink);
      margin: 0 0 8px;
    }
    .settings-card p.subhead {
      font-size: 13px;
      color: var(--muted);
      margin: 0 0 24px;
    }
    .form-group {
      display: flex;
      flex-direction: column;
      gap: 6px;
      margin-bottom: 18px;
    }
    .form-group label {
      font-size: 13px;
      font-weight: 700;
      color: var(--forest);
    }
    .form-group input {
      border: 1px solid #d4ded4;
      border-radius: 6px;
      padding: 10px 14px;
      font-size: 14px;
    }
    .alert {
      padding: 12px 16px;
      border-radius: 6px;
      font-size: 13px;
      margin-bottom: 20px;
    }
    .alert-error { background: #fdf2ef; color: #a13b22; border: 1px solid #f6cfc3; }
    .alert-success { background: #eaf6ee; color: #1d6e38; border: 1px solid #cce8d4; }
  </style>
</head>
<body>
  <header class="admin-navbar">
    <div class="container admin-nav-inner">
      <div style="display: flex; align-items: center; gap: 14px;">
        <a href="index.php"><img src="../assets/rightfit-logo-white.png" alt="RightFit"></a>
        <span style="font-weight: 700; font-size: 15px;">Admin Settings</span>
      </div>
      <div>
        <a href="index.php" style="color: #cfded9; font-size: 13px;">← Return to Dashboard</a>
      </div>
    </div>
  </header>

  <main class="settings-container">
    <div class="container">
      <div class="settings-card">
        <h1>Change Administrator Password</h1>
        <p class="subhead">Manage access to the RightFit clinical intake portal.</p>

        <?php if (!empty($error)): ?>
          <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>

        <?php if ($flash): ?>
          <div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
        <?php endif; ?>

        <form method="POST" action="settings.php">
          <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

          <div class="form-group">
            <label for="current_password">Current Password</label>
            <input type="password" id="current_password" name="current_password" required>
          </div>

          <div class="form-group">
            <label for="new_password">New Password (min. 8 characters)</label>
            <input type="password" id="new_password" name="new_password" required minlength="8">
          </div>

          <div class="form-group">
            <label for="confirm_password">Confirm New Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
          </div>

          <button type="submit" class="btn btn-copper" style="width: 100%; margin-top: 10px;">Update Password ↗</button>
        </form>

        <div style="margin-top: 24px; text-align: center;">
          <a href="index.php" style="font-size: 13px; color: var(--muted); text-decoration: underline;">Cancel &amp; return</a>
        </div>
      </div>
    </div>
  </main>
</body>
</html>
