<?php
session_start();
require_once __DIR__ . '/../db.php'; // PDO connection ($pdo)

// check employee session
$employee_id = $_SESSION['file201_id'] ?? null;
if (!$employee_id) {
    die("No employee session found.");
}

/* =========================
   SAVE CREDENTIAL (POST)
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $doctype    = $_POST['doctype'] ?? '';
    $typeofdoc  = $_POST['typeofdoc'] ?? '';
    $date       = $_POST['date'] ?? '';
    $issued_by  = $_POST['issued_by'] ?? '';

    // File upload
    $file_path = null;
    if (!empty($_FILES['file_upload']['name'])) {

        $upload_dir = __DIR__ . '/../uploads/cred/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $ext = pathinfo($_FILES['file_upload']['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg','jpeg','png','pdf'];

        if (!in_array(strtolower($ext), $allowed)) {
            die("Invalid file type.");
        }

        $file_name = time() . '_' . uniqid() . '.' . $ext;
        $target = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['file_upload']['tmp_name'], $target)) {
            $file_path = 'uploads/cred/' . $file_name;
        }
    }

    $stmt = $pdo->prepare("
        INSERT INTO cred 
        (employee_id, doctype, typeofdoc, date, issued_by, file_path)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $employee_id,
        $doctype,
        $typeofdoc,
        $date,
        $issued_by,
        $file_path
    ]);

    echo "Credential saved successfully!";
}

/* =========================
   FETCH CREDENTIALS
========================= */
$stmt = $pdo->prepare("SELECT * FROM cred WHERE employee_id = ? ORDER BY id DESC");
$stmt->execute([$employee_id]);
$credentials = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Credential Form</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
/* RESET + GLOBAL */
*{margin:0;padding:0;box-sizing:border-box;font-family:"Poppins",sans-serif;}
body{
  background:#f4f6f9;
  display:flex;
  min-height:100vh;
  color:#333;
}

/* LEFT PANEL */
.left-panel{
  width:38%;
  background:#0a7c12;
  color:#fff;
  padding:60px 40px;
  border-right:6px solid #ffffff;
  display:flex;
  flex-direction:column;
  justify-content:center;
  align-items:center;
  text-align:center;
  gap:12px;
}
.left-panel img{
  width:145px;
  height:145px;
  border-radius:50%;
  object-fit:cover;
  margin-bottom:12px;
}
.left-panel h1{
  font-size:26px;
  font-weight:600;
}
.left-panel p{
  font-size:15px;
  opacity:0.9;
}

/* RIGHT PANEL */
.right-panel{
  flex:1;
  padding:35px;
  overflow-y:auto;
}
.back-arrow{
  font-size:30px;
  cursor:pointer;
  margin-bottom:12px;
  user-select:none;
  color:#222;
}

/* FORMS CONTAINER */
#formsContainer{
  display:flex;
  flex-direction:column;
  gap:20px;
  position: relative;
  height: 550px;
  overflow: hidden;
}

/* FORM BOX */
.form-box{
  width:72%;
  background:#fff;
  padding:25px;
  border-radius:14px;
  border:1px solid #e1e1e1;
  box-shadow:0 4px 18px rgba(0,0,0,0.06);
  transition:0.3s ease;
  transform-origin:left center;
  position: absolute;
  top:0;
  left:0;
}

/* Animations */
.form-box.front{
  animation: slideIn 2.5s ease forwards;
}
.form-box.back{
  transform: translateX(-40px) scale(0.9);
  opacity: 0.7;
  transition: 2.5s ease;
}

/* keyframes */
@keyframes slideIn {
  0% { opacity: 0; transform: translateX(80px) scale(0.95); }
  40% { opacity: 1; transform: translateX(-10px) scale(1.03); }
  100% { opacity: 1; transform: translateX(0) scale(1); }
}

/* UPLOAD */
.upload-row{
  display:flex;
  gap:14px;
  margin-bottom:15px;
}
.preview-box{
  flex:1;
  height:130px;
  background:#fafafa;
  border:1px dashed #B3B3B3;
  border-radius:10px;
  display:flex;
  justify-content:center;
  align-items:center;
  overflow:hidden;
  color:#777;
  font-size:14px;
}
.preview-box img{
  width:100%;
  height:100%;
  object-fit:contain;
}

.upload-icon-btn{
  width:22%;
  height:130px;
  background:#ffffff;
  color:#fff;
  font-size:26px;
  display:flex;
  justify-content:center;
  align-items:center;
  border-radius:10px;
  cursor:pointer;
  position:relative;
  user-select:none;
}
.upload-icon-btn input[type="file"]{
  position:absolute;
  inset:0;
  width:100%;
  height:100%;
  opacity:0;
  cursor:pointer;
}

