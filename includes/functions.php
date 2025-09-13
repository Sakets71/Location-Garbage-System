<?php
// Include config only if not already included
if (!isset($pdo)) {
    include 'config.php';
}

// Get all reports
function getAllReports() {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT r.*, u.name as user_name, d.name as driver_name 
        FROM reports r 
        LEFT JOIN users u ON r.user_id = u.id 
        LEFT JOIN drivers dr ON r.driver_id = dr.id 
        LEFT JOIN users d ON dr.user_id = d.id 
        ORDER BY r.created_at DESC
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get reports by user
function getUserReports($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM reports WHERE user_id = :user_id ORDER BY created_at DESC");
    $stmt->execute(['user_id' => $user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get assigned reports for driver
function getDriverReports($driver_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM reports WHERE driver_id = :driver_id ORDER BY created_at DESC");
    $stmt->execute(['driver_id' => $driver_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get all drivers
function getAllDrivers() {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT d.*, u.name 
        FROM drivers d 
        JOIN users u ON d.user_id = u.id 
        WHERE d.status = 'available'
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Update report status
function updateReportStatus($report_id, $status, $driver_id = null) {
    global $pdo;
    if ($driver_id) {
        $stmt = $pdo->prepare("UPDATE reports SET status = :status, driver_id = :driver_id WHERE id = :id");
        $stmt->execute(['status' => $status, 'driver_id' => $driver_id, 'id' => $report_id]);
    } else {
        $stmt = $pdo->prepare("UPDATE reports SET status = :status WHERE id = :id");
        $stmt->execute(['status' => $status, 'id' => $report_id]);
    }
    return $stmt->rowCount();
}

// Get report by ID
function getReportById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM reports WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get driver by user ID
function getDriverByUserId($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM drivers WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>