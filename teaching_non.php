<?php
session_start();
require_once "db.php";

// Fetch only employees with status 'Non-Teaching'
$stmt = $pdo->prepare("
    SELECT id, name, employee_id, position
    FROM file_201
    WHERE LOWER(TRIM(employee_select)) = 'non-teaching'
");
$stmt->execute();
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>HR Management</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: Arial, sans-serif; background: #f4f4f4; padding-top: 80px; }
.top-header {
    position: fixed; top: 0; left: 0; width: 100%;
    background: #1f8f2d; color: white; padding: 10px 20px;
    display: flex; align-items: center; gap: 15px; z-index: 100;
}
.header-logo { width: 50px; height: auto; }
.top-header h1 { font-size: 20px; line-height: 1.2; }
.top-header p { font-size: 14px; margin-top: 2px; }
a.back-link { display: inline-block; margin: 15px 20px 10px 20px; text-decoration: none; font-size: 24px; color: #333; }
.table-container { margin: 20px; border: 1px solid #999; border-radius: 6px; overflow: hidden; }
.table-scroll { max-height: 400px; overflow-y: auto; }
table { border-collapse: collapse; width: 100%; min-width: 800px; }
thead th { position: sticky; top: 0; background: #f2f2f2; color: black; z-index: 10; padding: 10px; border-bottom: 2px solid #555; }
th, td { border: 1px solid #444; padding: 8px; text-align: left; white-space: nowrap; }
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

<a href="javascript:history.back()" class="back-link">&larr;</a>

<div class="table-container">
    <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th>Instructor Name</th>
                    <th>Instructor ID</th>
                    <th>Position</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($employees): ?>
                    <?php foreach ($employees as $emp): ?>
                        <tr>
                            <td><?= htmlspecialchars($emp['name']) ?></td>
                            <td><?= htmlspecialchars($emp['employee_id']) ?></td>
                            <td><?= htmlspecialchars($emp['position']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align:center;">No records found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


</body>
</html>
