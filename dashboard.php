<?php
session_start();
include 'database.php';
// Set Manila timezone
date_default_timezone_set('Asia/Manila');

// Get today's date in YYYY-MM-DD format
$today = date('Y-m-d');

// Admin-only protection
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    exit("Access denied. Admin only.");
}

$statusFilter = $_GET['status'] ?? 'Pending';

// Count all-time bookings per status for sidebar
$totalStatusCounts = [
    'Pending' => 0,
    'In Progress' => 0,
    'Finished' => 0,
    'Cancelled' => 0
];

$sqlTotalCount = "
    SELECT status, COUNT(*) AS total
    FROM bookings
    GROUP BY status
";

$resultTotal = mysqli_query($conn, $sqlTotalCount);

while ($row = mysqli_fetch_assoc($resultTotal)) {
    if (array_key_exists($row['status'], $totalStatusCounts)) {
        $totalStatusCounts[$row['status']] = $row['total'];
    }
}

// Count bookings per status for today (daily summary table)
$statusCounts = [
    'Pending' => 0,
    'In Progress' => 0,
    'Finished' => 0,
    'Cancelled' => 0
];

$sqlCount = "
    SELECT status, COUNT(*) AS total
    FROM bookings
    WHERE DATE(created_at) = ?
    GROUP BY status
";

$stmtCount = mysqli_prepare($conn, $sqlCount);
mysqli_stmt_bind_param($stmtCount, "s", $today);
mysqli_stmt_execute($stmtCount);
$resultCount = mysqli_stmt_get_result($stmtCount);

while ($row = mysqli_fetch_assoc($resultCount)) {
    if (array_key_exists($row['status'], $statusCounts)) {
        $statusCounts[$row['status']] = $row['total'];
    }
}

// Fetch bookings for selected status
$bookingQuery = "
    SELECT id, full_name, phone_number, email, device_type, problem,
        repair_images, preferred_date, preferred_time, status, created_at
    FROM bookings
    WHERE status = ?
    ORDER BY id ASC
";

