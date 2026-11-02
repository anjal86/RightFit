<?php
/**
 * Database Connection & Schema Provisioning
 * Automatically initializes database tables on first launch.
 */

require_once __DIR__ . '/../config.php';

function getDb(): PDO {
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    try {
        if (DB_DRIVER === 'sqlite') {
            $dataDir = dirname(DB_SQLITE_PATH);
            if (!is_dir($dataDir)) {
                mkdir($dataDir, 0750, true);
            }
            $pdo = new PDO('sqlite:' . DB_SQLITE_PATH);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $pdo->exec('PRAGMA foreign_keys = ON;');
        } else {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                DB_MYSQL_HOST,
                DB_MYSQL_PORT,
                DB_MYSQL_NAME,
                DB_MYSQL_CHARSET
            );
            $pdo = new PDO($dsn, DB_MYSQL_USER, DB_MYSQL_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        }

        // Initialize schema if not already initialized
        initDatabaseSchema($pdo);

        return $pdo;
    } catch (PDOException $e) {
        error_log('Database Connection Error: ' . $e->getMessage());
        die('Database connection could not be established. Please check configuration.');
    }
}

function initDatabaseSchema(PDO $pdo): void {
    if (DB_DRIVER === 'sqlite') {
        // 1. Submissions table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS submissions (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                type TEXT NOT NULL, /* 'enquiry' or 'referral' */
                name TEXT NOT NULL,
                email TEXT NOT NULL,
                phone TEXT,
                service TEXT,
                referrer_role TEXT,
                referrer_name TEXT,
                referrer_org TEXT,
                referrer_email TEXT,
                referrer_phone TEXT,
                participant_name TEXT,
                participant_dob TEXT,
                participant_suburb TEXT,
                funding_type TEXT,
                ndis_number TEXT,
                delivery_preference TEXT,
                services_requested TEXT,
                message TEXT,
                consent_confirmed INTEGER DEFAULT 0,
                status TEXT DEFAULT 'new', /* 'new', 'in_progress', 'completed', 'archived' */
                admin_notes TEXT,
                ip_address TEXT,
                user_agent TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
        ");

        // 2. Admins table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS admins (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT UNIQUE NOT NULL,
                password_hash TEXT NOT NULL,
                email TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                last_login DATETIME
            );
        ");

        // 3. Login Attempts (Rate Limiting)
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS login_attempts (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                ip_address TEXT NOT NULL,
                attempted_at INTEGER NOT NULL
            );
        ");
    } else {
        // MySQL Schema
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS submissions (
                id INT AUTO_INCREMENT PRIMARY KEY,
                type VARCHAR(20) NOT NULL,
                name VARCHAR(150) NOT NULL,
                email VARCHAR(200) NOT NULL,
                phone VARCHAR(50) NULL,
                service VARCHAR(100) NULL,
                referrer_role VARCHAR(100) NULL,
                referrer_name VARCHAR(150) NULL,
                referrer_org VARCHAR(150) NULL,
                referrer_email VARCHAR(200) NULL,
                referrer_phone VARCHAR(50) NULL,
                participant_name VARCHAR(150) NULL,
                participant_dob VARCHAR(50) NULL,
                participant_suburb VARCHAR(150) NULL,
                funding_type VARCHAR(100) NULL,
                ndis_number VARCHAR(50) NULL,
                delivery_preference VARCHAR(100) NULL,
                services_requested TEXT NULL,
                message TEXT NULL,
                consent_confirmed TINYINT(1) DEFAULT 0,
                status VARCHAR(30) DEFAULT 'new',
                admin_notes TEXT NULL,
                ip_address VARCHAR(45) NULL,
                user_agent VARCHAR(255) NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS admins (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) UNIQUE NOT NULL,
                password_hash VARCHAR(255) NOT NULL,
                email VARCHAR(200) NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                last_login TIMESTAMP NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS login_attempts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                ip_address VARCHAR(45) NOT NULL,
                attempted_at INT NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
    }

    // Provision default administrator if table is empty
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM admins");
    $row = $stmt->fetch();
    if ((int)$row['count'] === 0) {
        $defaultHash = password_hash(DEFAULT_ADMIN_PASS, PASSWORD_DEFAULT);
        $insert = $pdo->prepare("INSERT INTO admins (username, password_hash, email) VALUES (:u, :p, :e)");
        $insert->execute([
            ':u' => DEFAULT_ADMIN_USER,
            ':p' => $defaultHash,
            ':e' => APP_EMAIL,
        ]);
    }
}

