<?php
session_start();
include "database.php";

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
    if (!empty($_FILES['repair_images']['name'][0])) {
        $uploadDir = "uploads/";
        foreach ($_FILES['repair_images']['tmp_name'] as $key => $tmpName) {
            $fileName = time() . "_" . $_FILES['repair_images']['name'][$key];
            $targetPath = $uploadDir . $fileName;
            if (move_uploaded_file($tmpName, $targetPath)) {
                $uploadedImages[] = $targetPath;
            }
        }
        // Convert to JSON for database storage
        $imagesJson = !empty($uploadedImages) ? json_encode($uploadedImages) : null;
    }

    // Validation
    $errors = [];
    if (empty($full_name)) $errors[] = "Full name is required.";
    if (empty($phone_number)) $errors[] = "Phone number is required.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if (empty($device_type)) $errors[] = "Device type is required.";
    if (empty($problem)) $errors[] = "Problem description is required.";
    if (empty($imagesJson)) $errors[] = "At least one image is required.";
    if (empty($preferred_date) || !strtotime($preferred_date)) $errors[] = "Valid preferred date is required.";
    if (empty($preferred_time)) $errors[] = "Preferred time is required.";

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: bookrepair.php");
        exit();
    }

    // Insert into database
    $stmt = mysqli_prepare($conn, "INSERT INTO bookings (full_name, phone_number, email, device_type, problem, repair_images, preferred_date, preferred_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssssssss", $full_name, $phone_number, $email, $device_type, $problem, $imagesJson, $preferred_date, $preferred_time_24);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['ticketNumber'] = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);
        header("Location: appointments_success.php");
        exit();
    } else {
        mysqli_stmt_close($stmt);
        die("Database error: " . mysqli_stmt_error($stmt));
    }
} else {
    header("Location: bookrepair.php");
    exit();
}