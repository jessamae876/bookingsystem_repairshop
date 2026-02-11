<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services | Yheck-Shang</title>
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

        /* Services cards grid */
        .info-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            margin-top: 30px;
        }

        /* Individual card styling */
        .info-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 14px;
            padding: 24px;
            min-height: 140px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.35);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.08);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .info-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.5);
        }

        .info-card h3 {
            margin-bottom: 10px;
            font-size: 1.15rem;
            color: #ffffff;
            font-weight: 600;
        }

        .info-card p {
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.95rem;
            line-height: 1.5;
        }

        @media (max-width: 900px) {
            .info-cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 500px) {
            .info-cards {
                grid-template-columns: 1fr;
            }
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
                    <a href ="login.php" class="nav-link">Login</a>
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
                <a href ="login.php" class="nav-link">Login</a>
            </nav>
        </div>
    </header>

    <section class="page-section">
        <div class="container">
            <h1 class="page-title">Our Services</h1>
            <p class="page-description">
                We provide professional and reliable cellphone repair services using
                high-quality components and experienced technicians.
            </p>

            <div class="info-cards">
                <div class="info-card">
                    <h3>Screen Replacement</h3>
                    <p>Fixing cracked, shattered, or unresponsive glass and OLED/LCD panels.</p>
                </div>
                <div class="info-card">
                    <h3>Touchscreen Repair</h3>
                    <p>Resolving ghost touches or dead zones where the screen doesn't respond.</p>
                </div>
                <div class="info-card">
                    <h3>Back Glass Replacement</h3>
                    <p>Repairing the glass rear panel on modern premium smartphones.</p>
                </div>
                <div class="info-card">
                    <h3>Battery Replacement</h3>
                    <p>Swapping out old, bloated, or fast-draining batteries with new ones.</p>
                </div>
                <div class="info-card">
                    <h3>Charging Port Repair</h3>
                    <p>Fixing or replacing damaged USB-C or Lightning ports that fail to charge the device.</p>
                </div>
                <div class="info-card">
                    <h3>Water Damage Restoration</h3>
                    <p>Deep cleaning and component repair for phones exposed to liquids.</p>
                </div>
                <div class="info-card">
                    <h3>Camera Repair</h3>
                    <p>Replacing scratched lenses or malfunctioning front and rear camera modules.</p>
                </div>
                <div class="info-card">
                    <h3>Audio Component Repair</h3>
                    <p>Fixing speakers, microphones, and earpieces for call and media issues.</p>
                </div>
                <div class="info-card">
                    <h3>Phone Unlocking</h3>
                    <p>Unlocking devices restricted to specific network carriers.</p>
                </div>
                <div class="info-card">
                    <h3>Data Recovery</h3>
                    <p>Retrieving lost photos and files from damaged or non-working devices.</p>
                </div>
                <div class="info-card">
                    <h3>Security Lock Removal</h3>
                    <p>Bypassing forgotten passcodes, patterns, or FRP (Google/iCloud) locks.</p>
                </div>
                <div class="info-card">
                    <h3>Software Updates & Bug Fixes</h3>
                    <p>Resolving "boot loops," app crashes, and OS malfunctions.</p>
                </div>
                <div class="info-card">
                    <h3>Motherboard / Logic Board Repair</h3>
                    <p>Complex micro-soldering to fix dead units or specific chip failures.</p>
                </div>
                <div class="info-card">
                    <h3>Power / Volume Button Repair</h3>
                    <p>Replacing mechanical buttons that are stuck or non-functional.</p>
                </div>
                <div class="info-card">
                    <h3>SIM Card/SD Card Slot Repair</h3>
                    <p>Fixing or replacing damaged SIM or memory card slots to restore connectivity and storage access.</p>
                </div>
                <div class="info-card">
                    <h3>Wi-Fi/Bluetooth Module Repair</h3>
                    <p>Repairing or replacing wireless components to ensure stable network and device connections.</p>
                </div>
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

<script src="script.js"></script>
</body>
</html>