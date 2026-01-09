<?php
session_start();
require_once __DIR__ . '/../db.php'; // $pdo is defined in db.php

$employees = []; // initialize

try {
    $stmt = $pdo->prepare("SELECT * FROM file_201 ORDER BY id DESC");
    $stmt->execute();
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC); // fetch all rows as associative array
} catch (PDOException $e) {
    die("Query Error: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Schedule</title>

<style>
/* ===== GLOBAL ===== */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    background: #f4f6f5;
}

/* HEADER */
.top-header {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 5px 25px;
    background: #2e8b22;
    color: white;
}
.header-logo { width: 80px; }

/* TABLE */
.table-container {
    margin: 20px;
    background: white;
    padding: 10px;
    border-radius: 5px;
    position: relative;
}

.table-scroll {
    overflow-x: auto;
    overflow-y: auto;
    max-height: 420px;
    border: 1px solid #999;
}

table {
    border-collapse: collapse;
    min-width: 1500px;
    width: 100%;
}

thead th {
    position: sticky;
    top: 0;
    background: #f2f2f2;
    border-bottom: 2px solid #555;
    z-index: 2;
}

th, td {
    border: 1px solid #444;
    padding: 8px;
    white-space: nowrap;
}

tbody tr:nth-child(even) {
    background: #e6e6e6;
}

.selected-for-delete {
    background: #ffcccc !important;
}

button {
    padding: 4px 8px;
    cursor: pointer;
}

.header-controls {
    text-align: center;
}
</style>
</head>

<body>

<header class="top-header">
    <img src="../hr/plsp pic.jpg" class="header-logo">
    <div>
        <h1>Pamantasan ng Lungsod ng San Pablo</h1>
        <p>Prime to Lead and Serve for Progress!</p>
    </div>
</header>

<a href="javascript:history.back()" style="text-decoration:none; font-size:24px; margin-left:20px;">
  &larr;
</a>

<div class="table-container">
    <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Date of Birth</th>
                    <th>Sex</th>
                    <th>Age</th>
                    <th>Email</th>
                    <th>Civil Status</th>
                    <th>Address</th>
                    <th>Date Hired</th>
                    <th>Emergency Contact</th>
                    <th>Emergency Contact No</th>
                    <th>Employee Type</th>
                    <th>Position</th>
                    <th>Department</th>
                    <th class="header-controls">
                        <button onclick="enableEditAll()" id="editAllBtn">✎ Edit</button>
                    </th>
                </tr>
            </thead>

            <tbody>
            <?php if (!empty($employees)): ?>
                <?php foreach ($employees as $emp): ?>
                <tr data-id="<?= $emp['id'] ?>">
                    <td><?= htmlspecialchars($emp['name']) ?></td>
                    <td><?= $emp['dob'] ?></td>
                    <td><?= $emp['sex'] ?></td>
                    <td><?= $emp['age'] ?></td>
                    <td><?= htmlspecialchars($emp['email']) ?></td>
                    <td><?= $emp['civil_status'] ?></td>
                    <td><?= htmlspecialchars($emp['address']) ?></td>
                    <td><?= $emp['date_hired'] ?></td>
                    <td><?= htmlspecialchars($emp['emergency_contact']) ?></td>
                    <td><?= $emp['emergency_contact_no'] ?></td>
                    <td><?= $emp['employee_type'] ?></td>
                    <td><?= $emp['position'] ?></td>
                    <td><?= $emp['department'] ?></td>
                    <td></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="14" style="text-align:center;">No records found</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function enableEditAll() {
    const editBtn = document.getElementById('editAllBtn');
    const rows = document.querySelectorAll('tbody tr');
    const editMode = editBtn.textContent !== '💾 Done';
    rows.forEach(row => {
        for (let i = 0; i < row.cells.length - 1; i++) {
            row.cells[i].contentEditable = editMode;
            row.cells[i].style.backgroundColor = editMode ? '#fffbe6' : '';
        }
    });
    editBtn.textContent = editMode ? '💾 Done' : '✎ Edit';
}
</script>

</body>
</html>
