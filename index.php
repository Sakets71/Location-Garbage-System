<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Garbage Management - Keep Our City Clean</title>
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
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        
        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            font-weight: 300;
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
        
        .btn-outline-light {
            padding: 0.8rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-light:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 3rem;
            position: relative;
        }
        
        .section-title:after {
            content: '';
            display: block;
            width: 60px;
            height: 4px;
            background: var(--primary);
            margin: 15px auto 0;
            border-radius: 2px;
        }
        
        .step-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
            border: none;
        }
        
        .step-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .step-number {
            width: 60px;
            height: 60px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            font-weight: bold;
            margin: 0 auto 20px;
        }
        
        .step-icon {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 1.5rem;
        }
        
        .city-info-section {
            background: linear-gradient(to right, #f8f9fa, #e9ecef);
            padding: 5rem 0;
        }
        
        .impact-stats {
            font-size: 3rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .faq-section {
            background: white;
            padding: 5rem 0;
        }
        
        .accordion-item {
            border: none;
            border-radius: 10px;
            margin-bottom: 1rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        
        .accordion-button {
            background: white;
            font-weight: 600;
            padding: 1.5rem;
            border: none;
            box-shadow: none;
        }
        
        .accordion-button:not(.collapsed) {
            background: var(--primary);
            color: white;
        }
        
        .accordion-body {
            padding: 1.5rem;
        }
        
        .cta-section {
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), 
                        url('https://images.unsplash.com/photo-1587334274527-ba54f0b5a357?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            padding: 6rem 0;
            color: white;
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
        .delay-4 { animation-delay: 0.8s; }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .impact-stats {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-recycle me-2"></i>CleanCity
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#how-it-works">How It Works</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#faq">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-primary" href="register.php">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="hero-title animate">Keep Our City Clean & Beautiful</h1>
                    <p class="hero-subtitle animate delay-1">Report garbage issues in your area and help make our city a better place to live</p>
                    <div class="mt-4 animate delay-2">
                        <a href="register.php" class="btn btn-primary me-3">Report an Issue</a>
                        <a href="#how-it-works" class="btn btn-outline-light">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How to Report Section -->
    <section id="how-it-works" class="py-5">
        <div class="container py-5">
            <h2 class="section-title text-center">How It Works</h2>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card step-card animate">
                        <div class="card-body text-center p-4">
                            <div class="step-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <h4>Create an Account</h4>
                            <p>Register on our platform to get started. It only takes a minute!</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card step-card animate delay-1">
                        <div class="card-body text-center p-4">
                            <div class="step-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h4>Locate the Issue</h4>
                            <p>Use our automatic GPS detection or manually enter the location.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card step-card animate delay-2">
                        <div class="card-body text-center p-4">
                            <div class="step-icon">
                                <i class="fas fa-camera"></i>
                            </div>
                            <h4>Provide Details</h4>
                            <p>Add a description and upload a photo to help our team understand the issue.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card step-card animate delay-3">
                        <div class="card-body text-center p-4">
                            <div class="step-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h4>Submit & Track</h4>
                            <p>Submit your report and track its status through your personal dashboard.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5 animate delay-4">
                <a href="register.php" class="btn btn-primary btn-lg">Get Started Now</a>
            </div>
        </div>
    </section>

    <!-- City Information Section -->
    <section id="about" class="city-info-section">
        <div class="container">
            <h2 class="section-title text-center">Our Clean City Initiative</h2>
            
            <div class="row align-items-center mb-5">
                <div class="col-lg-6">
                    <h3>Working Together for a Cleaner Environment</h3>
                    <p>Our city is dedicated to maintaining clean public spaces through innovative technology and community participation. We believe that a clean environment contributes to better health, tourism, and quality of life for all residents.</p>
                    <p>Through this platform, we've empowered citizens to take an active role in keeping our city beautiful. Your reports help us respond faster and allocate resources more efficiently.</p>
                    <div class="mt-4">
                        <a href="#" class="btn btn-outline-dark">Learn About Our Initiatives</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1611273426858-450d8e3c9fce?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Clean City Park" class="img-fluid rounded shadow">
                </div>
            </div>
            
            <div class="row mt-5">
                <div class="col-md-4 mb-4">
                    <div class="stat-card animate">
                        <div class="impact-stats">5,000+</div>
                        <p class="lead">Reports Resolved</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="stat-card animate delay-1">
                        <div class="impact-stats">92%</div>
                        <p class="lead">Satisfaction Rate</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="stat-card animate delay-2">
                        <div class="impact-stats">24h</div>
                        <p class="lead">Average Response Time</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="faq-section">
        <div class="container">
            <h2 class="section-title text-center">Frequently Asked Questions</h2>
            
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item animate">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                            What types of garbage should I report?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            You should report any accumulated waste in public areas, illegal dumping, overflowing bins, or any other garbage-related issues that need attention from our sanitation team.
                        </div>
                    </div>
                </div>
                <div class="accordion-item animate delay-1">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                            How long does it take to resolve a report?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Most reports are addressed within 24-48 hours. The time may vary depending on the severity of the issue and current workload of our sanitation teams.
                        </div>
                    </div>
                </div>
                <div class="accordion-item animate delay-2">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                            Is my personal information secure?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes, we take privacy seriously. Your personal information is protected and only used for managing garbage reports. Location data is only used to address the specific issues you report.
                        </div>
                    </div>
                </div>
                <div class="accordion-item animate delay-3">
                    <h2 class="accordion-header" id="headingFour">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">
                            Can I report issues anonymously?
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Currently, you need to create an account to submit reports. This helps us prevent spam and allows you to track the status of your reports. However, your identity is not shared publicly.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section">
        <div class="container text-center">
            <h2 class="mb-4">Ready to Make a Difference?</h2>
            <p class="lead mb-4">Join thousands of residents who are helping keep our city clean and beautiful.</p>
            <a href="register.php" class="btn btn-primary btn-lg">Report Garbage Now</a>
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
                        <li><a href="#">Home</a></li>
                        <li><a href="#how-it-works">How It Works</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#faq">FAQ</a></li>
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
    </script>
</body>
</html>