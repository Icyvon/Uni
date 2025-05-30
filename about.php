<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Pay360</title>
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
    background: linear-gradient(135deg, #e6f9e6, #d0f5e0);
    color: var(--text-color);
    overflow-x: hidden;
    padding-top: 60px;
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
    font-size: 3rem;
    font-weight: 600;
    animation: fadeIn 1.5s ease-in-out;
}

.hero p {
    font-size: 1.2rem;
    margin-top: 1rem;
    animation: fadeIn 2s ease-in-out;
}

@keyframes fadeIn {
    0% { opacity: 0; transform: translateY(20px); }
    100% { opacity: 1; transform: translateY(0); }
}
.about-section {
    background-color: var(--white);
    padding: 3rem;
    border-radius: 15px;
    box-shadow: var(--shadow-md);
    margin: -2rem 1rem 1rem; /* Lowered the container by increasing the top margin */
    position: relative;
    z-index: 10;
}

.about-section h2 {
    color: var(--primary-color);
    font-size: 2rem;
    margin-bottom: 1.5rem;
    text-align: center;
}

.about-section p {
    line-height: 1.8;
    font-size: 1.1rem;
    color: #555;
    text-align: center;
}

.team-section {
    padding: 3rem 1rem;
    text-align: center;
}

.team-section h2 {
    color: var(--primary-color);
    font-size: 2rem;
    margin-bottom: 2rem;
}

.team {
    display: flex;
    flex-wrap: wrap;
    gap: 2rem;
    justify-content: center;
}

.team-member {
    background-color: #f9f9f9;
    padding: 1.5rem;
    border-radius: 15px;
    text-align: center;
    width: 250px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.team-member:hover {
    transform: translateY(-10px);
    box-shadow: var(--shadow-lg);
}

.team-member h3 {
    color: var(--primary-color);
    margin-bottom: 0.5rem;
    font-size: 1.3rem;
}

.team-member p {
    color: var(--accent-color);
    font-size: 1rem;
}

footer {
    background-color: var(--primary-color);
    color: var(--white);
    text-align: center;
    padding: 1.5rem;
    margin-top: 2rem;
}

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

footer a {
    color: var(--accent-color);
    text-decoration: none;
    transition: color 0.3s ease;
}

footer a:hover {
    color: var(--white);
}

/* Responsive Design */
@media (max-width: 768px) {
    body {
        padding-top: 100px;
    }

    .header-container {
        flex-direction: column;
        padding: 5px 0;
    }

    nav.navigationbar {
        margin-top: 10px;
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
        font-size: 2rem;
    }

    .hero p {
        font-size: 1rem;
    }

    .about-section {
        margin: -3rem 0.5rem 1rem;
        padding: 2rem;
    }

    .team-member {
        width: 100%;
        max-width: 300px;
    }
}

    </style>
</head>
<body>
    <header>
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
        <div>
            <h1>Welcome to Pay360</h1>
            <p>Innovating the Future of System Management</p>
        </div>
    </div>

    <div class="container">
        <div class="about-section">
            <h2>Our Story</h2>
            <p> Pay360 is a cutting-edge multipurpose system management platform designed to simplify operations and boost productivity. Since our founding in 2020, we've been committed to delivering innovative, user-focused solutions for businesses worldwide.</p>
            <p>Our vision is to empower organizations with tools that drive efficiency and growth, all while maintaining a seamless and intuitive experience.</p>
        </div>

        <div class="team-section">
            <h2>Meet Our Team</h2>
            <div class="team">
                <div class="team-member">
                    <h3>Lorraine Pangilinan</h3>
                    <p>CEO & Founder</p>
                </div>
                <div class="team-member">
                    <h3>Lorraine Pangilinan</h3>
                    <p>CTO</p>
                </div>
                <div class="team-member">
                    <h3>Lorraine Pangilinan</h3>
                    <p>Lead Developer</p>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>© 2025 Pay360. All rights reserved. | <a href="contact.html">Contact Us</a></p>
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