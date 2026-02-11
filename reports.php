<?php
session_start();
include 'database.php';

// Admin-only protection
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    exit("Access denied. Admin only.");
}

// Get monthly data for current year
$currentYear = date('Y');

// Initialize monthly status counts
$months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
$monthlyStatusCounts = [];
foreach ($months as $m) {
    $monthlyStatusCounts[$m] = ['Pending'=>0, 'In Progress'=>0, 'Finished'=>0, 'Cancelled'=>0];
}

// Fetch counts per month and per status
$sql = "SELECT MONTH(created_at) AS month, status, COUNT(*) AS total
        FROM bookings
        WHERE YEAR(created_at) = ?
        GROUP BY month, status";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $currentYear);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {
    $monthIndex = (int)$row['month'] - 1; 
    $monthName = $months[$monthIndex];
    $monthlyStatusCounts[$monthName][$row['status']] = (int)$row['total'];
}

// Prepare total bookings per month for the chart
$totalMonthlyBookings = [];
foreach ($months as $m) {
    $totalMonthlyBookings[] = array_sum($monthlyStatusCounts[$m]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Monthly Reports</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; font-family: Arial, sans-serif; }
body { display: flex; min-height: 100vh; background-color: #0a1f44; color: white; }

/* Sidebar */
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
.sidebar .menu {
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.sidebar .menu-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 16px;
    color: #9ccfff;
    text-decoration: none;
    border-radius: 999px;
    transition: all 0.3s ease;
}
.sidebar .menu-item:hover { background-color:#1e90ff11; padding-left:12px; }
.sidebar .menu-item.active {
    background: rgba(0,140,255,0.25);
    color:#fff;
    box-shadow: 0 0 8px rgba(0,140,255,0.7), 0 0 18px rgba(0,140,255,0.5);
    padding-left:22px;
    padding-right:22px;
}
.sidebar .logout-link {
    margin-top: auto;
    color: #1e90ff;
    text-decoration: none;
    font-weight: bold;
    padding-bottom: 10px;
    transition: color 0.3s ease;
}
.sidebar .logout-link:hover { color:#ff9999; }

/* Main content */
.main {
    margin-left: 200px;
    padding: 20px;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 30px;
}
h2 { margin-bottom: 20px; }

/* Chart */
.chart-container {
    width: 100%;
    flex: 1;
}
canvas {
    background-color:#050b18;
    border-radius:10px;
    width: 100% !important;
    height: 400px;
}

/* Table for monthly status counts */
table {
    width: 100%;
    border-collapse: collapse;
    background-color: #050b18;
    border-radius: 10px;
    overflow: hidden;
    table-layout: fixed;
    word-wrap: break-word;
}
th, td {
    padding: 12px 15px;
    border-bottom: 1px solid #1e90ff33;
    text-align: center;
}
th { background-color: #1e90ff; }
tr:hover { background-color: #1e90ff11; }
</style>
</head>
<body>

<div class="sidebar">
    <div class="menu">
        <a href="dashboard.php?status=Pending" class="menu-item">Pending</a>
        <a href="dashboard.php?status=In Progress" class="menu-item">In Progress</a>
        <a href="dashboard.php?status=Finished" class="menu-item">Finished</a>
        <a href="dashboard.php?status=Cancelled" class="menu-item">Cancelled</a>
        <a href="reports.php" class="menu-item active">Reports</a>
    </div>
    <a href="index.php" class="logout-link">Logout</a>
</div>

<div class="main">
    <h2>Monthly Bookings Report (<?= $currentYear ?>)</h2>

    <!-- Table showing Pending/In Progress/Finished/Cancelled per month -->
    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th>Pending</th>
                <th>In Progress</th>
                <th>Finished</th>
                <th>Cancelled</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($months as $m): ?>
            <tr>
                <td><?= $m ?></td>
                <td><?= $monthlyStatusCounts[$m]['Pending'] ?></td>
                <td><?= $monthlyStatusCounts[$m]['In Progress'] ?></td>
                <td><?= $monthlyStatusCounts[$m]['Finished'] ?></td>
                <td><?= $monthlyStatusCounts[$m]['Cancelled'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Graph showing total bookings per month -->
    <div class="chart-container">
        <canvas id="monthlyChart"></canvas>
    </div>
</div>

<script>
const ctx = document.getElementById('monthlyChart').getContext('2d');
const monthlyChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($months) ?>,
        datasets: [{
            label: 'Total Bookings',
            data: <?= json_encode($totalMonthlyBookings) ?>,
            backgroundColor: 'rgba(30,144,255,0.7)',
            borderColor: '#1e90ff',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1, color: 'white' }, grid: { color: '#1e90ff33' } },
            x: { ticks: { color: 'white' }, grid: { color: '#1e90ff33' } }
        },
        plugins: { legend: { labels: { color: 'white' } }, tooltip: { mode: 'index', intersect: false } }
    }
});
</script>

</body>
</html>