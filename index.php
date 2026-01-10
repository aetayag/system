<?php
session_start();
require 'db.php';

if (!isset($conn)) {
    $conn = new mysqli($host ?? 'localhost', $username ?? 'root', $password ?? '', $dbname ?? 'hr');
    if ($conn->connect_error) die('Connection failed: ' . $conn->connect_error);
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = "Please fill in both fields!";
    } else {

    // --- Check Finance admin ---
$stmt_fin = $conn->prepare("SELECT id, password FROM finance_admin WHERE office_name = ?");
$stmt_fin->bind_param("s", $username);
$stmt_fin->execute();
$result_fin = $stmt_fin->get_result();

if ($result_fin && $result_fin->num_rows === 1) {
    $row_fin = $result_fin->fetch_assoc();

    // plain text password (same as your table)
    if ($password === $row_fin['password']) {
        $_SESSION['user_id'] = $row_fin['id'];
        $_SESSION['role'] = 'Finance';
        $_SESSION['office'] = 'Finance';

        header("Location: finance/finance.php"); // finance dashboard
        exit();
    } else {
        $error = "Incorrect password!";
    }
}
$stmt_fin->close();


        // --- Check HR admin ---
        $stmt = $conn->prepare("SELECT id, password FROM hr_admin WHERE office_name = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $row = $result->fetch_assoc();
            if ($password === $row['password']) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['role'] = 'HRadmin';
                header("Location: hr/HR.php");
                exit();
            } else {
                $error = "Incorrect password!";
            }

        } else {
            // --- Check department users ---
            $stmt_dept = $conn->prepare("SELECT id, password, dept_name FROM department WHERE dept_name = ?");
            $stmt_dept->bind_param("s", $username);
            $stmt_dept->execute();
            $result_dept = $stmt_dept->get_result();

            if ($result_dept && $result_dept->num_rows === 1) {
                $row_dept = $result_dept->fetch_assoc();
                if ($password === $row_dept['password']) { // plain text
                    $_SESSION['user_id'] = $row_dept['id'];
                    $_SESSION['role'] = 'Department';
                    $_SESSION['dept_name'] = $row_dept['dept_name'];
                    header("Location: department/college_dashboard.php"); // change this to your dept dashboard
                    exit();
                } else {
                    $error = "Incorrect password!";
                }

            } else {
                // --- Check regular employees ---
                $stmt_emp = $conn->prepare("SELECT id, password, employee_select, employee_type FROM file_201 WHERE email = ?");
                $stmt_emp->bind_param("s", $username);
                $stmt_emp->execute();
                $result_emp = $stmt_emp->get_result();

                if ($result_emp && $result_emp->num_rows === 1) {
                    $row_emp = $result_emp->fetch_assoc();
                    if (password_verify($password, $row_emp['password'])) {
                        $_SESSION['user_id'] = $row_emp['id'];
                        $_SESSION['email'] = $username;
                        $_SESSION['employee_select'] = trim($row_emp['employee_select'] ?? '');
                        $_SESSION['employee_type'] = trim($row_emp['employee_type'] ?? '');

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
                $stmt_emp->close();
            }
            $stmt_dept->close();
        }
        $stmt->close();
    }
}

$conn->close();
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
