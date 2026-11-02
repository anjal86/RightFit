<?php
if (getenv('RIGHTFIT_ADMIN_ENABLED') !== '1' || strlen(getenv('RIGHTFIT_ADMIN_BOOTSTRAP_PASSWORD') ?: '') < 16) { http_response_code(503); header('Content-Type: text/plain; charset=utf-8'); exit('Admin login unavailable.'); }
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$ip = getClientIp();
$error = '';
$isLocked = isRateLimited($ip);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$isLocked) {
    $token = $_POST['csrf_token'] ?? '';
    if (!verifyCsrfToken($token)) {
        $error = 'Invalid security token. Please refresh and try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $error = 'Please enter both username and password.';
        } else {
            $pdo = getDb();
            $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = :u LIMIT 1");
            $stmt->execute([':u' => $username]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin['password_hash'])) {
                // Successful login
                clearLoginAttempts($ip);
                session_regenerate_id(true);
                $_SESSION['admin_user_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_email'] = $admin['email'];

                // Update last login
                $upd = $pdo->prepare("UPDATE admins SET last_login = " . (DB_DRIVER === 'sqlite' ? "datetime('now')" : "NOW()") . " WHERE id = :id");
                $upd->execute([':id' => $admin['id']]);

                header('Location: index.php');
                exit;
            } else {
                recordFailedAttempt($ip);
                if (isRateLimited($ip)) {
                    $isLocked = true;
                    $error = 'Too many failed login attempts. Account temporarily locked for 15 minutes.';
                } else {
                    $error = 'Invalid username or password.';
                }
            }
        }
    }
}
?>
<!doctype html>
<html lang="en-AU">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login | RightFit Allied Health</title>
  <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="128x128" href="../assets/favicon.png">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../styles.css">
  <style>
    .login-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f7f5ef;
      padding: 24px;
    }
    .login-card {
      background: #ffffff;
      border: 1px solid #e2e8e2;
      border-radius: 12px;
      padding: 44px 40px;
      width: min(440px, 100%);
      box-shadow: 0 16px 40px rgba(25, 61, 68, 0.08);
    }
    .login-logo {
      text-align: center;
      margin-bottom: 28px;
    }
    .login-logo img {
      max-width: 190px;
      margin: 0 auto;
    }
    .login-card h1 {
      font-size: 20px;
      font-weight: 700;
      color: var(--ink);
      margin: 0 0 6px;
      text-align: center;
    }
    .login-card p.subhead {
      font-size: 13px;
      color: var(--muted);
      text-align: center;
      margin: 0 0 24px;
    }
    .login-alert {
      padding: 12px 14px;
      border-radius: 6px;
      font-size: 13px;
      margin-bottom: 20px;
      background: #fdf2ef;
      color: #9c3c23;
      border: 1px solid #f7cfc4;
    }
    .login-form {
      display: flex;
      flex-direction: column;
      gap: 18px;
    }
    .login-field {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }
    .login-field label {
      font-size: 13px;
      font-weight: 700;
      color: var(--ink);
    }
    .login-field input {
      border: 1px solid #d4ded4;
      border-radius: 6px;
      padding: 12px 14px;
      font-size: 14px;
      outline: none;
      transition: border-color 0.2s;
    }
    .login-field input:focus {
      border-color: var(--forest);
      box-shadow: 0 0 0 3px rgba(54, 88, 73, 0.12);
    }
    .login-btn {
      width: 100%;
      margin-top: 8px;
    }
    .login-footer {
      margin-top: 24px;
      text-align: center;
      font-size: 12px;
      color: #7b8e86;
    }
    .login-footer a {
      color: var(--ink);
      font-weight: 600;
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-card">
      <div class="login-logo">
        <picture>
          <source srcset="../assets/rightfit-logo.webp" type="image/webp">
          <img src="../assets/rightfit-logo.png" alt="RightFit Allied Health Services" width="210">
        </picture>
      </div>
      <h1>Intake Portal Login</h1>
      <p class="subhead">Secure access to clinical enquiries and participant referrals.</p>

      <?php if (!empty($error)): ?>
        <div class="login-alert" role="alert">
          <?= e($error) ?>
        </div>
      <?php endif; ?>

      <?php if ($isLocked): ?>
        <div class="login-alert">
          <strong>Account Temporarily Locked:</strong> Maximum failed attempts reached from this IP address. Please wait 15 minutes before attempting again.
        </div>
      <?php else: ?>
        <form class="login-form" method="POST" action="login.php">
          <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

          <div class="login-field">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required autocomplete="username" autofocus>
          </div>

          <div class="login-field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">
          </div>

          <button type="submit" class="btn btn-copper login-btn">Log In to Dashboard <i data-lucide="arrow-right"></i></button>
        </form>
      <?php endif; ?>

      <div class="login-footer">
        <a href="../index.php" style="display: inline-flex; align-items: center; gap: 4px;"><i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i> Return to Main Website</a>
      </div>
    </div>
  </div>

  <script src="../assets/lucide.min.js"></script>
  <script>
    if (typeof lucide !== 'undefined') {
      lucide.createIcons();
    }
  </script>
</body>
</html>

