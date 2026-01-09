<?php
session_start();
require_once "db.php"; // Make sure $pdo is defined

// Fetch all records from comm_dept
$stmt = $pdo->query("SELECT * FROM comm_dept ORDER BY date_time DESC");
$comm_dept = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>PLSP Dashboard</title>
<style>
/* Reset */
* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: Arial, sans-serif;
    background: #f4f4f4;
    padding-top: 80px; /* Space for fixed header */
}

/* HEADER */
.top-header {
    position: fixed;
    top: 0; left: 0; width: 100%;
    background: #1f8f2d;
    color: white;
    padding: 10px 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    z-index: 100;
}

.header-logo { width: 50px; height: auto; }
.top-header h1 { font-size: 20px; line-height: 1.2; }
.top-header p { font-size: 14px; margin-top: 2px; }

/* BACK LINK */
a.back-link {
    display: inline-block;
    margin: 15px 20px 10px 20px;
    text-decoration: none;
    font-size: 24px;
    color: #333;
}

/* TABLE CONTAINER */
.table-container {
    margin: 20px;
    border: 1px solid #999;
    border-radius: 6px;
    overflow: hidden;
}

/* Scrollable table body */
.table-scroll {
    max-height: 400px; /* adjust as needed */
    overflow-y: auto;
}

/* TABLE */
table {
    border-collapse: collapse;
    width: 100%;
    min-width: 1000px; /* allows horizontal scroll if table is wide */
}

thead th {
    position: sticky;
    top: 0; /* sticks header to top of table scroll */
    background: #f2f2f2;
    color: black;
    z-index: 10;
    padding: 10px;
    border-bottom: 2px solid #555;
}

th, td {
    border: 1px solid #444;
    padding: 8px;
    text-align: left;
    white-space: nowrap;
}

tbody tr:nth-child(even) { background: #e6e6e6; }
</style>
</head>
<body>

<header class="top-header">
    <img src="../hr/plsp pic.jpg" class="header-logo" alt="PLSP Logo">
    <div>
        <h1>Pamantasan ng Lungsod ng San Pablo</h1>
        <p>Prime to Lead and Serve for Progress!</p>
    </div>
</header>

<a href="javascript:history.back()" class="back-link">&larr; </a>

<div class="table-container">
    <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th>Date/Time</th>
                    <th>Full Name</th>
                    <th>Activity</th>
                    <th>Requested By</th>
                    <th>Status</th>
                    <th>HR Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($comm_dept): ?>
                    <?php foreach ($comm_dept as $emp): ?>
                        <tr>
                            <td><?= htmlspecialchars($emp['date_time']) ?></td>
                            <td><?= htmlspecialchars($emp['full_name']) ?></td>
                            <td><?= htmlspecialchars($emp['activity']) ?></td>
                            <td><?= htmlspecialchars($emp['requested_by']) ?></td>
                            <td><?= htmlspecialchars($emp['status']) ?></td>
                            <td>
                                <?php
                                // Fetch hr_status from communication table
                                $stmt2 = $pdo->prepare("SELECT status FROM communication WHERE employee_id = ?");
                                $stmt2->execute([$emp['id']]);
                                $hr_status = $stmt2->fetchColumn();
                                echo $hr_status ? htmlspecialchars($hr_status) : 'Pending';
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center;">No records found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
