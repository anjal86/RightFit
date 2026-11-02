<?php
/**
 * RightFit Allied Health Services — Application Configuration
 * Works out-of-the-box on standard cPanel hosting with zero setup (SQLite default)
 * or can be connected to cPanel MySQL/MariaDB database.
 */

// 1. Error Reporting (Turn off display_errors in production, log errors securely)
ini_set('display_errors', '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);

// 2. Application Identity
define('APP_NAME', 'RightFit Allied Health Services');
define('APP_EMAIL', 'enquiries@rightfitalliedhealth.com.au');
define('APP_PHONE', '1300 RIGHTFIT');
define('APP_PHONE_TEL', '1300000000');
define('APP_URL', 'https://www.rightfitalliedhealth.com.au');

// 3. Email Notification Configuration
// Set to true to send email notifications via PHP mail() on new submissions
define('NOTIFY_ADMIN_EMAIL', true);
define('ADMIN_NOTIFICATION_EMAIL', 'enquiries@rightfitalliedhealth.com.au');

// 4. Database Configuration
// 'sqlite' requires NO database setup on cPanel — works immediately!
// To switch to cPanel MySQL, change DB_DRIVER to 'mysql' and fill the credentials below.
define('DB_DRIVER', 'sqlite'); 

// SQLite configuration (Stored securely in /data/ protected by .htaccess)
define('DB_SQLITE_PATH', __DIR__ . '/data/rightfit.sqlite');

// MySQL configuration (Only used if DB_DRIVER is 'mysql')
define('DB_MYSQL_HOST', 'localhost');
define('DB_MYSQL_PORT', '3306');
define('DB_MYSQL_NAME', 'rightfit_db');
define('DB_MYSQL_USER', 'rightfit_user');
define('DB_MYSQL_PASS', 'your_secure_password');
define('DB_MYSQL_CHARSET', 'utf8mb4');

// 5. Default First-Time Admin Credentials
// An administrator account is automatically provisioned if the database is fresh.
// Username: admin
// Password: RightFit2026!
define('DEFAULT_ADMIN_USER', 'admin');
define('DEFAULT_ADMIN_PASS', 'RightFit2026!');

// 6. Security & Session Settings
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_samesite', 'Lax');
    // If running under HTTPS, enable secure cookie flag
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        ini_set('session.cookie_secure', '1');
    }
    session_start();
}