$stmt = mysqli_prepare($conn, $bookingQuery);
mysqli_stmt_bind_param($stmt, "s", $statusFilter);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
    }

    body {
        display: flex;
        min-height: 100vh;
        background-color: #0a1f44;
        color: white;
    }

    /* Sidebar container */
    .sidebar {
        width: 200px;
        background-color: #050b18;
        padding: 20px;

        display: flex;
        flex-direction: column;

        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
    }

    /* Sidebar menu items */
    .sidebar .menu-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 6px 0;
        padding: 10px 16px;
        color: #9ccfff;
        text-decoration: none;
        border-radius: 999px;
        transition: all 0.3s ease;
    }

    /* Hover effect for menu items */
    .sidebar .menu-item:hover {
        background-color: #1e90ff11;
        padding-left: 12px;
    }

    /* Active / highlighted menu item */
    .sidebar .menu-item.active {
        background: rgba(0, 140, 255, 0.25);
        color: #ffffff;
        box-shadow:
            0 0 8px rgba(0, 140, 255, 0.7),
            0 0 18px rgba(0, 140, 255, 0.5);
        padding-left: 22px;
        padding-right: 22px;
    }

    /* Counter badge */
    .sidebar .count {
        background: #007bff;
        color: white;
        font-size: 12px;
        padding: 3px 10px;
        border-radius: 999px;
        min-width: 22px;
        text-align: center;
        box-shadow: 0 0 6px rgba(0, 140, 255, 0.7);
    }

    /* Optional glow keyframes */
    @keyframes glow {
        0% { box-shadow: 0 0 8px rgba(0,140,255,.5); }
        50% { box-shadow: 0 0 18px rgba(0,140,255,.8); }
        100% { box-shadow: 0 0 8px rgba(0,140,255,.5); }
    }

    /* Main content and table */
    .main {
        margin-left: 200px;
        padding: 20px;
        flex: 1;
        overflow-x: auto;
    }

    h2 {
        margin-bottom: 15px;
    }

    /* Table layout */
    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #050b18;
        border-radius: 10px;
        overflow: hidden;
        table-layout: auto;
    }

    /* Columns minimum widths */
    table th,
    table td {
        padding: 12px 10px;
        border-bottom: 1px solid #1e90ff33;
        text-align: left;
        vertical-align: middle;
    }

    /* Fixed column widths for consistent spacing */
    table th:nth-child(1),
    table td:nth-child(1) { width: 6%; } /* Ticket # */
    table th:nth-child(2),
    table td:nth-child(2) { width: 10%; } /* Booking Date */
    table th:nth-child(3),
    table td:nth-child(3) { width: 9%; } /* Name */
    table th:nth-child(4),
    table td:nth-child(4) { width: 10%; } /* Phone Number */
    table th:nth-child(5),
    table td:nth-child(5) { width: 10%; } /* Email */
    table th:nth-child(6),
    table td:nth-child(6) { width: 8%; } /* Device Type */
    table th:nth-child(7),
    table td:nth-child(7) { width: 12%; } /* Problem */
    table th:nth-child(8),
    table td:nth-child(8) { width: 12%; } /* Images */
    table th:nth-child(9),
    table td:nth-child(9) { width: 8%; } /* Preferred Date */
    table th:nth-child(10),
    table td:nth-child(10) { width: 8%; } /* Preferred Time */
    table th:nth-child(11),
    table td:nth-child(11) { width: 10%; } /* Status */

    /* Make the form inside Status column display properly */
    table td form {
        display: flex;
        flex-wrap: nowrap;
        gap: 6px;
        align-items: center;
    }

    table td form select {
        min-width: 120px;
        flex-shrink: 0;
    }

    table td form button {
        white-space: nowrap;
        flex-shrink: 0;
        padding: 5px 10px;
        cursor: pointer;
    }

    th {
        background-color: #1e90ff;
    }

    tr:hover {
        background-color: #1e90ff11;
    }

    select,
    button {
        padding: 5px;
        border-radius: 4px;
        border: none;
    }

    button {
        background-color: #1e90ff;
        color: white;
        cursor: pointer;
    }

    /* Logout link fixed at bottom inside sidebar */
    .sidebar .logout-link {
        color: #1e90ff;
        text-decoration: none;
        font-weight: bold;
        padding-bottom: 10px;
        transition: color 0.3s ease;
    }

    .sidebar .logout-link:hover {
        color: #ff9999;
    }

    /* Sidebar summary box */
    .sidebar-summary {
        margin-top: auto;
        margin-bottom: 72px;
    }

    /* Summary table */
    .sidebar-summary table {
        width: 100%;
        border-collapse: collapse;
        background-color: #0a1f44;
        border-radius: 10px;
        overflow: hidden;
        font-size: 14px;
    }

    .sidebar-summary th,
    .sidebar-summary td {
        padding: 6px 8px;
        text-align: left;
        border-bottom: 1px solid #1e90ff33;
    }

    /* Prevent text from wrapping in first column */
    .sidebar-summary td:first-child {
        white-space: nowrap;
    }

    /* Date header */
    .sidebar-summary .date {
        text-align: center;
        background-color: #1e90ff;
        color: white;
        font-size: 15px;
    }

    /* Last row no border */
    .sidebar-summary tr:last-child td {
        border-bottom: none;
    }

    /* Right-align numbers */
    .sidebar-summary td:last-child {
        text-align: right;
        font-weight: bold;
    }
    </style>

</head>

<body>

    <!-- Sidebar -->
