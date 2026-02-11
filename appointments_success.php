<?php
session_start();
include "database.php";
// Check if ticket number is set in session
if (!isset($_SESSION['ticketNumber'])) {
    header("Location: bookrepair.php");
    exit();
}
// Retrieve appointment details
$ticketNumber = intval($_SESSION['ticketNumber']);

$stmt = mysqli_prepare($conn, "SELECT * FROM bookings WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $ticketNumber);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($result) === 0) {
    mysqli_stmt_close($stmt);
    die("Appointment not found.");
}
$appointment = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Clear session
unset($_SESSION['ticketNumber']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Booking Confirmed!</title>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            min-height: 100vh;
        }

        /* Background Image */
        .background-wrapper {
            position: fixed;
            inset: 0;
            background: url("images/background.jpg") center center / cover no-repeat;
            z-index: -1; 
        }

        .background-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5); 
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 1; 
            background: rgba(26,26,26,0.9); 
            color: #fff;
            padding: 25px;
            max-width: 600px;
            width: 90%;
            border-radius: 12px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.8);
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        td {
            padding: 8px;
            vertical-align: top;
        }

        td:first-child {
            font-weight: bold;
            color: #ccc;
            width: 35%;
        }

        td:last-child {
            color: #fff;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            border: 1px solid #fff;
            border-radius: 8px;
            color: #fff;
            text-decoration: none;
        }

        a:hover {
            background: #fff;
            color: #1a1a1a;
        }

        .note {
            margin-top: 15px;
            font-size: 14px;
            color: #ccc;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="background-wrapper"></div>
    <div class="background-overlay"></div>

    <div class="container">
        <h1>Booking Confirmed!</h1>
        <table>
            <tr>
                <td>Ticket Number:</td>
                <td><?php echo htmlspecialchars($appointment['id']); ?></td>
            </tr>
            <tr>
                <td>Full Name:</td>
                <td><?php echo htmlspecialchars($appointment['full_name']); ?></td>
            </tr>
            <tr>
                <td>Phone Number:</td>
                <td><?php echo htmlspecialchars($appointment['phone_number']); ?></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><?php echo htmlspecialchars($appointment['email']); ?></td>
            </tr>
            <tr>
                <td>Device:</td>
                <td><?php echo htmlspecialchars($appointment['device_type']); ?></td>
            </tr>
            <tr>
                <td>Problem:</td>
                <td><?php echo htmlspecialchars($appointment['problem']); ?></td>
            </tr>
        <tr>
    <td>Images</td>
    <td><?php    if (!empty($appointment['repair_images'])) {
            $images = json_decode($appointment['repair_images'], true);
            if (is_array($images)) {
                foreach ($images as $imgPath) {
                    echo '<a href="' . htmlspecialchars($imgPath) . '" target="_blank">
                            <img src="' . htmlspecialchars($imgPath) . '"
                                style="width:80px; margin:5px; border-radius:6px;">
                        </a>';
                }
            } else {
                echo 'Images provided.';
            }
        } else {
            echo 'No images provided.';
        }
        ?>
    </td>
</tr>
            <tr>
                <td>Date:</td>
                <td><?php echo htmlspecialchars($appointment['preferred_date']); ?></td>
            </tr>
            <tr>
                <td>Time:</td>
                <td><?php
                    // Convert time to AM/PM
                    echo date("h:i A", strtotime($appointment['preferred_time'])); 
                ?></td>
            </tr>
            <tr>
                <td>Status:</td>
                <td><?php echo htmlspecialchars($appointment['status']); ?></td>
            </tr>
        </table>
        <p class="note">
            Please bring your device on your preferred date and time.
            Thank you for trusting Yheck-Shang!
        </p>
        <a href="index.php">Back to Home</a>
    </div>

</body>

</html>