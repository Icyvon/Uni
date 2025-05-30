<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Pay360</title>
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

        /* Contact Form Styling */
        .contact-section {
            background-color: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: var(--shadow-md);
            margin: -5rem 1rem 2rem;
            position: relative;
            z-index: 10;
        }

        .contact-form {
            max-width: 600px;
            margin: 0 auto;
        }

        .contact-form h2 {
            color: var(--primary-color);
            font-size: 2rem;
            margin-bottom: 1rem;
            text-align: center;
        }

        .contact-form p {
            text-align: center;
            margin-bottom: 2rem;
            color: #555;
            font-size: 1.1rem;
        }

        .contact-form p a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 600;
        }

        .contact-form p a:hover {
            text-decoration: underline;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
            font-weight: 600;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e0f2e0;
            border-radius: 10px;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            background-color: #f9fffe;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(60, 179, 113, 0.1);
            background-color: white;
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .submit-btn {
            width: 100%;
            background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* Contact Info Section */
        .contact-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .info-card {
            text-align: center;
            padding: 2rem;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-radius: 15px;
            border: 2px solid #e0f2e0;
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
            border-color: var(--accent-color);
        }

        .info-icon {
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

        .info-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .info-text {
            color: #555;
            line-height: 1.6;
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

            .contact-section {
                margin: -3rem 0.5rem 1rem;
                padding: 2rem;
            }

            .contact-info {
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
        <h1>Get in Touch</h1>
    </div>

    <div class="container">
        <div class="contact-section">
            <form class="contact-form" method="POST" action="">
                <h2>Contact Us</h2>
                <p>We'd love to hear from you! Send us a message and we'll respond as soon as possible.</p>
                <p>Email us directly at: <a href="mailto:rainepangilinan11@gmail.com">rainepangilinan11@gmail.com</a></p>
                
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email address" required>
                </div>
                
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" placeholder="What is this about?" required>
                </div>
                
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" placeholder="Tell us how we can help you..." required></textarea>
                </div>
                
                <button type="submit" class="submit-btn">Send Message</button>
            </form>

            <div class="contact-info">
                <div class="info-card">
                    <div class="info-icon">📧</div>
                    <div class="info-title">Email Us</div>
                    <div class="info-text">rainepangilinan11@gmail.com<br>We'll respond within 24 hours</div>
                </div>
                
                <div class="info-card">
                    <div class="info-icon">📞</div>
                    <div class="info-title">Call Us</div>
                    <div class="info-text">+63 123 456 7890<br>Mon-Fri, 9AM-5PM</div>
                </div>
                
                <div class="info-card">
                    <div class="info-icon">📍</div>
                    <div class="info-title">Visit Us</div>
                    <div class="info-text">Malolos, Bulacan State University<br>Bulacan, Philippines</div>
                </div>
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

        // Form submission handling
        document.querySelector('.contact-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(this);
            const name = formData.get('name');
            const email = formData.get('email');
            const subject = formData.get('subject');
            const message = formData.get('message');
            
            // Simple validation
            if (!name || !email || !subject || !message) {
                alert('Please fill in all fields.');
                return;
            }
            
            // Here you would typically send the data to your PHP backend
            // For now, we'll just show a success message
            alert('Thank you for your message! We\'ll get back to you soon.');
            this.reset();
        });
    </script>
</body>
</html>