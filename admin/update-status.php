<?php
include '../includes/auth.php';
requireRole('admin');
include '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $report_id = $_POST['report_id'];
    $status = $_POST['status'];
    
    if (!empty($report_id) && !empty($status)) {
        updateReportStatus($report_id, $status);
        $_SESSION['success'] = 'Status updated successfully.';
    } else {
        $_SESSION['error'] = 'Please select a status.';
    }
}

header("Location: dashboard.php");
exit();
?>