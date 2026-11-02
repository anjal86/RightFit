<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

requireLogin();
$pdo = getDb();

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
$query = "SELECT * FROM submissions $whereSql ORDER BY id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$rows = $stmt->fetchAll();

$filename = 'rightfit-intake-' . date('Y-m-d') . '.csv';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

$output = fopen('php://output', 'w');
// UTF-8 BOM for clean Excel opening
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// CSV Header
fputcsv($output, [
    'ID',
    'Date Submitted',
    'Type',
    'Status',
    'Contact Name',
    'Email',
    'Phone',
    'Service(s)',
    'Referrer Role',
    'Referrer Name',
    'Referrer Organization',
    'Referrer Email',
    'Referrer Phone',
    'Participant Name',
    'Participant DOB/Age',
    'Participant Suburb',
    'Funding Type',
    'NDIS Number',
    'Delivery Preference',
    'Consent Confirmed',
    'Message / Clinical Goals',
    'Internal Admin Notes'
]);

foreach ($rows as $r) {
    fputcsv($output, [
        $r['id'],
        $r['created_at'],
        strtoupper($r['type']),
        ucfirst($r['status']),
        $r['name'],
        $r['email'],
        $r['phone'],
        $r['service'],
        $r['referrer_role'],
        $r['referrer_name'],
        $r['referrer_org'],
        $r['referrer_email'],
        $r['referrer_phone'],
        $r['participant_name'],
        $r['participant_dob'],
        $r['participant_suburb'],
        $r['funding_type'],
        $r['ndis_number'],
        $r['delivery_preference'],
        $r['consent_confirmed'] ? 'Yes' : 'No',
        $r['message'],
        $r['admin_notes'] ?? ''
    ]);
}

fclose($output);
exit;
