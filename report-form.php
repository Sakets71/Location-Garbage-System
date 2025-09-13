<?php
include 'includes/auth.php';
requireLogin();
include 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = getUserId();
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $location = trim($_POST['location']);
    $description = trim($_POST['description']);
    
    // Handle file upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $file_extension;
        $destination = $upload_dir . $filename;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
            $image = $filename;
        }
    }
    
    // Validate inputs
    $errors = [];
    
    if (empty($location)) {
        $errors[] = 'Location is required.';
    }
    
    if (empty($description)) {
        $errors[] = 'Description is required.';
    }
    
    // If no errors, save report
    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO reports (user_id, latitude, longitude, location, description, image) VALUES (:user_id, :latitude, :longitude, :location, :description, :image)");
        $stmt->execute([
            'user_id' => $user_id,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'location' => $location,
            'description' => $description,
            'image' => $image
        ]);
        
        $_SESSION['success'] = 'Report submitted successfully.';
        header("Location: dashboard.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Garbage - Smart Garbage Management</title>
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
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            overflow-x: hidden;
        }
        
        .navbar {
            padding: 1rem 0;
            transition: all 0.3s ease;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.8rem;
        }
        
        .navbar-dark .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }
        
        .navbar-dark .navbar-nav .nav-link:hover {
            color: #fff;
            transform: translateY(-2px);
        }
        
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('https://images.unsplash.com/photo-1611273426858-450d8e3c9fce?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            padding: 7rem 0;
            color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .report-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }
        
        .report-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .report-header {
            background: var(--primary);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .report-form {
            padding: 2rem;
        }
        
        /* Form control styling */
        .form-control {
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            border: 1px solid #e1e5eb;
            margin-bottom: 1.5rem;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        
        textarea.form-control {
            border-radius: 15px;
            min-height: 120px;
        }
        
        /* Input group styling */
        .input-group {
            display: flex;
            align-items: stretch;
        }
        
        .input-group-text {
            background-color: #f8f9fa;
            border: 1px solid #e1e5eb;
            padding: 0.8rem 1rem;
            border-radius: 50px 0 0 50px;
            border-right: none;
        }
        
        .input-group .form-control {
            border-radius: 0 50px 50px 0;
            margin-bottom: 0;
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            padding: 0.8rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        /* Label styling */
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark);
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
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate {
            animation: fadeIn 1s ease forwards;
        }
        
        .delay-1 { animation-delay: 0.2s; }
        .delay-2 { animation-delay: 0.4s; }
        .delay-3 { animation-delay: 0.6s; }
        
        /* File upload styling */
        .file-upload {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }
        
        .file-upload-btn {
            border: 2px dashed #e1e5eb;
            color: #6c757d;
            background-color: #f8f9fa;
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .file-upload-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
        }
        
        .file-upload-input {
            position: absolute;
            top: 0;
            right: 0;
            margin: 0;
            padding: 0;
            font-size: 20px;
            cursor: pointer;
            opacity: 0;
            filter: alpha(opacity=0);
            height: 100%;
            width: 100%;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .hero-section {
                padding: 6rem 0;
            }
            
            .input-group-text {
                padding: 0.8rem 0.8rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
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
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Report Form -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-8">
                    <div class="report-container animate">
                        <div class="report-header">
                            <h3><i class="fas fa-trash-alt me-2"></i>Report Garbage</h3>
                            <p class="mb-0">Help keep our city clean by reporting garbage issues</p>
                        </div>
                        <div class="report-form">
                            <?php if (!empty($errors)): ?>
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <?php foreach ($errors as $error): ?>
                                            <li><?php echo $error; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            
                            <form method="POST" action="" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="location" class="form-label">Location</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <input type="text" class="form-control" id="location" name="location" required
                                            placeholder="Enter the location of the garbage issue">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3" required
                                        placeholder="Describe the garbage issue in detail"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="image" class="form-label">Upload Photo</label>
                                    <div class="file-upload">
                                        <div class="file-upload-btn">
                                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                            <p class="mb-1">Click to upload or drag and drop</p>
                                            <p class="small">PNG, JPG, GIF up to 10MB</p>
                                        </div>
                                        <input type="file" class="file-upload-input" id="image" name="image" accept="image/*">
                                    </div>
                                    <div id="file-name" class="small mt-2 text-center"></div>
                                </div>
                                <div class="mb-3">
                                    <button type="button" class="btn btn-outline-primary" id="get-location">
                                        <i class="fas fa-location-arrow me-2"></i>Get Current Location
                                    </button>
                                    <small class="text-muted ms-2" id="location-status">Location not yet retrieved</small>
                                </div>
                                <input type="hidden" id="latitude" name="latitude">
                                <input type="hidden" id="longitude" name="longitude">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-paper-plane me-2"></i>Submit Report
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
        // Navbar background change on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(52, 58, 64, 0.95)';
                navbar.style.padding = '0.5rem 0';
            } else {
                navbar.style.background = 'transparent';
                navbar.style.padding = '1rem 0';
            }
        });
        
        // Animation on scroll
        document.addEventListener('DOMContentLoaded', function() {
            const animatedElements = document.querySelectorAll('.animate');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = 1;
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });
            
            animatedElements.forEach(element => {
                element.style.opacity = 0;
                element.style.transform = 'translateY(20px)';
                element.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
                observer.observe(element);
            });
        });
        
        // File upload display
        document.getElementById('image').addEventListener('change', function(e) {
            const fileName = e.target.files[0].name;
            document.getElementById('file-name').textContent = 'Selected file: ' + fileName;
        });
        
        // Get location functionality
        document.getElementById('get-location').addEventListener('click', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        document.getElementById('latitude').value = position.coords.latitude;
                        document.getElementById('longitude').value = position.coords.longitude;
                        document.getElementById('location-status').textContent = 'Location retrieved successfully';
                        document.getElementById('location-status').className = 'text-success ms-2';
                        
                        // Reverse geocoding to get address (using Nominatim API)
                        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${position.coords.latitude}&lon=${position.coords.longitude}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.display_name) {
                                    document.getElementById('location').value = data.display_name;
                                }
                            })
                            .catch(error => {
                                console.error('Error getting address:', error);
                            });
                    },
                    function(error) {
                        document.getElementById('location-status').textContent = 'Error getting location: ' + error.message;
                        document.getElementById('location-status').className = 'text-danger ms-2';
                    }
                );
            } else {
                document.getElementById('location-status').textContent = 'Geolocation is not supported by this browser';
                document.getElementById('location-status').className = 'text-danger ms-2';
            }
        });
    </script>
</body>
</html>