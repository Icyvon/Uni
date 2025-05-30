<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="home.css">
    <title>Home</title>
</head>

<body>
    <header>
        <div class="container">
            <div class="header-container">
                <div class="logo-wrapper">
                    <img src="logo.png" alt="PAY360 Logo" class="logo-img">
                    <div class="logo-text">PAY360</div>
                </div>
                
                <nav class="navigationbar">
                    <ul>
                        <li><a href="Home.php" class="active">Home</a></li>
                        <li><a href="news.php">News</a></li>
                        <li><a href="announcement.php">Announcement</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
                        <li class="settings">
                            <a href="services.php">Services</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <section class="hero">
                <div class="hero-content">
                    <h1 class="hero-title">Complete Control Over Your Cash Flow</h1>
                    <h2 class="hero-subtitle">Seamless Payments, Simplified.</h2>
                    <p class="hero-text">
                        Effortlessly manage your finances with our intuitive platform. 
                        Reduce complexity, increase efficiency, and focus on what matters most – 
                        guiding you through your financial success.
                    </p>
                    <div class="btn-container">
                        <button class="btn btn-primary" onclick="location.href='Login.php'">Log In</button>
                        <button class="btn btn-secondary" onclick="location.href='Register.php'">Register Now</button>
                    </div>
                </div>
                
                <div class="hero-image">
                    <img src="finance.jpg" alt="Finance" class="hero-img" onerror="this.src='https://via.placeholder.com/600x400?text=Image+Not+Found';">
                    <div class="stat-card">
                        <div class="card-icon">✓</div>
                        <div class="card-text">
                            <span>Trusted by</span>
                            <strong>10,000+ Users</strong>
                        </div>
                    </div>
                </div>
            </section>
            
            <section class="features">
                <h2 class="section-title">Why Choose PAY360?</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">💵</div>
                        <h3 class="feature-title">Easy Payments</h3>
                        <p class="feature-text">
                            Process payments quickly and securely with our advanced platform that simplifies every transaction.
                        </p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">📊</div>
                        <h3 class="feature-title">Financial Analytics</h3>
                        <p class="feature-text">
                            Get valuable insights on your spending patterns and financial health with detailed reports.
                        </p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">🔒</div>
                        <h3 class="feature-title">Secure Platform</h3>
                        <p class="feature-text">
                            Your financial data is protected with enterprise-grade encryption and security protocols.
                        </p>
                    </div>
                </div>
            </section>
            
            <section class="testimonials">
                <h2 class="section-title">What Our Users Say</h2>
                <div class="testimonial-card">
                    <p class="testimonial-text">
                        PAY360 has transformed how I manage my business finances. The platform is intuitive and has saved me countless hours on payment processing.
                    </p>
                    <div class="testimonial-author">
                        <div>
                            <div class="author-name">Sarah Johnson</div>
                            <div class="author-title">Small Business Owner</div>
                        </div>
                    </div>
                </div>
            </section>
            
            <section class="cta">
                <div class="cta-content">
                    <h2 class="cta-title">Ready to Take Control?</h2>
                    <p class="cta-text">
                        Join thousands of satisfied users who've transformed their financial management with PAY360.
                    </p>
                    <div class="btn-container">
                        <button class="btn btn-primary" onclick="location.href='Login.php'">Log In</button>
                        <button class="btn btn-outline" onclick="location.href='Register.php'">Register Now</button>
                    </div>
                </div>
            </section>
        </div>
    </main>
    
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-about">
                    <div class="footer-logo">
                        <img src="logo.png" alt="PAY360 Logo">
                        PAY360
                    </div>
                    <p class="footer-text">
                        PAY360 provides seamless payment solutions to help you manage your finances efficiently and achieve your financial goals.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link">f</a>
                        <a href="#" class="social-link">in</a>
                        <a href="#" class="social-link">ig</a>
                        <a href="#" class="social-link">tw</a>
                    </div>
                </div>
                
                <div class="footer-links">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">News</a></li>
                        <li><a href="#">Announcements</a></li>
                        <li><a href="#">About Us</a></li>
                    </ul>
                </div>
                
                <div class="footer-links">
                    <h4>Services</h4>
                    <ul>
                        <li><a href="#">Membership</a></li>
                        <li><a href="#">Loans</a></li>
                        <li><a href="#">Payment Processing</a></li>
                        <li><a href="#">Others</a></li>
                    </ul>
                </div>
                
                <div class="footer-links">
                    <h4>Contact</h4>
                    <ul>
                        <li>support@pay360.com</li>
                        <li>+1 (555) 123-4567</li>
                        <li>123 Finance Street, City</li>
                    </ul>
                </div>
            </div>
            
            <div class="copyright">
                © 2025 PAY360. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        // Mobile dropdown toggle
        document.addEventListener('DOMContentLoaded', function() {
            const settingsItem = document.querySelector('.settings');
            if (window.innerWidth <= 768) {
                settingsItem.addEventListener('click', function(e) {
                    if (e.target.tagName === 'A' && e.target.nextElementSibling) {
                        e.preventDefault();
                        this.classList.toggle('active');
                    }
                });
            }
        });
    </script>
</body>
</html>