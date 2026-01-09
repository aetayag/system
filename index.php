<?php
session_start();
require 'db.php';

// Create mysqli connection from db.php credentials
if (!isset($conn)) {
    $conn = new mysqli($host ?? 'localhost', $username ?? 'root', $password ?? '', $dbname ?? 'hr');
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error = "Please fill in both fields!";
    } else {
        $stmt = $conn->prepare("
            SELECT id, password, employee_select, employee_type
            FROM file_201
            WHERE email = ?
        ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $hashed_password, $employee_select, $employee_type);
            $stmt->fetch();

            if ($hashed_password && password_verify($password, $hashed_password)) {
                // Save session
                $_SESSION['user_id'] = $id;
                $_SESSION['email'] = $email;
                $_SESSION['employee_select'] = trim($employee_select ?? '');
                $_SESSION['employee_type']   = trim($employee_type ?? '');

                // 🔀 Redirect Logic
                if ($_SESSION['employee_select'] === 'Non-Teaching') {
                    header("Location: nonteaching/non-teaching.php");
                } elseif ($_SESSION['employee_type'] === 'Full-Time') {
                    header("Location: fulltime/fulltime_dash.php");
                } elseif ($_SESSION['employee_type'] === 'Part-Time') {
                    header("Location: parttime/part_time.php");
                } else {
                    $error = "Unknown role or type!";
                }
                exit();
            } else {
                $error = "Incorrect password!";
            }
        } else {
            $error = "User not found!";
        }
        $stmt->close();
    }
}
if (isset($conn)) {
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PLSP Login Page</title>
<link rel="stylesheet" href="index.css">
</head>
<body>

<div class="container">

    <!-- Left Panel -->
    <div class="left-panel">
        <img src="plsp pic.jpg" alt="PLSP Logo" class="logo">
        <h1>Pamantasan ng Lungsod<br>ng San Pablo</h1>
    </div>

    <!-- Right Panel -->
    <div class="right-panel">
        <div class="profile-icon">🐵</div>
        <h2 class="login-text">Log In</h2>

        <form action="" method="POST">
            <input type="text" name="username" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <a href="forget.php" class="forgot">Forgot Password</a>
            <button type="submit" class="login-btn">Log In</button>
        </form>

        <!-- Display error message if exists -->
        <?php if(isset($error)) { ?>
            <p style="color:red; text-align:center; margin-top:10px;"><?php echo htmlspecialchars($error); ?></p>
        <?php } ?>

        <a href="register/register.php" class="register">Register an account</a>
    </div>

</div>

</body>
</html>
