<?php
/**
 * Authentication, Session Security & Helper Functions
 */

require_once __DIR__ . '/db.php';

// Check if current user is logged in
function isLoggedIn(): bool {
    return !empty($_SESSION['admin_user_id']) && !empty($_SESSION['admin_username']);
}

// Enforce authentication guard for admin pages
function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

// Generate or retrieve CSRF token
function csrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verify CSRF token
function verifyCsrfToken(?string $token): bool {
    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

// Rate Limiting: Check failed attempts (Max 5 attempts within 15 minutes)
function isRateLimited(string $ip): bool {
    $pdo = getDb();
    $cutoff = time() - (15 * 60);

    // Clean up old attempts
    $cleanup = $pdo->prepare("DELETE FROM login_attempts WHERE attempted_at < :cutoff");
    $cleanup->execute([':cutoff' => $cutoff]);

    $stmt = $pdo->prepare("SELECT COUNT(*) as attempts FROM login_attempts WHERE ip_address = :ip AND attempted_at >= :cutoff");
    $stmt->execute([':ip' => $ip, ':cutoff' => $cutoff]);
    $row = $stmt->fetch();

    return ((int)$row['attempts']) >= 5;
}

// Record a failed login attempt
function recordFailedAttempt(string $ip): void {
    $pdo = getDb();
    $stmt = $pdo->prepare("INSERT INTO login_attempts (ip_address, attempted_at) VALUES (:ip, :time)");
    $stmt->execute([':ip' => $ip, ':time' => time()]);
}

// Clear attempts on successful login
function clearLoginAttempts(string $ip): void {
    $pdo = getDb();
    $stmt = $pdo->prepare("DELETE FROM login_attempts WHERE ip_address = :ip");
    $stmt->execute([':ip' => $ip]);
}

// HTML Escaping for output
function e(?string $string): string {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

// Client IP detection (safe for proxies and cPanel)
function getClientIp(): string {
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        return $_SERVER['HTTP_CF_CONNECTING_IP'];
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ips[0]);
    }
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

// Session Flash Messaging
function setFlash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array {
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
