<?php
include 'includes/auth.php';
requireLogin();
include 'includes/functions.php';

$user_id = getUserId();
$reports = getUserReports($user_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Smart Garbage Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('https://images.unsplash.com/photo-1611273426858-450d8e3c9fce?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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
        }
        
        .welcome-text {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        .stats-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: none;
            height: 100%;
            padding: 1.5rem;
            text-align: center;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
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
        
        .card-success .stats-icon {
            color: var(--success);
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary);
            display: inline-block;
            color: white;
        }
        
        .report-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 1.5rem;
            border: none;
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .report-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
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
            height: 150px;
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
        
        .btn-primary {
            background: linear-gradient(120deg, var(--primary), var(--accent));
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .no-reports {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        
        .no-reports i {
            font-size: 30px;
            color: #dee2e6;
            margin-bottom: 1rem;
            padding: 1rem;
        }
        footer {
            background: var(--dark);
            color: white;
            padding: 4rem 0 2rem;
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
        
        @media (max-width: 768px) {
            .stats-number {
                font-size: 1.8rem;
            }
            
            .report-image {
                height: 200px;
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-recycle me-2"></i>CleanCity
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                      <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
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
                    <h1>User Dashboard</h1>
                    <p class="welcome-text">Welcome back, <?php echo $_SESSION['name']; ?>! Here's an overview of your reports.</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="report-form.php" class="btn btn-light">
                        <i class="fas fa-plus me-1"></i> New Report
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="container">
        <!-- Stats Overview -->
        <div class="row mb-5">
            <div class="col-md-4 mb-4">
                <div class="stats-card card-primary">
                    <div class="stats-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="stats-number"><?php echo count($reports); ?></div>
                    <div class="stats-label">Total Reports</div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="stats-card card-warning">
                    <div class="stats-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stats-number"><?php echo count(array_filter($reports, function($report) { return $report['status'] === 'Pending'; })); ?></div>
                    <div class="stats-label">Pending Reports</div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="stats-card card-success">
                    <div class="stats-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stats-number"><?php echo count(array_filter($reports, function($report) { return $report['status'] === 'Cleaned'; })); ?></div>
                    <div class="stats-label">Cleaned Reports</div>
                </div>
            </div>
        </div>
        
        <!-- Reports Section -->
        <div class="row">
            <div class="col-lg-12">
                <h3 class="section-title">My Reports</h3>
                
                <?php if (empty($reports)): ?>
                    <div class="no-reports">
                        <i class="fas fa-clipboard-list"></i>
                        <h4>No reports submitted yet</h4>
                        <p class="text-muted mb-4">You haven't submitted any garbage reports yet. Get started by reporting your first issue.</p>
                        <a href="report-form.php" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Report Garbage
                        </a>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($reports as $index => $report): ?>
                            <div class="col-lg-6 mb-4">
                                <div class="report-card">
                                    <div class="report-header">
                                        <span>Report #<?php echo $index + 1; ?></span>
                                        <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $report['status'])); ?>">
                                            <?php echo $report['status']; ?>
                                        </span>
                                    </div>
                                    <div class="report-body">
                                        <div class="report-image mb-3">
                                            <?php if (!empty($report['image'])): ?>
                                                <img src="uploads/<?php echo $report['image']; ?>" alt="Report Image">
                                            <?php else: ?>
                                                <i class="fas fa-image text-muted" style="font-size: 3rem;"></i>
                                            <?php endif; ?>
                                        </div>
                                        
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
                                                <i class="fas fa-calendar"></i>
                                                <strong>Reported on:</strong> <?php echo date('M j, Y', strtotime($report['created_at'])); ?>
                                            </li>
                                            <li>
                                                <i class="fas fa-clock"></i>
                                                <strong>Last updated:</strong> <?php echo date('M j, Y', strtotime($report['updated_at'])); ?>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
     <!-- Footer -->
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
                        <li><a href="index.php">Home</a></li>
                        <li><a href="index.php#how-it-works">How It Works</a></li>
                        <li><a href="index.php#about">About</a></li>
                        <li><a href="index.php#faq">FAQ</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <h5>Services</h5>
                    <ul class="footer-links">
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                        <li><a href="dashboard.php">Dashboard</a></li>
                        <li><a href="report-form.php">Report Issue</a></li>
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
    <script>
        // Simple animation for cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.stats-card, .report-card');
            
            cards.forEach((card, index) => {
                card.style.opacity = 0;
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                
                setTimeout(() => {
                    card.style.opacity = 1;
                    card.style.transform = 'translateY(0)';
                }, 100 + (index * 100));
            });
        });
    </script>
</body>
</html>