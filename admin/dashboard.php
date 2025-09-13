<?php
include '../includes/auth.php';
requireRole('admin');
include '../includes/functions.php';

$reports = getAllReports();
$drivers = getAllDrivers();

// Handle status update
if (isset($_POST['update_status'])) {
    $report_id = $_POST['report_id'];
    $status = $_POST['status'];
    updateReportStatus($report_id, $status);
    header("Location: dashboard.php");
    exit();
}

// Handle driver assignment
if (isset($_POST['assign_driver'])) {
    $report_id = $_POST['report_id'];
    $driver_id = $_POST['driver_id'];
    updateReportStatus($report_id, 'In Progress', $driver_id);
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Smart Garbage Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        :root {
            --primary: #28a745;
            --primary-dark: #218838;
            --secondary: #6c757d;
            --light: #f8f9fa;
            --dark: #343a40;
            --accent: #20c997;
            --warning: #ffc107;
            --info: #17a2b8;
            --light-bg: #f8f9fa;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --hover-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            color: #333;
            overflow-x: hidden;
        }
        
        .navbar {
            background: var(--dark) !important;
            padding: 0.8rem 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .navbar-dark .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
            border-radius: 4px;
        }
        
        .navbar-dark .navbar-nav .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .navbar-dark .navbar-nav .nav-link.active {
            background: var(--primary);
            color: white;
        }
        
        .dashboard-header {
            background: linear-gradient(120deg, var(--primary), var(--accent));
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .stats-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            border: none;
            height: 100%;
            padding: 1.5rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }
        
        .stats-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .stats-number {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .stats-label {
            color: var(--secondary);
            font-weight: 500;
        }
        
        .card-primary .stats-icon {
            color: var(--primary);
        }
        
        .card-warning .stats-icon {
            color: var(--warning);
        }
        
        .card-info .stats-icon {
            color: var(--info);
        }
        
        .card-success .stats-icon {
            color: var(--primary);
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary);
            display: inline-block;
            color: var(--dark);
        }
        
        .report-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
            border: none;
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .report-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--hover-shadow);
        }
        
        .report-header {
            background: var(--light);
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #eee;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .report-body {
            padding: 1.5rem;
        }
        
        .report-image {
            border-radius: 8px;
            overflow: hidden;
            height: 120px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .report-image img {
            object-fit: cover;
            width: 100%;
            height: 100%;
        }
        
        .report-details {
            padding: 0;
            list-style: none;
        }
        
        .report-details li {
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }
        
        .report-details li i {
            color: var(--primary);
            margin-right: 0.5rem;
            width: 20px;
        }
        
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-in-progress {
            background: #cce7ff;
            color: #004085;
        }
        
        .status-cleaned {
            background: #d4edda;
            color: #155724;
        }
        
        .map-container {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            height: 400px;
            background: white;
            margin-bottom: 1.5rem;
        }
        
        .form-select {
            border-radius: 6px;
            padding: 0.5rem 1rem;
            border: 1px solid #ced4da;
            transition: all 0.3s ease;
        }
        
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        
        .btn-action {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 6px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-action:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
        }
        
        .filter-title {
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--dark);
        }
        
        footer {
            background: var(--dark);
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 0.8rem;
        }
        
        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .footer-links a:hover {
            color: white;
            padding-left: 5px;
        }
        
        .social-icons {
            margin-top: 1.5rem;
        }
        
        .social-icons a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            color: white;
            margin-right: 10px;
            transition: all 0.3s ease;
        }
        
        .social-icons a:hover {
            background: var(--primary);
            transform: translateY(-5px);
        }
        
        .dashboard-content {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
        }
        
        .driver-list {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
        }
        
        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }
        
        @media (max-width: 768px) {
            .stats-number {
                font-size: 1.8rem;
            }
            
            .report-image {
                height: 200px;
                margin-bottom: 1rem;
            }
            
            .dashboard-header {
                padding: 1.5rem 0;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <i class="fas fa-recycle me-2"></i>CleanCity
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">Admin Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1>Admin Dashboard</h1>
                    <p class="mb-0">Manage all garbage reports and driver assignments</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="btn-group">
                        <button type="button" class="btn btn-light">
                            <i class="fas fa-user me-1"></i> <?php echo $_SESSION['name']; ?>
                        </button>
                        <a href="../logout.php" class="btn btn-outline-light">
                            <i class="fas fa-sign-out-alt"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="container">
        <!-- Stats Overview -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stats-card card-primary text-center">
                    <div class="stats-icon">
                        <i class="fas fa-trash-alt"></i>
                    </div>
                    <div class="stats-number"><?php echo count($reports); ?></div>
                    <div class="stats-label">Total Reports</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card card-warning text-center">
                    <div class="stats-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stats-number"><?php echo count(array_filter($reports, function($report) { return $report['status'] === 'Pending'; })); ?></div>
                    <div class="stats-label">Pending Reports</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card card-info text-center">
                    <div class="stats-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="stats-number"><?php echo count(array_filter($reports, function($report) { return $report['status'] === 'In Progress'; })); ?></div>
                    <div class="stats-label">In Progress</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card card-success text-center">
                    <div class="stats-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stats-number"><?php echo count(array_filter($reports, function($report) { return $report['status'] === 'Cleaned'; })); ?></div>
                    <div class="stats-label">Cleaned Reports</div>
                </div>
            </div>
        </div>
        
        <!-- Filter Section -->
        <div class="filter-section">
            <h4 class="filter-title"><i class="fas fa-filter me-2"></i>Filter Reports</h4>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <select class="form-select" id="statusFilter">
                        <option value="">All Statuses</option>
                        <option value="Pending">Pending</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Cleaned">Cleaned</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <select class="form-select" id="driverFilter">
                        <option value="">All Drivers</option>
                        <?php foreach ($drivers as $driver): ?>
                            <option value="<?php echo $driver['id']; ?>"><?php echo htmlspecialchars($driver['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <input type="date" class="form-control" id="dateFilter" placeholder="Filter by date">
                </div>
                <div class="col-md-3 mb-2">
                    <button class="btn btn-action w-100" id="applyFilters">
                        <i class="fas fa-filter me-1"></i> Apply Filters
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Reports Section -->
        <div class="row">
            <div class="col-lg-7 mb-4">
                <div class="dashboard-content">
                    <h3 class="section-title"><i class="fas fa-list-alt me-2"></i>All Reports</h3>
                    
                    <?php if (empty($reports)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h4>No reports found</h4>
                            <p class="text-muted">There are no garbage reports in the system yet.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($reports as $index => $report): ?>
                            <div class="report-card" data-status="<?php echo $report['status']; ?>" data-driver="<?php echo $report['driver_id']; ?>" data-date="<?php echo date('Y-m-d', strtotime($report['created_at'])); ?>">
                                <div class="report-header">
                                    <span>Report #<?php echo $index + 1; ?> by <?php echo htmlspecialchars($report['user_name']); ?></span>
                                    <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $report['status'])); ?>">
                                        <?php echo $report['status']; ?>
                                    </span>
                                </div>
                                <div class="report-body">
                                    <div class="row">
                                        <div class="col-md-4 mb-3 mb-md-0">
                                            <div class="report-image">
                                                <?php if (!empty($report['image'])): ?>
                                                    <img src="../uploads/<?php echo $report['image']; ?>" alt="Report Image">
                                                <?php else: ?>
                                                    <i class="fas fa-image text-muted" style="font-size: 2rem;"></i>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <ul class="report-details">
                                                <li>
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    <strong>Location:</strong> <?php echo htmlspecialchars($report['location']); ?>
                                                </li>
                                                <li>
                                                    <i class="fas fa-align-left"></i>
                                                    <strong>Description:</strong> <?php echo htmlspecialchars($report['description']); ?>
                                                </li>
                                                <li>
                                                    <i class="fas fa-user"></i>
                                                    <strong>Driver:</strong> <?php echo !empty($report['driver_name']) ? htmlspecialchars($report['driver_name']) : 'Not Assigned'; ?>
                                                </li>
                                                <li>
                                                    <i class="fas fa-calendar"></i>
                                                    <strong>Reported on:</strong> <?php echo date('M j, Y', strtotime($report['created_at'])); ?>
                                                </li>
                                            </ul>
                                            
                                            <div class="d-flex flex-wrap gap-2 mt-3">
                                                <!-- Status Update Form -->
                                                <form method="POST" action="">
                                                    <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                                                    <div class="input-group">
                                                        <select name="status" class="form-select form-select-sm">
                                                            <option value="Pending" <?php echo $report['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                            <option value="In Progress" <?php echo $report['status'] === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                                                            <option value="Cleaned" <?php echo $report['status'] === 'Cleaned' ? 'selected' : ''; ?>>Cleaned</option>
                                                        </select>
                                                        <button type="submit" name="update_status" value="1" class="btn-action btn-sm">
                                                            Update
                                                        </button>
                                                    </div>
                                                </form>
                                                
                                                <!-- Driver Assignment Form -->
                                                <form method="POST" action="">
                                                    <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                                                    <div class="input-group">
                                                        <select name="driver_id" class="form-select form-select-sm">
                                                            <option value="">Assign Driver</option>
                                                            <?php foreach ($drivers as $driver): ?>
                                                                <option value="<?php echo $driver['id']; ?>" <?php echo $report['driver_id'] == $driver['id'] ? 'selected' : ''; ?>>
                                                                    <?php echo htmlspecialchars($driver['name']); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <button type="submit" name="assign_driver" value="1" class="btn-action btn-sm">
                                                            Assign
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <!-- Pagination (if needed in future) -->
                        <div class="pagination-container">
                            <nav>
                                <ul class="pagination">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                                    </li>
                                    <li class="page-item active">
                                        <a class="page-link" href="#">1</a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">2</a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">3</a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="col-lg-5">
                <!-- Map Section -->
                <div class="dashboard-content">
                    <h3 class="section-title"><i class="fas fa-map-marked-alt me-2"></i>Reports Map</h3>
                    <div id="map" class="map-container"></div>
                </div>
                
                <!-- Driver Availability -->
                <div class="driver-list">
                    <h5 class="mb-3"><i class="fas fa-users me-2"></i>Driver Availability</h5>
                    <?php if (empty($drivers)): ?>
                        <p class="text-muted">No drivers available</p>
                    <?php else: ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($drivers as $driver): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?php echo htmlspecialchars($driver['name']); ?></strong>
                                        <small class="d-block text-muted"><?php echo $driver['assigned_route']; ?></small>
                                    </div>
                                    <span class="badge bg-<?php echo $driver['status'] === 'available' ? 'success' : 'secondary'; ?>">
                                        <?php echo ucfirst($driver['status']); ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h4>CleanCity</h4>
                    <p>Helping to create a cleaner, healthier city through technology and community participation.</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="../index.php">Home</a></li>
                        <li><a href="../index.php#how-it-works">How It Works</a></li>
                        <li><a href="../index.php#about">About</a></li>
                        <li><a href="../index.php#faq">FAQ</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <h5>Services</h5>
                    <ul class="footer-links">
                        <li><a href="../login.php">Login</a></li>
                        <li><a href="../register.php">Register</a></li>
                        <li><a href="../dashboard.php">Dashboard</a></li>
                        <li><a href="../report-form.php">Report Issue</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4 mb-4">
                    <h5>Contact Us</h5>
                    <ul class="footer-links">
                        <li><i class="fas fa-envelope me-2"></i> clean-city@example.com</li>
                        <li><i class="fas fa-phone me-2"></i> (555) 123-CLEAN</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i> 123 Clean Street, Our City</li>
                    </ul>
                </div>
            </div>
            <hr class="mt-4">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; 2023 Smart City Garbage Management System. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-white me-3">Privacy Policy</a>
                    <a href="#" class="text-white">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Initialize map
        var map = L.map('map').setView([0, 0], 2);
        
        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        // Add markers for each report
        <?php foreach ($reports as $report): ?>
            <?php if (!empty($report['latitude']) && !empty($report['longitude'])): ?>
                // Custom icon based on status
                var iconColor;
                switch('<?php echo $report['status']; ?>') {
                    case 'Pending':
                        iconColor = 'orange';
                        break;
                    case 'In Progress':
                        iconColor = 'blue';
                        break;
                    case 'Cleaned':
                        iconColor = 'green';
                        break;
                    default:
                        iconColor = 'red';
                }
                
                var customIcon = L.divIcon({
                    html: `<div style="background-color: ${iconColor}; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 5px rgba(0,0,0,0.5);"></div>`,
                    className: 'custom-div-icon',
                    iconSize: [20, 20],
                    iconAnchor: [10, 10]
                });
                
                var marker = L.marker([<?php echo $report['latitude']; ?>, <?php echo $report['longitude']; ?>], {icon: customIcon}).addTo(map);
                marker.bindPopup(
                    '<div class="p-2">' +
                    '<h6 class="mb-2">Report Details</h6>' +
                    '<p><strong>Location:</strong> <?php echo addslashes($report['location']); ?></p>' +
                    '<p><strong>Status:</strong> <span class="badge bg-' + iconColor + '"><?php echo $report['status']; ?></span></p>' +
                    '<p><strong>Description:</strong> <?php echo addslashes($report['description']); ?></p>' +
                    '<p><strong>Reported by:</strong> <?php echo addslashes($report['user_name']); ?></p>' +
                    '</div>'
                );
            <?php endif; ?>
        <?php endforeach; ?>
        
        // Adjust map view to show all markers
        <?php if (count($reports) > 0): ?>
            var group = new L.featureGroup([
                <?php foreach ($reports as $report): ?>
                    <?php if (!empty($report['latitude']) && !empty($report['longitude'])): ?>
                        L.marker([<?php echo $report['latitude']; ?>, <?php echo $report['longitude']; ?>]),
                    <?php endif; ?>
                <?php endforeach; ?>
            ]);
            map.fitBounds(group.getBounds());
        <?php else: ?>
            // Default view if no reports
            map.setView([20, 0], 2);
        <?php endif; ?>
        
        // Filter functionality
        document.getElementById('applyFilters').addEventListener('click', function() {
            const statusFilter = document.getElementById('statusFilter').value;
            const driverFilter = document.getElementById('driverFilter').value;
            const dateFilter = document.getElementById('dateFilter').value;
            
            document.querySelectorAll('.report-card').forEach(card => {
                const status = card.getAttribute('data-status');
                const driver = card.getAttribute('data-driver');
                const date = card.getAttribute('data-date');
                
                let show = true;
                
                if (statusFilter && status !== statusFilter) {
                    show = false;
                }
                
                if (driverFilter && driver !== driverFilter) {
                    show = false;
                }
                
                if (dateFilter && date !== dateFilter) {
                    show = false;
                }
                
                card.style.display = show ? 'block' : 'none';
            });
        });
    </script>
</body>
</html>