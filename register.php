<?php
session_start();
require_once __DIR__ . '/../db.php'; // PDO connection from root

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $pob = $_POST['pob'] ?? '';
    $sex = $_POST['sex'] ?? '';
    $age = $_POST['age'] ?? '';
    $email = $_POST['email'] ?? '';
    $civil_status = $_POST['civil_status'] ?? '';
    $address = $_POST['address'] ?? '';
    $emergency_contact = $_POST['emergency_contact'] ?? '';
    $emergency_contact_no = $_POST['emergency_contact_no'] ?? '';
    $employee_select = $_POST['employee_select'] ?? '';
    $position = $_POST['position'] ?? '';
    $department = $_POST['department'] ?? '';
    $employee_type = $_POST['employee_type'] ?? '';
    $per_office = $_POST['per_office'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $profile_photo = 'default.png';

        // Handle profile picture upload
        if (!empty($_FILES['profile_picture']['name'])) {
            $upload_dir = __DIR__ . '/uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $file_name = $_FILES['profile_picture']['name'];
            $file_tmp = $_FILES['profile_picture']['tmp_name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($file_ext, $allowed_ext)) {
                $new_file_name = uniqid('profile_') . '.' . $file_ext;
                $upload_path = $upload_dir . $new_file_name;

                if (move_uploaded_file($file_tmp, $upload_path)) {
                    $profile_photo = $new_file_name;
                } else {
                    $error = "Failed to upload profile picture!";
                }
            } else {
                $error = "Invalid file format! Only JPG, PNG, GIF allowed.";
            }
        }

        if (!isset($error)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO file_201 
                    (name, dob, pob, sex, age, email, civil_status, address, emergency_contact, emergency_contact_no, position, department, employee_type, per_office, password, profile_photo, employee_select)
                    VALUES 
                    (:name, :dob, :pob, :sex, :age, :email, :civil_status, :address, :emergency_contact, :emergency_contact_no, :position, :department, :employee_type, :per_office, :password, :profile_photo, :employee_select)");
                
                $stmt->execute([
                    ':name' => $name,
                    ':dob' => $dob,
                    ':pob' => $pob,
                    ':sex' => $sex,
                    ':age' => $age,
                    ':email' => $email,
                    ':civil_status' => $civil_status,
                    ':address' => $address,
                    ':emergency_contact' => $emergency_contact,
                    ':emergency_contact_no' => $emergency_contact_no,
                    ':position' => $position,
                    ':department' => $department,
                    ':employee_type' => $employee_type,
                    ':per_office' => $per_office,
                    ':password' => $password_hashed,
                    ':profile_photo' => $profile_photo,
                    ':employee_select' => $employee_select
                ]);

                $_SESSION['success'] = "Registration successful!";
                header("Location: ../index.php");
                exit();
            } catch(PDOException $e) {
                $error = "Registration failed: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PLSP Registration UI</title>
<style>
    body { margin:0; font-family: Arial, sans-serif; background: #e8f0e6; }
    .container { display: flex; height: 100vh; }
    .left { width: 40%; background: #197a2f; color: white; display: flex; flex-direction: column; align-items:center; justify-content:center; padding:20px; text-align:center; box-shadow:3px 0 10px rgba(0,0,0,0.2);}
    .left img { width:170px; margin-bottom:15px;}
    .left h1 { font-size:30px; margin:0; font-weight:bold;}
    .left p { margin-top:8px; font-size:16px; opacity:0.9; font-style:italic;}
    .right { width: 60%; background: white; padding: 40px 50px; overflow-y:auto;}
    .back-btn { font-size:26px; cursor:pointer; margin-bottom:10px; transition:0.2s;}
    .back-btn:hover { transform: translateX(-5px);}
    h2 { text-align:center; background:#197a2f; padding:12px 0; color:white; border-radius:25px; width:220px; font-size:22px; margin:0 auto 25px auto; box-shadow:0 3px 6px rgba(0,0,0,0.2);}
    .row { padding:0 10px; display:flex; gap:15px; margin-bottom:18px;}
    input, select { flex:1; padding:12px; border-radius:25px; border:1px solid #bfbfbf; background:#fafafa; transition:0.2s;}
    input:focus, select:focus { border-color:#197a2f; background:white; box-shadow:0 0 5px rgba(25,122,47,0.4); outline:none;}
    .register-btn { width:230px; padding:14px; background:#197a2f; color:white; border:none; border-radius:30px; cursor:pointer; display:block; margin:25px auto; font-size:17px; transition:0.2s; box-shadow:0 4px 8px rgba(0,0,0,0.2);}
    .register-btn:hover { background:#155f27; transform: scale(1.05);}
    .error { color:red; text-align:center; margin:10px 0; font-weight:bold;}
    .success { color:green; text-align:center; margin:10px 0; font-weight:bold;}
</style>
</head>
<body>

<div class="container">
    <div class="left">
        <img src="../hr/plsp pic.jpg" alt="PLSP Logo">
        <h1>Pamantasan ng Lungsod<br>ng San Pablo</h1>
        <p>Prime to Lead and Serve for Progress!</p>
    </div>

    <div class="right">
        <div class="back-btn" onclick="window.location.href='../index.php'">←</div>
        <h2>Register</h2>

        <?php if(isset($error)) { ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php } ?>
        <?php if(isset($_SESSION['success'])) { ?>
            <p class="success"><?= htmlspecialchars($_SESSION['success']) ?></p>
            <?php unset($_SESSION['success']); ?>
        <?php } ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <!-- Profile Picture Upload Section -->
            <div style="text-align:center; margin-bottom:25px;">
                <div style="width:120px; height:120px; margin:0 auto 15px; border-radius:50%; border:3px solid #197a2f; overflow:hidden; background:#f0f0f0; display:flex; align-items:center; justify-content:center;">
                    <img id="profilePreview" src="../hr/plsp pic.jpg" alt="Profile Preview" style="width:100%; height:100%; object-fit:cover;">
                </div>
                <label for="profile_picture" style="display:inline-block; padding:10px 20px; background:#197a2f; color:white; border-radius:25px; cursor:pointer; font-weight:bold;">
                    📷 Upload Photo
                </label>
                <input type="file" id="profile_picture" name="profile_picture" accept="image/*" hidden>
            </div>

            <!-- Personal Information Section -->
            <h3 style="color:#197a2f; margin-top:20px; border-bottom:2px solid #197a2f; padding-bottom:8px;">Personal Information</h3>

            <div class="row">
                <input type="text" name="name" placeholder="Full Name" required>
            </div>

            <div class="row">
                <input type="date" name="dob" placeholder="Date of Birth" required>
                <input type="text" name="pob" placeholder="Place of Birth" required>
            </div>

            <div class="row">
                <input type="number" name="age" placeholder="Age" min="18" max="100" required>
                <input type="text" name="sex" placeholder="Sex" list="sex-list" required>
                <datalist id="sex-list">
                    <option value="Male">
                    <option value="Female">
                    <option value="Other">
                </datalist>
            </div>

            <div class="row">
                <input type="text" name="address" placeholder="Address" required>
            </div>

            <div class="row">
                <input type="text" name="civil_status" placeholder="Civil Status" list="civil-status-list" required>
                <datalist id="civil-status-list">
                    <option value="Single">
                    <option value="Married">
                    <option value="Divorced">
                    <option value="Widowed">
                </datalist>
            </div>

            <div class="row">
                <input type="text" name="emergency_contact" placeholder="Emergency Contact Name" required>
                <input type="tel" name="emergency_contact_no" placeholder="Emergency Contact Number" required>
            </div>

            <!-- Employment Information Section -->
            <h3 style="color:#197a2f; margin-top:20px; border-bottom:2px solid #197a2f; padding-bottom:8px;">Employment Information</h3>

            <div class="row">
                <input type="text" name="employee_select" placeholder="Employee Selects" list="employee-selects" required>
                <datalist id="employee-selects">
                    <option value="Non-Teaching">
                    <option value="Teaching">
                </datalist>
                <input type="text" name="position" placeholder="Position" required>
            </div>

            <div class="row">
                <input type="text" name="department" placeholder="Department" list="department-list" required>
                <datalist id="department-list">
                    <option value="CCSE">
                    <option value="CBAM">
                    <option value="CNAHS">
                    <option value="CTED">
                    <option value="CAS">
                    <option value="COA">
                    <option value="CTHM">
                </datalist>
                <input type="text" name="employee_type" placeholder="Employee Type" list="employee-type" required>
                <datalist id="employee-type">
                    <option value="Full-Time">
                    <option value="Part-Time">
                    <option value="Non-Teaching">
                </datalist>
            </div>

            <div class="row">
                <input type="text" name="per_office" placeholder="Per Office" list="per-office">
                <datalist id="per-office">
                    <option value="OSAS">
                    <option value="Sinag">
                    <option value="Registrar">
                    <option value="EMIS">
                    <option value="Finance">
                    <option value="Admin">
                    <option value="HR">
                    <option value="Librarian">
                    <option value="Clinic">
                    <option value="Payroll">
                    <option value="Guidance">
                </datalist>
            </div>

            <!-- Credentials Upload Section -->
            <h3 style="color:#197a2f; margin-top:20px; border-bottom:2px solid #197a2f; padding-bottom:8px;">Credentials & Documents</h3>
            <div style="margin-bottom:18px; padding:0 10px;">
                <p style="margin:5px 0; color:#666; font-size:14px;">Upload your credentials and required documents</p>
                <a href="cred.php" style="display:inline-block; padding:8px 15px; background:#1f8a2b; color:white; border-radius:20px; text-decoration:none; font-size:14px;">📄 Upload Credentials</a>
            </div>

            <!-- Account Information Section -->
            <h3 style="color:#197a2f; margin-top:20px; border-bottom:2px solid #197a2f; padding-bottom:8px;">Account Information</h3>

            <div class="row">
                <input type="email" name="email" placeholder="Email Address" required>
            </div>

            <div class="row">
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            </div>

            <button type="submit" class="register-btn">Register</button>
        </form>

        <script>
            const profilePictureInput = document.getElementById('profile_picture');
            const profilePreview = document.getElementById('profilePreview');

            profilePictureInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        profilePreview.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        </script>
    </div>
</div>

</body>
</html>