<div class="sidebar">
    <div class="menu">
        <a href="dashboard.php?status=Pending" class="menu-item <?= $statusFilter === 'Pending' ? 'active' : '' ?>">
            <span>Pending</span>
            <span class="count"><?= $totalStatusCounts['Pending'] ?></span>
        </a>

        <a href="dashboard.php?status=In Progress" class="menu-item <?= $statusFilter === 'In Progress' ? 'active' : '' ?>">
            <span>In Progress</span>
            <span class="count"><?= $totalStatusCounts['In Progress'] ?></span>
        </a>

        <a href="dashboard.php?status=Finished" class="menu-item <?= $statusFilter === 'Finished' ? 'active' : '' ?>">
            <span>Finished</span>
            <span class="count"><?= $totalStatusCounts['Finished'] ?></span>
        </a>

        <a href="dashboard.php?status=Cancelled" class="menu-item <?= $statusFilter === 'Cancelled' ? 'active' : '' ?>">
            <span>Cancelled</span>
            <span class="count"><?= $totalStatusCounts['Cancelled'] ?></span>
        </a>
        
        <a href="reports.php" class="menu-item <?= basename($_SERVER['PHP_SELF']) === 'reports.php' ? 'active' : '' ?>">
            <span>Reports</span>
        </a>

    </div>

    <div class="sidebar-summary">
    <table>
        <tr>
            <th colspan="2" class="date">
                <?= date('l') ?><br>
                <small><?= date('F d, Y') ?></small>
            </th>
        </tr>
        <tr>
            <td>Pending</td>
            <td><?= $statusCounts['Pending'] ?></td>
        </tr>
        <tr>
            <td>In Progress</td>
            <td><?= $statusCounts['In Progress'] ?></td>
        </tr>
        <tr>
            <td>Finished</td>
            <td><?= $statusCounts['Finished'] ?></td>
        </tr>
        <tr>
            <td>Cancelled</td>
            <td><?= $statusCounts['Cancelled'] ?></td>
        </tr>
    </table>
</div>

    <a href="index.php" class="logout-link">Logout</a>
</div>

    <!-- Main content -->
    <div class="main">
        <h2>Bookings</h2>

        <table>
            <thead>
                <tr>
                    <th>Ticket #</th>
                    <th>Booking Date</th>
                    <th>Name</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Device Type</th>
                    <th>Problem</th>
                    <th>Images</th>
                    <th>Preferred Date</th>
                    <th>Preferred Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>

                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars(date('Y-m-d', strtotime($row['created_at']))) ?></td>
                            <td><?= htmlspecialchars($row['full_name']) ?></td>
                            <td><?= htmlspecialchars($row['phone_number']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['device_type'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($row['problem'] ?? 'N/A') ?></td>
                            <td>
                                <?php
                                $images = json_decode($row['repair_images'], true);
                                if (!empty($images)) {
                                    foreach ($images as $img) {
                                        echo '<img src="' . htmlspecialchars($img) . '"
                                            style="width:80px; height:auto; margin:5px; border-radius:6px;">';
                                    }
                                } else {
                                    echo "No images";
                                }
                                ?>
                            </td>
                            <td><?= htmlspecialchars($row['preferred_date'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($row['preferred_time'] ?? 'N/A') ?></td>
                            <td>
                                <form action="update_status.php" method="POST">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <input type="hidden" name="current_status" value="<?= $statusFilter ?>">

                                    <select name="status" required>
                                        <option value="Pending" <?= $row['status'] === "Pending" ? 'selected' : '' ?>>Pending</option>
                                        <option value="In Progress" <?= $row['status'] === "In Progress" ? 'selected' : '' ?>>In Progress</option>
                                        <option value="Finished" <?= $row['status'] === "Finished" ? 'selected' : '' ?>>Finished</option>
                                        <option value="Cancelled" <?= $row['status'] === "Cancelled" ? 'selected' : '' ?>>Cancelled</option>
                                    </select>

                                    <button type="submit">Save</button>
                                </form>
                            </td>
                        </tr>

                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11" style="text-align:center;">No bookings found</td>
                    </tr>
                <?php endif; ?>

            </tbody>
        </table>
    </div>

</body>

</html>