/* INPUTS */
.form-box input,
.form-box select{
  width:100%;
  padding:12px;
  border:1px solid #ccc;
  border-radius:8px;
  margin-bottom:12px;
  font-size:14px;
}

/* BUTTONS */
.buttons{
  display:flex;
  justify-content:flex-end;
  gap:10px;
}
button{
  padding:10px 16px;
  border:none;
  border-radius:8px;
  font-weight:600;
  cursor:pointer;
  transition:0.2s;
}
.upload-btn{
  background:#0a7c12;
  color:#fff;
}
.upload-btn:hover{
  background:#085d0d;
}
.add-btn{
  background:#007bff;
  color:#fff;
}
.add-btn:hover{
  background:#0060c9;
}

/* RESPONSIVE */
@media(max-width:900px){
  .left-panel{display:none;}
  .form-box{width:100%;}
}

/* Label above input */
.input-group{
  margin-bottom:12px;
  display:flex;
  flex-direction:column;
}
.input-group label{
  font-size:14px;
  font-weight:500;
  color:#333;
  margin-bottom:5px;
}

</style>

</head>
<body>

<!-- LEFT PANEL -->
<div class="left-panel">
  <img src="plsp pic.jpg" alt="logo">
  <h1>Pamantasan ng Lungsod ng San Pablo</h1>
  <p>Prime to Lead and Serve for Progress!</p>
</div>

<!-- RIGHT PANEL -->
<div class="right-panel">
  <div class="back-arrow" onclick="goBack()">&#8592;</div>

  <div id="formsContainer"></div>
</div>

<script>
let count = 0;

// This comes from PHP
const fetchedCredentials = <?= json_encode($credentials ?? []) ?>;

function addForm(data = {}) {
    count++;

    const container = document.getElementById("formsContainer");
    const oldForm = container.querySelector(".form-box.front");

    if (oldForm) {
        oldForm.classList.remove("front");
        oldForm.classList.add("back");
    }

    const form = document.createElement("div");
    form.className = "form-box front";
    const prevID = "prev" + count;

    const doctype = data.doctype ?? '';
    const typeofdoc = data.typeofdoc ?? '';
    const date = data.date ?? '';
    const issued_by = data.issued_by ?? '';
    const file_name = data.file_name ?? '';

    form.innerHTML = `
        <h2 style="margin-bottom:15px;font-weight:600;color:#222;">Credential Information</h2>

        <div class="upload-row">
            <div class="preview-box" id="${prevID}">
                ${file_name ? `<img src="uploads/${file_name}" alt="Credential File">` : 'No File'}
            </div>

            <label class="upload-icon-btn">
                📄
                <input type="file" accept="image/*" data-prev="${prevID}">
            </label>
        </div>

        <select>
            <option value="">Doc Type</option>
            <option ${doctype === 'COC' ? 'selected' : ''}>COC</option>
            <option ${doctype === 'Sick Leave' ? 'selected' : ''}>Sick Leave</option>
            <option ${doctype === 'CV' ? 'selected' : ''}>CV</option>
            <option ${doctype === 'Seminar Certificate' ? 'selected' : ''}>Seminar Certificate</option>
            <option ${doctype === 'Others' ? 'selected' : ''}>Others</option>
        </select>
        <input type="text" placeholder="Type of Document" value="${typeofdoc}">
        <input type="date" value="${date}">
        <input type="text" placeholder="Issued By" value="${issued_by}">

        <div class="buttons">
            <button class="upload-btn" type="button">Upload</button>
            <button class="add-btn" type="button">Add More</button>
        </div>
    `;

    container.appendChild(form);

    form.querySelector('input[type="file"]').addEventListener("change", function() {
        if (!this.files[0]) return;
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById(this.dataset.prev).innerHTML = `<img src="${e.target.result}">`;
        };
        reader.readAsDataURL(this.files[0]);
    });

    form.querySelector(".add-btn").addEventListener("click", () => addForm());

    const all = container.querySelectorAll(".form-box");
    if (all.length > 2) all[0].remove();
}

if (fetchedCredentials.length > 0) {
    fetchedCredentials.forEach(cred => addForm(cred));
} else {
    addForm();
}

// BACK BUTTON
function goBack() {
    if (document.referrer) {
        history.back(); // go back dynamically
    } else {
        window.location.href = "index.php"; // fallback
    }
}
</script>

</body>
</html>
