<?php
include '../includes/auth.php';
requireRole('admin');
include '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $report_id = $_POST['report_id'];
    $driver_id = $_POST['driver_id'];
    
    if (!empty($report_id) && !empty($driver_id)) {
        updateReportStatus($report_id, 'In Progress', $driver_id);
        $_SESSION['success'] = 'Driver assigned successfully.';
    } else {
        $_SESSION['error'] = 'Please select a driver.';
    }
}

header("Location: dashboard.php");
exit();
?>