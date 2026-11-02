<?php
if (getenv('RIGHTFIT_INTAKE_ENABLED') !== '1') { http_response_code(503); header('Content-Type: application/json; charset=utf-8'); echo json_encode(['success'=>false,'message'=>'Online intake is not available.']); exit; }
/**
 * RightFit Allied Health Services — Secure Submission Endpoint
 * Handles General Enquiries and Client Referrals.
 * Compatible with AJAX fetch and traditional form POST.
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
    || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

function sendResponse(bool $success, string $message, int $statusCode = 200, array $extra = []) {
    global $isAjax;
    http_response_code($statusCode);
    if ($isAjax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array_merge(['success' => $success, 'message' => $message], $extra));
        exit;
    } else {
        setFlash($success ? 'success' : 'error', $message);
        header('Location: ' . (!empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'contact.php'));
        exit;
    }
}

// Only accept POST requests
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    sendResponse(false, 'Secure HTTPS is required for submissions.', 503);
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Invalid request method.', 405);
}

if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
    sendResponse(false, 'Invalid security token.', 403);
}

// 1. Anti-Spam Honeypot Verification
if (!empty($_POST['_website_trap'])) {
    // Bot detected — quietly return success to fool spam bot
    sendResponse(true, 'Thank you! Your submission has been received.');
}

// 2. Determine Submission Type ('enquiry' or 'referral')
$type = (!empty($_POST['form_type']) && $_POST['form_type'] === 'referral') ? 'referral' : 'enquiry';

// 3. Extract & Sanitize Common Data
$pdo = getDb();
$ip = getClientIp();
$userAgent = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);

if ($type === 'enquiry') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $service = trim($_POST['service'] ?? 'General Enquiry');
    $message = trim($_POST['message'] ?? '');

    // Validation
    if (empty($name) || empty($email) || empty($message)) {
        sendResponse(false, 'Please fill in all required fields (Name, Email, Message).', 400);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendResponse(false, 'Please provide a valid email address.', 400);
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO submissions (
                type, name, email, phone, service, message,
                status, ip_address, user_agent
            ) VALUES (
                'enquiry', :name, :email, :phone, :service, :message,
                'new', :ip, :ua
            )
        ");

        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':service' => $service,
            ':message' => $message,
            ':ip' => $ip,
            ':ua' => $userAgent
        ]);

        $submissionId = $pdo->lastInsertId();

        // Optional Email Notification
        if (NOTIFY_ADMIN_EMAIL) {
            $subject = "RightFit Website Enquiry: " . $name . " [" . $service . "]";
            $body = "A new general enquiry was submitted on the RightFit website:\n\n"
                  . "Name: $name\n"
                  . "Email: $email\n"
                  . "Phone: " . ($phone ?: 'Not provided') . "\n"
                  . "Service: $service\n\n"
                  . "Message:\n$message\n\n"
                  . "Submitted: " . date('Y-m-d H:i:s') . "\n"
                  . "IP: $ip\n";
            @mail(ADMIN_NOTIFICATION_EMAIL, $subject, $body, "From: " . APP_NAME . " <no-reply@" . parse_url(APP_URL, PHP_URL_HOST) . ">");
        }

        sendResponse(true, 'Thank you! Your general enquiry has been received. Our team will contact you shortly.');
    } catch (Exception $e) {
        error_log('Enquiry save error: ' . $e->getMessage());
        sendResponse(false, 'An unexpected error occurred while saving your enquiry. Please try again.', 500);
    }
} else {
    // 4. Referral Intake Submission
    $referrerRole = trim($_POST['referrer_role'] ?? 'Participant (Self-referral)');
    $referrerName = trim($_POST['referrer_name'] ?? '');
    $referrerOrg = trim($_POST['referrer_org'] ?? '');
    $referrerEmail = trim($_POST['referrer_email'] ?? '');
    $referrerPhone = trim($_POST['referrer_phone'] ?? '');

    $participantName = trim($_POST['participant_name'] ?? '');
    $participantDob = trim($_POST['participant_dob'] ?? '');
    $participantSuburb = trim($_POST['participant_suburb'] ?? '');
    $fundingType = trim($_POST['funding_type'] ?? 'NDIS - Plan-Managed');
    $ndisNumber = trim($_POST['ndis_number'] ?? '');
    $deliveryPreference = trim($_POST['delivery_preference'] ?? 'Flexible');

    $servicesRequested = isset($_POST['services_requested']) ? (array)$_POST['services_requested'] : [];
    $servicesString = implode(', ', array_map('trim', $servicesRequested));

    $referralNotes = trim($_POST['referral_notes'] ?? '');
    $consentConfirmed = !empty($_POST['consent_confirmed']) ? 1 : 0;

    // Validation
    if (empty($referrerName) || empty($referrerEmail) || empty($referrerPhone) || empty($participantName) || empty($participantSuburb)) {
        sendResponse(false, 'Please complete all required referral fields.', 400);
    }

    if (!filter_var($referrerEmail, FILTER_VALIDATE_EMAIL)) {
        sendResponse(false, 'Please provide a valid referrer email address.', 400);
    }

    if ($consentConfirmed !== 1) {
        sendResponse(false, 'Please confirm participant consent before submitting.', 400);
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO submissions (
                type, name, email, phone, service,
                referrer_role, referrer_name, referrer_org, referrer_email, referrer_phone,
                participant_name, participant_dob, participant_suburb, funding_type,
                ndis_number, delivery_preference, services_requested, message,
                consent_confirmed, status, ip_address, user_agent
            ) VALUES (
                'referral', :p_name, :r_email, :r_phone, :service,
                :r_role, :r_name, :r_org, :r_email, :r_phone,
                :p_name, :p_dob, :p_suburb, :funding,
                :ndis, :delivery, :services, :notes,
                :consent, 'new', :ip, :ua
            )
        ");

        $stmt->execute([
            ':p_name' => $participantName,
            ':r_email' => $referrerEmail,
            ':r_phone' => $referrerPhone,
            ':service' => $servicesString ?: 'Multi-Disciplinary Allied Health',
            ':r_role' => $referrerRole,
            ':r_name' => $referrerName,
            ':r_org' => $referrerOrg,
            ':p_dob' => $participantDob,
            ':p_suburb' => $participantSuburb,
            ':funding' => $fundingType,
            ':ndis' => $ndisNumber,
            ':delivery' => $deliveryPreference,
            ':services' => $servicesString,
            ':notes' => $referralNotes,
            ':consent' => $consentConfirmed,
            ':ip' => $ip,
            ':ua' => $userAgent
        ]);

        // Optional Email Notification
        if (NOTIFY_ADMIN_EMAIL) {
            $subject = "⭐ New RightFit Client Referral: " . $participantName . " (" . $fundingType . ")";
            $body = "A new client referral was lodged on the RightFit website:\n\n"
                  . "REFERRER:\n"
                  . "Role: $referrerRole\n"
                  . "Name: $referrerName\n"
                  . "Organization: " . ($referrerOrg ?: 'N/A') . "\n"
                  . "Email: $referrerEmail\n"
                  . "Phone: $referrerPhone\n\n"
                  . "PARTICIPANT:\n"
                  . "Name: $participantName\n"
                  . "DOB / Age: " . ($participantDob ?: 'Not provided') . "\n"
                  . "Suburb: $participantSuburb\n"
                  . "Funding: $fundingType\n"
                  . "NDIS Number: " . ($ndisNumber ?: 'Not provided') . "\n\n"
                  . "SERVICES & PREFERENCES:\n"
                  . "Requested Services: " . ($servicesString ?: 'None specified') . "\n"
                  . "Delivery: $deliveryPreference\n\n"
                  . "PRIMARY GOALS & CLINICAL CONTEXT:\n"
                  . ($referralNotes ?: 'No additional notes.') . "\n\n"
                  . "Consent Confirmed: Yes\n"
                  . "Submitted: " . date('Y-m-d H:i:s') . "\n";
            @mail(ADMIN_NOTIFICATION_EMAIL, $subject, $body, "From: " . APP_NAME . " <no-reply@" . parse_url(APP_URL, PHP_URL_HOST) . ">");
        }

        sendResponse(true, 'Thank you! The referral for ' . $participantName . ' has been lodged successfully. Our clinical intake team will review it within 1–2 business days.');
    } catch (Exception $e) {
        error_log('Referral save error: ' . $e->getMessage());
        sendResponse(false, 'An unexpected error occurred while saving the referral. Please try again.', 500);
    }
}

