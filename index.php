<?php
session_start();
include 'database.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yheck&Shang - Cellphone Repair Services</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Social Icons */
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
    </style>
</head>

<body>

    <div class="background-wrapper">
        <div class="background-overlay"></div>
    </div>

    <div class="content-wrapper">

        <header class="header">
            <div class="container">
                <div class="header-content">

                    <div class="logo">
                        <img src="logo.jpg" class="logo-icon" alt="Yheck-Shang Logo">
                        <span class="logo-text">Yheck-Shang</span>
                    </div>

                    <nav class="desktop-nav">
                        <a href="index.php" class="nav-link">Home</a>
                        <a href="services.php" class="nav-link">Services</a>
                        <a href="bookrepair.php" class="nav-link">Book a Repair</a>
                        <a href="about.php" class="nav-link">About Us</a>
                        <a href="login.php" class="nav-link">Login</a>
                    </nav>

                    <div class="header-actions">

                        <div class="social-links">
                            <a href="https://maps.app.goo.gl/Vd8qa3TyZtVjNNXx9" target="_blank">
                                <img src="maps.jpg" alt="Maps" class="social-icon">
                            </a>
                            <div class="social-links">
                                <a href="https://www.facebook.com/share/19BsmwarN7/" target="_blank">
                                    <img src="facebook.jpg" alt="Facebook" class="social-icon">
                                </a>
                            </div>

                            <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                                <svg class="menu-icon" id="menuIcon" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="4" y1="6" x2="20" y2="6"></line>
                                    <line x1="4" y1="12" x2="20" y2="12"></line>
                                    <line x1="4" y1="18" x2="20" y2="18"></line>
                                </svg>
                                <svg class="close-icon hidden" id="closeIcon" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M18 6 6 18"></path>
                                    <path d="M6 6 18 18"></path>
                                </svg>
                            </button>

                        </div>
                    </div>

                    <nav class="mobile-nav hidden" id="mobileNav">
                        <a href="index.php" class="nav-link">Home</a>
                        <a href="services.php" class="nav-link">Services</a>
                        <a href="bookrepair.php" class="nav-link">Book a Repair</a>
                        <a href="about.php" class="nav-link">About Us</a>
                        <a href="login.php" class="nav-link">Admin Login</a>

                    </nav>
                </div>
        </header>

        <section class="hero-section">
            <div class="container">
                <div class="hero-content">
                    <p class="hero-subtitle">Mobile repairs you can count on</p>

                    <h1 class="hero-title">
                        Phone Trouble?<br>
                        <span class="title-highlight">Yheck-Shang</span> got your back!
                    </h1>

                    <p class="hero-description">
                        Professional cellphone repair services you can trust.
                        Legit parts, affordable prices, and expert technicians
                        serving customers since 2013.
                    </p>

                    <div class="hero-buttons">
                        <button class="btn btn-primary btn-large" onclick="window.location.href='bookrepair.php'">
                            Book a Repair
                        </button>
                        <button class="btn btn-primary btn-large" onclick="window.location.href='services.php'">
                            Browse Services Offered
                        </button>
                    </div>

                    <div class="info-cards">
                        <div class="info-card">
                            <h3 class="info-title">EXPERT TECHNICIANS</h3>
                            <p class="info-description">
                                Our skilled technicians have years of experience in cellphone repairs.
                            </p>
                        </div>
                        <div class="info-card">
                            <h3 class="info-title">QUALITY SERVICE</h3>
                            <p class="info-description">
                                Reliable repair solutions using tested replacement components.
                            </p>
                        </div>
                        <div class="info-card">
                            <h3 class="info-title">FAST REPAIR</h3>
                            <p class="info-description">Most repairs completed the same day.</p>
                        </div>
                        <div class="info-card">
                            <h3 class="info-title">AFFORDABLE RATES</h3>
                            <p class="info-description">
                                Affordable prices without compromising quality.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section class="track-section">
            <div class="container">
                <div class="track-card">
                    <h2 class="track-title">Track Your Repair Status</h2>
                    <p class="track-description">Enter your repair ticket number</p>

                    <div class="track-form">
                        <input type="text" id="ticketNumber" placeholder="Enter ticket number..." class="track-input">
                        <button class="btn btn-primary" onclick="trackRepair()">Track</button>
                    </div>
                    <script>
                        function trackRepair() {
                            const ticket = document.getElementById('ticketNumber').value.trim();
                            
                            if (ticket === "") {
                                alert("Please enter your ticket number.");
                                return;
                            }

                            window.location.href = "track.php?ticket=" + encodeURIComponent(ticket);
                        }
                    </script>

                </div>
            </div>
        </section>

        <section class="contact-section">
            <div class="container">
                <div class="contact-cards">
                    <div class="contact-card">
                        <img src="call.jpg" alt="contact" class="social-icon">
                        <p class="contact-label">Contact Us</p>
                        <p class="contact-value">0915 269 5362</p>
                    </div>
                    <div class="contact-card">
                        <img src="email.jpg" alt="Email" class="social-icon">
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

    <!-- <script src="script.js"></script> -->
</body>

</html>