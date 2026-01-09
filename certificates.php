<?php
// certificates.php
$conn = mysqli_connect("localhost", "root", "", "hr");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get instructor_id from URL
$employee_id = $_GET['instructor_id'] ?? 0;
if (!$employee_id) {
    die("Invalid employee");
}

// ===============================
// FETCH EMPLOYEE INFO (file_201)
// ===============================
$emp_sql = "SELECT email, name FROM file_201 WHERE id = ?";
$emp_stmt = mysqli_prepare($conn, $emp_sql);
mysqli_stmt_bind_param($emp_stmt, "i", $employee_id);
mysqli_stmt_execute($emp_stmt);
$emp_result = mysqli_stmt_get_result($emp_stmt);
$employee = mysqli_fetch_assoc($emp_result);

$email = $employee['email'] ?? 'unknown@email.com';
$name = $employee['name'] ?? 'Unknown Employee';

// ===============================
// FETCH CREDENTIALS (cred)
// ===============================
$sql = "SELECT * FROM cred WHERE employee_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $employee_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($name) ?> - Credentials</title>
<style>
body{margin:0;font-family:Arial,sans-serif;background:#f5f5f5}
/* HEADER */
.top-header{display:flex;align-items:center;gap:15px;padding:10px 20px;background:#2e8b22;color:white}
.header-logo{width:70px;border-radius:50%}
.back-link{margin:15px 20px;display:inline-block;text-decoration:none;font-size:18px;color:#2e8b22;font-weight:bold}
/* MAIN CONTAINER */
.container{background:white;margin:20px;padding:25px;border-radius:15px}
.inner{display:flex;gap:20px;overflow-x:auto}
.card{width:220px;background:#e0e0e0;border-radius:15px;padding:15px;flex-shrink:0;position:relative}
.card-header{display:flex;justify-content:space-between;font-size:14px;margin-bottom:10px}
.card img{width:100%;height:120px;border-radius:10px;object-fit:cover;background:#fff}
.card-dots{cursor:pointer;font-size:18px;color:#2e8b22}
/* MODAL */
.modal{display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);align-items:center;justify-content:center;z-index:1000}
.modal-content{background:#fff;width:600px;padding:30px;border-radius:20px;display:flex;gap:20px;align-items:center}
.modal-actions{margin-top:20px;display:flex;gap:15px}
.btn{padding:10px 25px;border:none;border-radius:8px;cursor:pointer}
.send{background:black;color:white}
.cancel{background:#ddd}
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

<a href="javascript:history.back()" class="back-link">&larr; Back</a>

<div class="container">
    <h2><?= htmlspecialchars($name) ?> - Credentials</h2>

    <div class="inner">
    <?php if(mysqli_num_rows($result) > 0): ?>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="card">
                <div class="card-header">
                    <span><?= htmlspecialchars($row['doctype']) ?></span>
                    <span class="card-dots"
                        onclick="openModal(
                            '<?= htmlspecialchars($row['doctype']) ?>',
                            '<?= htmlspecialchars($email) ?>',
                            '<?= htmlspecialchars($row['file']) ?>'
                        )">&#8942;</span>
                </div>
                <?php if ($row['file']): ?>
                    <img src="../<?= $row['file'] ?>" alt="<?= htmlspecialchars($row['doctype']) ?>">
                <?php else: ?>
                    <img src="no-file.png" alt="No file">
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No credentials uploaded for this employee.</p>
    <?php endif; ?>
    </div>
</div>

<!-- MODAL -->
<div id="confirmModal" class="modal">
  <div class="modal-content">
    <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" width="70" alt="">
    <div>
      <p id="modalText" style="font-size:18px;"></p>

      <div class="modal-actions">
        <button class="btn cancel" onclick="closeModal()">Cancel</button>
        <a id="downloadBtn" class="btn send" download>Download</a>
      </div>
    </div>
  </div>
</div>

<script>
function openModal(doctype, email, filePath){
    document.getElementById('modalText').innerHTML =
        `You are about to download the <b>${doctype}</b>. Once you select ‘Download,’ a notification will be sent to <b>${email}</b>.`;
    document.getElementById('downloadBtn').href = "../" + filePath;
    document.getElementById('confirmModal').style.display = 'flex';
}

function closeModal(){
    document.getElementById('confirmModal').style.display = 'none';
}
</script>

</body>
</html>
