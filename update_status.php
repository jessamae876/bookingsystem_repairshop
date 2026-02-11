<?php
session_start();
include "database.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    exit("Access denied. Admin only.");
}

$id = intval($_POST['id']);
$status = $_POST['status'];
$currentStatus = $_POST['current_status'] ?? 'Pending';

$allowed = ['Pending', 'In Progress', 'Finished', 'Cancelled'];

if (!in_array($status, $allowed)) {
    die("Invalid status value");
}

// Update booking status
$stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $id);
$stmt->execute();

$redirectStatus = $currentStatus; 

header("Location: dashboard.php?status=" . urlencode($redirectStatus));
exit;