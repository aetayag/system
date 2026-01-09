<?php
session_start();
require_once __DIR__ . '/../db.php'; // your DB connection

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Initialize variables
$success = '';
$error = '';
$user = [];

// Fetch user data
try {
    $stmt = $pdo->prepare("SELECT * FROM file_201 WHERE id = :id");
    $stmt->execute([':id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $error = "User not found!";
    }
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}

// Handle Request button submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_request'])) {
    try {
        $stmt = $pdo->prepare("INSERT INTO connection_inbox (employee_id, request_type, date_created) VALUES (:empid, :rtype, NOW())");
        $stmt->execute([
            ':empid' => $user['employee_id'], // use employee_id from file_201
            ':rtype' => 'Edit Request'
        ]);
        $success = "Request sent successfully!";
    } catch (PDOException $e) {
        $error = "Failed to send request: " . $e->getMessage();
    }
}

// Handle password update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($new !== $confirm) {
        $error = "New password and Confirm password do not match!";
    } else {
        try {
            // Verify current password
            if (password_verify($current, $user['password'])) {
                $new_hash = password_hash($new, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE file_201 SET password = :pass WHERE id = :id");
                $stmt->execute([':pass' => $new_hash, ':id' => $_SESSION['user_id']]);
                $success = "Password updated successfully!";
            } else {
                $error = "Current password is incorrect!";
            }
        } catch (PDOException $e) {
            $error = "Password update failed: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profile Page</title>
<link rel="stylesheet" href="profile.css">
</head>
<body>

<!-- GREEN HEADER -->
<header class="top-header">
    <img src="plsp pic.jpg" class="uni-logo">
    <div class="header-text">
        <h2>Pamantasan ng Lungsod ng San Pablo</h2>
        <p>Prime to Lead and Serve for Progress!</p>
    </div>
</header>

<!-- MAIN LAYOUT WRAPPER -->
<div class="layout">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="back-btn" onclick="window.location.href='part_time.php'">←</div>
        <div class="profile-pic">
            <img src="profile.webp" alt="Profile">
        </div>
        <button class="menu-btn active" data-page="account">Account Details</button>
        <button class="menu-btn" data-page="security">Security</button>
        <button class="menu-btn" data-page="activity">Activity Log</button>
        <button class="menu-btn logout" onclick="window.location.href='../index.php?logout=true'">Log out</button>
    </div>

    <!-- RIGHT CONTENT -->
    <div class="content">

        <!-- ACCOUNT DETAILS -->
        <div id="account" class="page active">
            <h3>Account Details</h3>

            <?php if ($success): ?>
                <div class="success-msg"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="error-msg"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form class="form-grid" method="POST">
                <!-- READ-ONLY FIELDS -->
                <label>Full Name</label>
                <input type="text" value="<?= htmlspecialchars($user['name']) ?>" readonly class="read-only">

                <label>Birthday</label>
                <input type="date" value="<?= htmlspecialchars($user['dob']) ?>" readonly class="read-only">

                <label>Age</label>
                <input type="number" value="<?= htmlspecialchars($user['age']) ?>" readonly class="read-only">

                <label>Place of Birth</label>
                <input type="text" value="<?= htmlspecialchars($user['pob']) ?>" readonly class="read-only">

                <label>Sex</label>
                <input type="text" value="<?= htmlspecialchars($user['sex']) ?>" readonly class="read-only">

                <label>Address</label>
                <input type="text" value="<?= htmlspecialchars($user['address']) ?>" readonly class="read-only">

                <label>Civil Status</label>
                <input type="text" value="<?= htmlspecialchars($user['civil_status']) ?>" readonly class="read-only">

                <label>Date Hired</label>
                <input type="date" value="<?= htmlspecialchars($user['date_hired']) ?>" readonly class="read-only">

                <label>Position</label>
                <input type="text" value="<?= htmlspecialchars($user['position']) ?>" readonly class="read-only">

                <label>Department</label>
                <input type="text" value="<?= htmlspecialchars($user['department']) ?>" readonly class="read-only">

                <label>Employee Type</label>
                <input type="text" value="<?= htmlspecialchars($user['employee_type']) ?>" readonly class="read-only">

                <label>Email</label>
                <input type="text" value="<?= htmlspecialchars($user['email']) ?>" readonly class="read-only">

                <label>Emergency Contact Name</label>
                <input type="text" value="<?= htmlspecialchars($user['emergency_contact']) ?>" readonly class="read-only">

                <label>Emergency Contact Number</label>
                <input type="text" value="<?= htmlspecialchars($user['emergency_contact_no']) ?>" readonly class="read-only">

                <!-- Request Button -->
                <button type="submit" name="send_request" class="update-btn">Send Request</button>
            </form>
        </div>

        <!-- SECURITY -->
        <div id="security" class="page">
            <h3>Security</h3>
            <form class="form-grid" method="POST">
                <label>Current Password</label>
                <input type="password" name="current_password" required>

                <label>New Password</label>
                <input type="password" name="new_password" required>

                <label>Confirm Password</label>
                <input type="password" name="confirm_password" required>

                <input type="hidden" name="update_password" value="1">
                <button type="submit" class="update-btn">Update Password</button>
            </form>
        </div>

        <!-- ACTIVITY LOG -->
        <div id="activity" class="page">
            <h3>Activity Log</h3>
            <table class="log-table">
                <tr>
                    <th>Date Time</th>
                    <th>Activity</th>
                </tr>
                <!-- Placeholder, populate dynamically from activity table -->
                <tr>
                    <td>Jan 9, 2026 1:39 PM</td>
                    <td>Profile viewed</td>
                </tr>
            </table>
        </div>

    </div>
</div>

<script>
    // MENU SWITCHING
    const buttons = document.querySelectorAll(".menu-btn");
    const pages = document.querySelectorAll(".page");

    buttons.forEach(btn => {
        btn.addEventListener("click", () => {
            buttons.forEach(b => b.classList.remove("active"));
            btn.classList.add("active");

            pages.forEach(p => p.classList.remove("active"));
            document.getElementById(btn.dataset.page).classList.add("active");
        });
    });
</script>

</body>
</html>
