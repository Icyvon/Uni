<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements - Pay360</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
    :root {
            --primary-color: #2e8b57; /* Main green */
            --accent-color: #3cb371;  /* Soft green */
            --text-color: #333;
            --white: #ffffff;
            --shadow-sm: 0 2px 5px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 8px 20px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--white);
            color: var(--text-color);
            line-height: 1.6;
            padding-top: 50px; 
        }

        header {
            background-color: var(--white);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 100;
            box-shadow: var(--shadow-sm);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
        }

        .logo-wrapper {
            display: flex;
            align-items: center;
        }

        .logo-img {
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }

        .logo-text {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--accent-color);
        }

        /* Navigation styling */
        nav.navigationbar {
            margin-left: auto;
            margin-top: 0;
            padding: 0;
            align-self: center;
            position: relative;
            top: 0;
        }

        nav.navigationbar ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        nav.navigationbar li {
            position: relative;
        }

        nav.navigationbar a {
            color: var(--text-color);
            text-decoration: none;
            padding: 20px 20px;
            display: block;
            font-size: 1.1rem;
            transition: color 0.3s ease;
            font-weight: 500;
        }

        nav.navigationbar a:hover {
            color: var(--accent-color);
        }

        /* Dropdown menu styling */
        nav.navigationbar .dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            background-color: var(--white);
            min-width: 190px;
            box-shadow: var(--shadow-md);
            border-radius: 5px;
            display: none;
            z-index: 1;
        }

        nav.navigationbar .settings:hover .dropdown {
            display: block;
        }

        nav.navigationbar .dropdown li {
            width: 100%;
        }

        nav.navigationbar .dropdown a {
            color: var(--text-color);
            padding: 12px 15px;
            font-size: 1rem;
        }

        nav.navigationbar .dropdown a:hover {
            background-color: rgba(26, 188, 107, 0.08); 
            color: var(--accent-color);
        }

        /* Style for active navigation item */
        nav.navigationbar a.active {
            color: var(--accent-color);
            position: relative;
        }

        nav.navigationbar a.active::after {
            content: '';
            position: absolute;
            bottom: 12px;
            left: 15px;
            right: 15px;
            height: 3px;
            background-color: var(--accent-color);
        }

        .hero {
            position: relative;
            height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
            color: var(--white);
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
        }

        .hero h1 {
            font-size: 2.5rem;
            font-weight: 600;
            animation: fadeIn 1.5s ease-in-out;
        }

        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .announcements-section {
            background-color: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: var(--shadow-md);
            margin: -5rem 1rem 2rem;
            position: relative;
            z-index: 10;
        }

        .section-title {
            color: var(--primary-color);
            font-size: 2rem;
            margin-bottom: 1rem;
            text-align: center;
        }

        .section-subtitle {
            color: #555;
            margin-bottom: 2rem;
            font-size: 1.1rem;
            text-align: center;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .announcement-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .announcement-card {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            border: 2px solid #e0f2e0;
        }

        .announcement-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
            border-color: var(--accent-color);
        }

        .card-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            color: white;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .card-description {
            color: #555;
            line-height: 1.6;
            font-size: 1rem;
        }

        .card-date {
            color: var(--accent-color);
            font-weight: 600;
            font-size: 0.9rem;
            margin-top: 1rem;
        }

        .features-section {
            background-color: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: var(--shadow-md);
            margin: 2rem 1rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .feature-item {
            text-align: center;
            padding: 1.5rem;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .feature-item:hover {
            transform: translateY(-3px);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
            color: white;
        }

        .feature-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .cta-section {
            background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
            color: white;
            padding: 3rem;
            border-radius: 15px;
            text-align: center;
            margin: 2rem 1rem;
        }

        .cta-button {
            background-color: white;
            color: var(--primary-color);
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin-top: 1rem;
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        footer {
            background-color: var(--primary-color);
            color: white;
            text-align: center;
            padding: 1.5rem;
            margin-top: 2rem;
        }

        footer a {
            color: #A5D6A7;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        footer a:hover {
            color: white;
        }

        @media (max-width: 768px) {
            body {
                padding-top: 120px;
            }

            .header-container {
                flex-direction: column;
                padding: 10px 0;
            }

            nav.navigationbar {
                margin-top: 20px;
                width: 100%;
            }

            nav.navigationbar ul {
                flex-wrap: wrap;
                justify-content: center;
            }

            nav.navigationbar li {
                margin: 5px 0;
            }

            nav.navigationbar a {
                padding: 8px 12px;
                font-size: 1rem;
            }

            nav.navigationbar .dropdown {
                position: static;
                display: none;
                width: 100%;
                box-shadow: none;
                background-color: rgba(255, 255, 255, 0.1);
            }

            nav.navigationbar .settings.active .dropdown {
                display: block;
            }

            .hero h1 {
                font-size: 1.8rem;
            }

            .announcements-section, .features-section, .cta-section {
                margin: -3rem 0.5rem 1rem;
                padding: 2rem;
            }

            .announcement-grid {
                grid-template-columns: 1fr;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
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

    <div class="hero">
        <h1>Latest Announcements</h1>
    </div>

    <div class="container">
        <div class="announcements-section">
            <h2 class="section-title">Important Updates</h2>
            <p class="section-subtitle">Stay informed with our latest developments and exciting new offerings that will enhance your cooperative experience</p>
            
                <div class="announcement-card">
                    <div class="card-icon">💰</div>
                    <h3 class="card-title">Enhanced Loan Services</h3>
                    <p class="card-description">Introducing our expanded loan portfolio with competitive interest rates, flexible repayment terms, and faster approval processes. Whether it's for business expansion, education, or personal needs, we've streamlined our services to better serve you.</p>
                    <div class="card-date">Effective: April 1, 2025</div>
                </div>
                
                <div class="announcement-card">
                    <div class="card-icon">🏆</div>
                    <h3 class="card-title">Member Rewards Program</h3>
                    <p class="card-description">Join our new loyalty program and earn points on every transaction. Redeem points for cashback, discounts on services, and exclusive member benefits. The more you engage with Pay360, the more rewards you'll earn!</p>
                    <div class="card-date">Coming Soon: May 2025</div>
                </div>
                
                <div class="announcement-card">
                    <div class="card-icon">🎓</div>
                    <h3 class="card-title">Financial Literacy Program</h3>
                    <p class="card-description">We're launching a comprehensive financial literacy program for our members. Join our free workshops and online courses to improve your financial knowledge and make better decisions for your future.</p>
                    <div class="card-date">Starting: June 2025</div>
                </div>
                
                <div class="announcement-card">
                    <div class="card-icon">🌟</div>
                    <h3 class="card-title">Branch Expansion</h3>
                    <p class="card-description">Pay360 is expanding! We're opening three new branches to serve you better. Visit our new locations for all your cooperative banking needs and experience our personalized service closer to your community.</p>
                    <div class="card-date">Opening: July 2025</div>
                </div>
            </div>
        </div>

        <div class="features-section">
            <h2 class="section-title">Why Choose Pay360?</h2>
            <p class="section-subtitle">Discover the advantages of being part of our growing cooperative community</p>
            
            <div class="features-grid">
                <div class="feature-item">
                    <div class="feature-icon">🔒</div>
                    <h3 class="feature-title">Secure & Reliable</h3>
                    <p>Bank-grade security measures protect your assets and personal information</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">👥</div>
                    <h3 class="feature-title">Community Focused</h3>
                    <p>Member-owned cooperative committed to serving our community's needs</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">📱</div>
                    <h3 class="feature-title">Digital Innovation</h3>
                    <p>Cutting-edge technology for convenient 24/7 access to your accounts</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">💎</div>
                    <h3 class="feature-title">Competitive Rates</h3>
                    <p>Better rates on savings and loans compared to traditional banks</p>
                </div>
            </div>
        </div>

        <div class="cta-section">
            <h2>Ready to Join Pay360?</h2>
            <p>Become a member today and experience the difference of cooperative banking</p>
            <a href="Register.php" class="cta-button">Get Started</a>
        </div>
    </div>

    <footer>
        <p>© 2025 Pay360. All rights reserved. | <a href="contact.php">Contact Us</a></p>
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