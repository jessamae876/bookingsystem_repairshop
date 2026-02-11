<?php
session_start();
include 'database.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Sanitize and validate inputs
    $login = trim($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins
            WHERE (username = ? OR email = ?)
            AND role = 'admin'";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ss", $login, $login);
    $stmt->execute();

    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && $password === $admin['password']) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['username'];
        $_SESSION['role'] = 'admin';

        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Access denied. Admin only.";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="styles.css">

    <style>
        body {
            margin: 0;
            height: 100vh;

            /* Background image */
            background:
                linear-gradient(rgba(2, 6, 23, 0.8), rgba(2, 6, 23, 0.8)),
                url("shop.jpg");

            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;

            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
            color: white;
        }

        .login-box {
            background: #050b18;
            padding: 45px;
            width: 360px;
            border-radius: 15px;
            box-shadow: 0 0 25px rgba(30, 144, 255, 0.5);
        }

        .login-box h2 {
            text-align: center;
            color: #1e90ff;
            margin-bottom: 25px;
        }

        .login-box input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: none;
            outline: none;
        }

        .login-box button {
            background-color: #2f8ef5;
            color: white;
            border: none;
        padding: 12px 40px;
        border-radius: 10px;
        font-size: 16px;
        cursor: pointer;
        text-decoration: none;
        font-family: inherit;
        transition: 0.3s ease;

        }

        .login-box button:hover {
            background: #00bfff;
        }

        .button {
    display: flex;
    justify-content: space-between; /* pushes them apart */
    align-items: center;
    margin-top: 20px;
}
        .error {
            color: #ff4c4c;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

    <div class="login-box">
        <h2>Admin Login</h2>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>


        <form method="POST">
            <input type="text" name="username" placeholder="Admin Username" required>
            <input type="password" name="password" placeholder="Admin Password" required>
            <div class="button">
            <button type="button" onclick="window.location.href='index.php'">Home</button>
            <button type="submit">Login</button>
            </div>

        </form>

    </div>

</body>

</html>