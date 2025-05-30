<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services - Pay360</title>
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
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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

        .main-content {
            flex: 1;
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

        .hero p {
            font-size: 1.2rem;
            margin-top: 1rem;
            opacity: 0.9;
        }

        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        /* Main Content Section */
        .main-section {
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

        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
            justify-items: center;
        }

        .content-card {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            border: 2px solid #e0f2e0;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .content-card:hover {
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
            margin: 0 auto 1.5rem;
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
            margin-bottom: 1rem;
        }

        .card-price {
            color: var(--accent-color);
            font-weight: 600;
            font-size: 1.2rem;
            margin-top: 1rem;
        }

        .card-button {
            background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin-top: 1rem;
        }

        .card-button:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
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

        .feature-description {
            color: #555;
            font-size: 0.95rem;
        }

        .cta-section {
            background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
            color: white;
            padding: 3rem;
            border-radius: 15px;
            text-align: center;
            margin: 2rem 1rem;
        }

        .cta-title {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .cta-description {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            opacity: 0.9;
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
            padding: 1.5rem 0;
            margin-top: auto;
            width: 100%;
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

            .hero p {
                font-size: 1rem;
            }

            .main-section, .features-section, .cta-section {
                margin: -3rem 0.5rem 1rem;
                padding: 2rem;
            }

            .content-grid {
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
                        <li><a href="Home.php">Home</a></li>
                        <li><a href="news.php">News</a></li>
                        <li><a href="announcement.php">Announcement</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
                        <li class="settings">
                            <a href="services.php" class="active">Services</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <div class="main-content">
        <div class="hero">
            <div>
                <h1>Our Financial Services</h1>
                <p>Comprehensive solutions for all your financial needs</p>
            </div>
        </div>

        <div class="container">
            <div class="main-section">
                <h2 class="section-title">Choose Your Service Plan</h2>
                <p class="section-subtitle">We offer a variety of financial services designed to help you achieve your goals and secure your financial future.</p>
                

                                    <div class="content-grid">
                    <div class="content-card">
                        <div class="card-icon">💳</div>
                        <div class="card-title">Coming Soon</div>
                        <div class="card-description">Perfect for individuals starting their financial journey. Includes basic banking services and financial planning tools.</div>
                        <div class="card-price">Custom Pricing</div>
                        <a href="#" class="card-button">Coming Soon</a>
                    </div>

                    <div class="content-card">
                        <div class="card-icon">💼</div>
                        <div class="card-title">All-in Membership</div>
                        <div class="card-description">Advanced financial services with personalized advice, investment options, and priority customer support.</div>
                        <div class="card-price">₱1,500/month</div>
                        <a href="Register.php" class="card-button">Register Now</a>
                    </div>

                    <div class="content-card">
                        <div class="card-icon">🏆</div>
                        <div class="card-title">Coming Soon</div>
                        <div class="card-description">Comprehensive business financial management with corporate accounts, payroll services, and business loans.</div>
                        <div class="card-price">Custom Pricing</div>
                        <a href="#" class="card-button">Coming Soon</a>
                    </div>
                </div>
            </div>

            <div class="cta-section">
                <h2 class="cta-title">Ready to Transform Your Finances?</h2>
                <p class="cta-description">Join thousands of satisfied customers who trust Pay360 for their financial needs. Start your journey today with a free consultation.</p>
                <a href="Register.php" class="cta-button">Get a membership!</a>
            </div>
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

        // Button interactions
        document.querySelectorAll('.card-button, .cta-button').forEach(button => {
            button.addEventListener('click', function(e) {
                if (this.getAttribute('href') !== 'Register.php') {
                    e.preventDefault();
                    const service = this.closest('.content-card')?.querySelector('.card-title')?.textContent || 'service';
                    alert(`Thank you for your interest in ${service}! We'll contact you soon.`);
                }
            });
        });
    </script>
</body>
</html>