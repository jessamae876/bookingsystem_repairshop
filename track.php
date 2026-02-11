<?php
include "database.php";

// Accept BOTH ticket and ticketNumber (safe)
$ticketNumber = $_GET['ticket'] ?? $_GET['ticketNumber'] ?? null;

if (!$ticketNumber || !is_numeric($ticketNumber)) {
    die("Invalid or missing ticket number.");
}

$ticketNumber = (int)$ticketNumber;

$stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ?");
$stmt->bind_param("i", $ticketNumber);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No appointment found with ticket number #$ticketNumber");
}

$appointment = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Track Booking #<?php echo $ticketNumber; ?></title>
<style>
body { font-family: Arial, sans-serif; background: #0a1f44; padding: 20px; }
.container {
    background: #050b18;
    color: #fff;
    padding: 25px;
    max-width: 600px;
    margin: 50px auto;
    border-radius: 12px;
}
h1 { text-align: center; color: #1e90ff; }
table { width: 100%; border-collapse: collapse; margin-top: 20px; }
td { padding: 8px; vertical-align: top; }
td:first-child { font-weight: bold; color: #9ccfff; width: 35%; }
a {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 20px;
    border: 1px solid #1e90ff;
    border-radius: 8px;
    color: #1e90ff;
    text-decoration: none;
}
a:hover { background: #1e90ff; color: #fff; }
</style>
</head>
<body>

<div class="container">
<h1>Appointment Status</h1>
<table>
<tr><td>Ticket #</td><td>#<?php echo $appointment['id']; ?></td></tr>
<tr><td>Full Name</td><td><?php echo htmlspecialchars($appointment['full_name']); ?></td></tr>
<tr><td>Phone Number</td><td><?php echo htmlspecialchars($appointment['phone_number']); ?></td></tr>
<tr><td>Email</td><td><?php echo htmlspecialchars($appointment['email']); ?></td></tr>
<tr><td>Device</td><td><?php echo htmlspecialchars($appointment['device_type']); ?></td></tr>
<tr><td>Problem</td><td><?php echo htmlspecialchars($appointment['problem']); ?></td></tr>
<tr><td>Date</td><td><?php echo htmlspecialchars($appointment['preferred_date']); ?></td></tr>
<tr><td>Time</td><td><?php echo htmlspecialchars($appointment['preferred_time']); ?></td></tr>
<tr><td>Status</td><td><strong><?php echo htmlspecialchars($appointment['status']); ?></strong></td></tr>
</table>

<a href="index.php">Back to Home</a>
</div>

</body>
</html>