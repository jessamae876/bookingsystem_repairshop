<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Yheck-Shang</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /*Social Icons*/
        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .social-links {
            display: flex;
            gap: 8px;
        }

        .social-icon {
            width: 24px;
            height: 24px;
            transition: transform 0.2s;
        }

        .social-icon:hover {
            transform: scale(1.1);
        }

        /* ===== Who Images ===== */
        .who-images {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .who-image {
            flex: 1;
            object-fit: cover;
            height: 200px;
            border-radius: 0.5rem;
            filter: brightness(0.9);
        }

        /*Our Story Content*/
        .our-story {
            margin-top: 2rem;
            color: #d1d5db;
            line-height: 1.7;
        }

        .our-story h3 {
            color: #3b82f6;
            margin-bottom: 0.5rem;
        }

        .our-story p {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

    <!-- Background Image with Overlay -->
    <div class="background-wrapper">
        <div class="background-overlay"></div>
    </div>

    <div class="content-wrapper">

        <!--HEADER-->
        <header class="header">
            <div class="container">
                <div class="header-content">

                    <!-- Logo -->
                    <div class="logo">
                        <img src="logo.jpg" class="logo-icon" alt="Yheck-Shang Logo">
                        <span class="logo-text">Yheck-Shang</span>
                    </div>

                    <!-- Desktop Navigation -->
                    <nav class="desktop-nav">
                        <a href="index.php" class="nav-link">Home</a>
                        <a href="services.php" class="nav-link">Services</a>
                        <a href="bookrepair.php" class="nav-link">Book a Repair</a>
                        <a href="about.php" class="nav-link">About Us</a>
                        <A href="login.php" class="nav-link">Login</a>
                    </nav>

                    <!-- Social Links + Mobile Button -->
                    <div class="header-actions">
                        <a href="https://maps.app.goo.gl/Vd8qa3TyZtVjNNXx9" target="_blank">
                            <img src="maps.jpg" alt="Maps" class="social-icon">
                        </a>
                        <div class="social-links">
                            <a href="https://www.facebook.com/share/19BsmwarN7/" target="_blank">
                                <img src="facebook.jpg" alt="Facebook" class="social-icon">
                            </a>
                        </div>

                        <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                            <svg class="menu-icon" id="menuIcon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="4" y1="6" x2="20" y2="6"></line>
                                <line x1="4" y1="12" x2="20" y2="12"></line>
                                <line x1="4" y1="18" x2="20" y2="18"></line>
                            </svg>

                            <svg class="close-icon hidden" id="closeIcon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 6 6 18"></path>
                                <path d="M6 6 18 18"></path>
                            </svg>
                        </button>
                    </div>

                </div>

                <!-- Mobile Navigation -->
                <nav class="mobile-nav hidden" id="mobileNav">
                    <a href="index.php" class="nav-link">Home</a>
                    <a href="services.php" class="nav-link">Services</a>
                    <a href="bookrepair.php" class="nav-link">Book a Repair</a>
                    <a href="about.php" class="nav-link">About Us</a>
                </nav>
            </div>
        </header>

        <!--ABOUT CONTENT-->
        <section class="page-section">
            <div class="container">

                <!--WHO WE ARE-->
                <div class="who-we-are">
                    <div class="who-left">
                        <h2>Who We Are</h2>

                        <!--OUR STORY CONTENT-->
                        <div class="our-story">
                            <h3>Our Story</h3>
                            <p>
                                Founded in 2013, <b>Yheck & Shang</b> Cellphone Parts and Accessories started with a simple goal:
                                to provide a reliable, affordable, and high-quality option for anyone in need of cellphone
                                parts, accessories, or repair services. Over the years, we’ve grown with our community,
                                helping countless customers keep their devices running smoothly without breaking the bank.
                            </p>

                            <h3>Who We Serve</h3>
                            <p>
                                Whether your phone needs a new screen, battery replacement, or you’re simply looking for
                                the latest accessories, we’re here for you. Our customers include everyday users facing 
                                phone problems and tech enthusiasts in search of reliable parts, all wanting affordable 
                                solutions without sacrificing quality.
                            </p>

                            <h3>Our Approach</h3>
                            <p>
                                At Yheck & Shang, we pride ourselves on offering genuine, high-quality cellphone parts
                                and accessories at rates that won’t hurt your wallet. Our repair services are carried
                                out with care and expertise, ensuring your devices are restored efficiently and reliably.
                                We combine honest pricing, quality products, and excellent service to make sure our customers
                                leave satisfied every time.
                            </p>

                            <h3>Why Choose Us?</h3>
                            <p>
                                · Affordable rates without compromising quality<br>
                                · Genuine products and reliable repair services<br>
                                · Fast and professional service you can trust<br>
                                · We’re more than just a shop, we’re your dependable partner in keeping your devices
                                working at their best.
                            </p>

                            <h3>Get in Touch</h3>
                            <p>
                                Visit us for all your cellphone needs, whether it’s parts, accessories, or repairs. 
                                <b>Yheck & Shang</b> is here to make your experience smooth, affordable, and worry-free.
                                You can visit our physical store at the address below, or contact us via email or phone. 
                                You can also browse our products and updates on our Facebook page and Shopee link.
                            </p>
                        </div>
                    </div>
                </div>

                <!--CONTACT INFO-->
                <section class="contact-section">
                    <div class="container">
                        <div class="contact-cards">

                            <div class="contact-card">
                                <img src="call.jpg" alt="contact" class="social-icon">
                                <p class="contact-label">Contact Us</p>
                                <p class="contact-value">0915 269 5362</p>
                            </div>

                            <div class="contact-card">
                                <img src="email.jpg" alt="email" class="social-icon">
                                <p class="contact-label">Email Us</p>
                                <p class="contact-value">rosanabarnuevo@gmail.com</p>
                            </div>
                            <div class="contact-card">
                                <img src="clock.jpg" alt="Clock" class="social-icon">
                                <p class="contact-label">Open Hours</p>
                                <p class="contact-value">Mon–Sun: 8AM–8PM</p>
                            </div>

                        </div>
                    </div>
                </section>

            </div>
        </section>

    </div>

    <script src="script.js"></script>
</body>
</html>