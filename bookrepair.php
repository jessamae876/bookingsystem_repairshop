<?php
session_start();
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name      = trim($_POST['fullName'] ?? '');
    $phone_number   = trim($_POST['phoneNumber'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $device_type    = trim($_POST['deviceType'] ?? '');
    $problem        = trim($_POST['problem'] ?? '');
    $preferred_date = $_POST['preferredDate'] ?? '';
    $preferred_time = $_POST['preferredTime'] ?? '';
    // Convert from "08:00 AM" or "06:30 PM" to MySQL TIME format "HH:MM:SS"
    $preferred_time_24 = date("H:i:s", strtotime($preferred_time));

    // Default status for new bookings
    $default_status = "Pending";

    // Handle file uploads
    $uploadedImages = [];
    $imagesJson = null; 
    $uploadedImages = [];
$imagesJson = null;

if (isset($_FILES['repairImages']) && is_array($_FILES['repairImages']['name'])) {
    $uploadDir = "uploads/";

    foreach ($_FILES['repairImages']['name'] as $key => $name) {
        if ($_FILES['repairImages']['error'][$key] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['repairImages']['tmp_name'][$key];

            $fileName = uniqid() . "_" . basename($name);
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($tmpName, $targetPath)) {
                $uploadedImages[] = $targetPath;
            }
        }
    }

    if (!empty($uploadedImages)) {
        $imagesJson = json_encode($uploadedImages);
    }
}

    // Validation
    $errors = [];
    if (empty($full_name)) $errors[] = "Full name is required.";
    if (empty($phone_number)) $errors[] = "Phone number is required.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if (empty($device_type)) $errors[] = "Device type is required.";
    if (empty($problem)) $errors[] = "Problem description is required.";
    if (empty($uploadedImages)) {
        $errors[] = "At least one image is required.";
    }
    if (empty($preferred_date) || !strtotime($preferred_date)) $errors[] = "Valid preferred date is required.";
    if (empty($preferred_time)) $errors[] = "Preferred time is required.";

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: bookrepair.php");
        exit();
    }

    // Insert into database
    $stmt = mysqli_prepare($conn, "INSERT INTO bookings (full_name, phone_number, email, device_type, problem, repair_images, preferred_date, preferred_time, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssssssss", $full_name, $phone_number, $email, $device_type, $problem, $imagesJson, $preferred_date, $preferred_time_24, $default_status);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['ticketNumber'] = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);
        header("Location: appointments_success.php");
        exit();
    } else {
        mysqli_stmt_close($stmt);
        die("Database error: " . mysqli_stmt_error($stmt));
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Repair | Yheck-Shang</title>
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


        /*Input & Buttons*/
        #trackTicket {
            width: calc(100% - 110px);
            padding: 8px 12px;
        }

        #trackInputWrapper {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
            gap: 10px;
        }

        #trackInputWrapper .input-check-wrapper {
            display: flex;
            gap: 10px;
        }

        #trackInputWrapper button#checkTicketBtn {
            width: 100px;
            flex-shrink: 0;
        }

        #backToBookingBtn {
            align-self: flex-start;
            margin-top: 5px;
        }

        /*Form Styles*/
        .form-group label {
            display: block;
            margin-bottom: 4px;
            font-weight: 400;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            margin-bottom: 22px;
            padding: 8px 10px;
            font-size: 16px;
        }

        .form-group textarea {
            min-height: 55px;
            resize: vertical;
        }

        .track-card .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: stretch;
            margin-top: 20px;
            gap: 10px;
        }

        .track-card .form-actions .btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 10px 20px;
        }

        .page-description {
            margin-bottom: 35px;
        }

        @media (max-width: 500px) {
            .track-card .form-actions {
                flex-direction: column;
                gap: 10px;
            }

            .track-card .form-actions .btn {
                width: 100%;
            }

            #trackInputWrapper .input-check-wrapper {
                flex-direction: column;
            }

            #trackInputWrapper button#checkTicketBtn {
                width: 100%;
            }
        }

        /*Modal Popup Styles*/
        #modalOverlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        #modalContent {
            background: #1a1a1a;
            color: #fff;
            padding: 25px 30px;
            border-radius: 12px;
            max-width: 500px;
            width: 90%;
            text-align: left;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.5);
            position: relative;
        }

        #modalTitle {
            margin-bottom: 15px;
            font-size: 20px;
            font-weight: 500;
            text-align: center;
        }

        #modalMessage {
            margin-bottom: 25px;
            font-size: 16px;
        }

        #modalCloseBtn {
            padding: 10px 18px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background: transparent;
            color: #fff;
            font-size: 14px;
            cursor: pointer;
            transition: 0.2s;
        }

        #modalCloseBtn:hover {
            background-color: #fff;
            color: #333;
        }

        /* Table Styles for Modal */
        .modal-table {
            width: 100%;
            border-collapse: collapse;
        }

        .modal-table td {
            padding: 6px 8px;
            vertical-align: top;
        }

        .modal-table td:first-child {
            font-weight: 500;
            width: 35%;
            color: #ccc;
        }

        .modal-table td:last-child {
            color: #fff;
        }

        /*Note at Bottom*/
        .modal-note {
            margin-top: 15px;
            font-size: 14px;
            color: #ccc;
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

        <!-- Booking Form Section -->
        <section class="page-section">
            <div class="container">
                <h1 class="page-title">Book a Repair</h1>
                <p class="page-description">
                    Schedule your cellphone repair with our expert technicians.
                </p>

                <div class="track-card">
                    <h2>Book Your Repair</h2>
                    <p>Fill in the details below to schedule your repair.</p>

                    <!-- Display Errors if Any -->
                    <?php if (isset($_SESSION['errors'])): ?>
                        <div style="color: red; margin-bottom: 20px;">
                            <ul>
                                <?php foreach ($_SESSION['errors'] as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php unset($_SESSION['errors']); ?>
                    <?php endif; ?>

                    <!-- Booking Form -->
                    <form id="bookingForm" enctype="multipart/form-data" method="POST">
                        <div class="form-group">
                            <label>Full Name *</label>
                            <input type="text" name="fullName" required>
                        </div>
                        <div class="form-group">
                            <label>Phone Number *</label>
                            <input type="tel" name="phoneNumber" required>
                        </div>
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label>Device Type *</label>
                            <input type="text" name="deviceType" required>
                        </div>
                        <div class="form-group">
                            <label>Problem Description *</label>
                            <textarea name="problem" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Attach Images *</label>
                            <input type="file" name="repairImages[]" accept="image/*" multiple onchange="previewMultipleImages(event)" required>
                        </div>
                        <!-- IMAGE PREVIEW -->
                        <div id="imagePreview" style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:20px;"></div>


                        <div class="form-group">
                            <label>Preferred Date *</label>
                            <input type="date" name="preferredDate" required>
                        </div>
                        <div class="form-group">
                            <label>Preferred Time *</label>
                            <select name="preferredTime" required>
                                <option value="">Select time</option>
                                <option>08:00 AM</option>
                                <option>08:30 AM</option>
                                <option>09:00 AM</option>
                                <option>09:30 AM</option>
                                <option>10:00 AM</option>
                                <option>10:30 AM</option>
                                <option>11:00 AM</option>
                                <option>11:30 AM</option>
                                <option>12:00 PM</option>
                                <option>12:30 PM</option>
                                <option>01:00 PM</option>
                                <option>01:30 PM</option>
                                <option>02:00 PM</option>
                                <option>02:30 PM</option>
                                <option>03:00 PM</option>
                                <option>03:30 PM</option>
                                <option>04:00 PM</option>
                                <option>04:30 PM</option>
                                <option>05:00 PM</option>
                                <option>05:30 PM</option>
                                <option>06:00 PM</option>
                                <option>06:30 PM</option>
                                <option>07:00 PM</option>
                                <option>07:30 PM</option>
                                <option>08:00 PM</option>
                            </select>
                        </div>

                        <!-- Form Buttons -->
                        <div class="form-actions">
                            <a href="index.php" class="btn btn-outline">Back</a>
                            <button type="submit" class="btn btn-outline">Submit Booking</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
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

    <script>
        function toggleMobileMenu() {
            const mobileNav = document.getElementById('mobileNav');
            const menuIcon = document.getElementById('menuIcon');
            const closeIcon = document.getElementById('closeIcon');

            if (mobileNav.classList.contains('hidden')) {
                mobileNav.classList.remove('hidden');
                menuIcon.classList.add('hidden');
                closeIcon.classList.remove('hidden');
            } else {
                mobileNav.classList.add('hidden');
                menuIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
            }
        }

        function previewMultipleImages(event) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = ''; 

            const files = event.target.files;
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.width = '80px';
                        img.style.height = 'auto';
                        img.style.margin = '5px';
                        img.style.borderRadius = '6px';
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }
            }
        }
    </script>

</body>

</html>