<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News & Updates - Pay360</title>
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

        .hero p {
            font-size: 1.2rem;
            margin-top: 1rem;
            opacity: 0.9;
        }

        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        /* Main News Section */
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

        /* Filter Tabs */
        .filter-tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .filter-tab {
            background: transparent;
            border: 2px solid var(--accent-color);
            color: var(--accent-color);
            padding: 0.8rem 1.5rem;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .filter-tab.active,
        .filter-tab:hover {
            background: var(--accent-color);
            color: white;
        }

        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .news-card {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-radius: 15px;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            border: 2px solid #e0f2e0;
            overflow: hidden;
        }

        .news-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
            border-color: var(--accent-color);
        }

        .news-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
        }

        .news-content {
            padding: 2rem;
        }

        .news-category {
            background: var(--accent-color);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .news-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 1rem;
            line-height: 1.4;
        }

        .news-excerpt {
            color: #555;
            line-height: 1.6;
            font-size: 0.95rem;
            margin-bottom: 1rem;
        }

        .news-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .news-date {
            color: var(--accent-color);
            font-weight: 600;
            font-size: 0.9rem;
        }

        .news-author {
            color: #777;
            font-size: 0.9rem;
        }

        .read-more-btn {
            background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .read-more-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* Featured News Section */
        .featured-section {
            background-color: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: var(--shadow-md);
            margin: 2rem 1rem;
        }

        .featured-news {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
            align-items: center;
        }

        .featured-image {
            width: 100%;
            height: 250px;
            background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: white;
        }

        .featured-content {
            padding: 1rem;
        }

        .featured-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .featured-excerpt {
            color: #555;
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        /* Newsletter Section */
        .newsletter-section {
            background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
            color: white;
            padding: 3rem;
            border-radius: 15px;
            text-align: center;
            margin: 2rem 1rem;
        }

        .newsletter-title {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .newsletter-description {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .newsletter-form {
            display: flex;
            max-width: 400px;
            margin: 0 auto;
            gap: 1rem;
        }

        .newsletter-input {
            flex: 1;
            padding: 1rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
        }

        .newsletter-btn {
            background-color: white;
            color: var(--primary-color);
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .newsletter-btn:hover {
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

            .hero p {
                font-size: 1rem;
            }

            .main-section, .featured-section, .newsletter-section {
                margin: -3rem 0.5rem 1rem;
                padding: 2rem;
            }

            .news-grid {
                grid-template-columns: 1fr;
            }

            .featured-news {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .newsletter-form {
                flex-direction: column;
            }

            .filter-tabs {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <<header>
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
        <div>
            <h1>Latest News & Updates</h1>
            <p>Stay informed with the latest financial news and Pay360 updates</p>
        </div>
    </div>

    <div class="container">
        <div class="main-section">
            <h2 class="section-title">Recent News</h2>
            <p class="section-subtitle">Discover the latest developments in finance, technology, and Pay360 platform updates to stay ahead of the curve.</p>
            
            <div class="filter-tabs">
                <button class="filter-tab active" data-category="all">All News</button>
                <button class="filter-tab" data-category="finance">Finance</button>
                <button class="filter-tab" data-category="technology">Technology</button>
                <button class="filter-tab" data-category="company">Company</button>
                <button class="filter-tab" data-category="market">Market</button>
            </div>
            
            <div class="news-grid">
                <article class="news-card" data-category="finance">
                    <div class="news-image">📈</div>
                    <div class="news-content">
                        <span class="news-category">Finance</span>
                        <h3 class="news-title">Philippine Peso Strengthens Against Major Currencies</h3>
                        <p class="news-excerpt">The Philippine peso has shown significant strength this quarter, benefiting local businesses and international trade partnerships...</p>
                        <div class="news-meta">
                            <span class="news-date">May 20, 2025</span>
                            <span class="news-author">By Finance Team</span>
                        </div>
                        <a href="#" class="read-more-btn">Read More</a>
                    </div>
                </article>

                <article class="news-card" data-category="company">
                    <div class="news-image">🏆</div>
                    <div class="news-content">
                        <span class="news-category">Company</span>
                        <h3 class="news-title">Pay360 Wins Best Fintech Innovation Award</h3>
                        <p class="news-excerpt">We're proud to announce that Pay360 has been recognized as the Best Fintech Innovation company at the 2025 Asia Finance Awards...</p>
                        <div class="news-meta">
                            <span class="news-date">May 15, 2025</span>
                            <span class="news-author">By PR Team</span>
                        </div>
                        <a href="#" class="read-more-btn">Read More</a>
                    </div>
                </article>

                <article class="news-card" data-category="market">
                    <div class="news-image">💹</div>
                    <div class="news-content">
                        <span class="news-category">Market</span>
                        <h3 class="news-title">Digital Banking Adoption Surges in Southeast Asia</h3>
                        <p class="news-excerpt">Recent studies show a 150% increase in digital banking adoption across Southeast Asia, with the Philippines leading the charge...</p>
                        <div class="news-meta">
                            <span class="news-date">May 12, 2025</span>
                            <span class="news-author">By Market Analysis</span>
                        </div>
                        <a href="#" class="read-more-btn">Read More</a>
                    </div>
                </article>

                <article class="news-card" data-category="finance">
                    <div class="news-image">💰</div>
                    <div class="news-content">
                        <span class="news-category">Finance</span>
                        <h3 class="news-title">New Government Policies Support Financial Inclusion</h3>
                        <p class="news-excerpt">The government has announced new policies aimed at improving financial inclusion and supporting fintech companies...</p>
                        <div class="news-meta">
                            <span class="news-date">May 10, 2025</span>
                            <span class="news-author">By Policy Team</span>
                        </div>
                        <a href="#" class="read-more-btn">Read More</a>
                    </div>
                </article>

                <article class="news-card" data-category="technology">
                    <div class="news-image">🔐</div>
                    <div class="news-content">
                        <span class="news-category">Technology</span>
                        <h3 class="news-title">Enhanced Security Measures Implemented</h3>
                        <p class="news-excerpt">Pay360 has implemented advanced biometric authentication and AI-powered fraud detection to ensure maximum account security...</p>
                        <div class="news-meta">
                            <span class="news-date">May 08, 2025</span>
                            <span class="news-author">By Security Team</span>
                        </div>
                        <a href="#" class="read-more-btn">Read More</a>
                    </div>
                </article>
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

        // Filter functionality
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                // Add active class to clicked tab
                this.classList.add('active');
                
                const category = this.getAttribute('data-category');
                const newsCards = document.querySelectorAll('.news-card');
                
                newsCards.forEach(card => {
                    if (category === 'all' || card.getAttribute('data-category') === category) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Newsletter subscription
        document.querySelector('.newsletter-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('.newsletter-input').value;
            if (email) {
                alert('Thank you for subscribing! You\'ll receive our latest updates at ' + email);
                this.reset();
            }
        });

        // Read more button functionality
        document.querySelectorAll('.read-more-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const title = this.closest('.news-card, .featured-content').querySelector('.news-title, .featured-title').textContent;
                alert('Opening article: "' + title + '"');
            });
        });
    </script>
</body>
</html>