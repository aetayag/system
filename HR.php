<?php
session_start();
require_once __DIR__ . '/../db.php';

// Provide a mysqli connection compatibility for legacy code that uses $conn
if (!isset($conn)) {
  if (!isset($host) || !isset($username) || !isset($password) || !isset($dbname)) {
    // try to load root db config if not already available
    if (file_exists(__DIR__ . '/../db.php')) {
      require_once __DIR__ . '/../db.php';
    }
  }
  $conn = new mysqli($host ?? 'localhost', $username ?? 'root', $password ?? '', $dbname ?? 'hr');
  if ($conn->connect_error) {
    die('MySQLi connection failed: ' . $conn->connect_error);
  }
}

// LOGOUT HANDLER
if(isset($_GET['logout'])){
    session_destroy();
    header("Location: index.php");
    exit();
}

/* ===== POST ANNOUNCEMENT WITH IMAGE ===== */
if(isset($_POST['post_announcement'])){
    $title = $_POST['title'];
    $message = $_POST['message'];
    $audience = $_POST['audience'];
    $department = ($audience === 'department') ? $_POST['department'] : NULL;

    $imageName = NULL;

    if (isset($_POST['post_announcement'])) {

    $title = $_POST['title'];
    $message = $_POST['message'];
    $audience = $_POST['audience'];
    $department = ($audience === 'department') ? $_POST['department'] : NULL;
    $imageName = NULL;

    /* === IMAGE UPLOAD (FIXED PATH ONLY) === */
    if (!empty($_FILES['image']['name'])) {

        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif','webp'];

        if (!in_array($ext, $allowed)) {
            die('Invalid image type');
        }

        $imageName = uniqid("announce_") . "." . $ext;

        // EXACT PATH YOU REQUESTED
        $uploadDir = 'C:/wamp64/www/hr system/uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            $uploadDir . $imageName
        );
    }

    $stmt = $conn->prepare(
        "INSERT INTO posting (title, message, audience, department, image)
         VALUES (?,?,?,?,?)"
    );
    $stmt->bind_param("sssss", $title, $message, $audience, $department, $imageName);
    $stmt->execute();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
}

// USER INFO (for sidebar)
$fullname = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'Juan Dela Cruz';
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'Human Resources';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>HR Dashboard</title>

<style>
:root{
  --green:#1e8d3d; --green-dark:#157033; --bg:#f6f8fa; --card:#ffffff;
  --text:#0f172a; --muted:#64748b; --radius:14px; --shadow:0 10px 25px rgba(0,0,0,.08); --soft:0 4px 12px rgba(0,0,0,.06);
}
*{box-sizing:border-box}
body{margin:0;font-family:Inter,system-ui,Arial;background:var(--bg);color:var(--text);}
.topbar{height:80px;background:linear-gradient(90deg,var(--green),var(--green-dark));display:flex;align-items:center;padding:0 24px;color:#fff;box-shadow:var(--shadow);}
.brand{display:flex;align-items:center;gap:14px;}
.logo{width:54px;height:54px;border-radius:50%;background:#fff;overflow:hidden;}
.logo img{width:100%;height:100%;object-fit:cover;}
.brand h1{font-size:16px;margin:0;}
.brand p{font-size:12px;margin:0;opacity:.9;}
.topbar-right{margin-left:auto;}
.btn{background:none;border:none;font-size:22px;color:white;cursor:pointer;}
.app{max-width:1200px;margin:auto;padding:26px;display:flex;}
.panel{flex:1;background:var(--card);border-radius:var(--radius);padding:24px;box-shadow:var(--soft);transition:.25s;}
.panel.shift{margin-left:270px;}
.hamburger{position:fixed;top:100px;left:20px;background:var(--green);color:white;border:none;border-radius:12px;padding:10px 14px;font-size:20px;cursor:pointer;z-index:3000;}
.sidebar{position:fixed;top:110px;left:20px;width:250px;background:var(--green);color:white;border-radius:var(--radius);padding:20px;box-shadow:var(--shadow);transform:scale(.9);opacity:0;pointer-events:none;transition:.25s;}
.sidebar.open{opacity:1;transform:scale(1);pointer-events:auto;}
.profile{text-align:center;padding-bottom:18px;border-bottom:1px solid rgba(255,255,255,.25);}
.avatar{width:82px;height:82px;border-radius:50%;background:#fff;margin:auto;overflow:hidden;}
.avatar img{width:100%;height:100%;object-fit:cover;}
.name{margin-top:10px;font-weight:700;}
.role{font-size:13px;opacity:.8;}
.menu{margin-top:20px;display:flex;flex-direction:column;gap:6px;}
.menu button{background:none;border:none;padding:12px;color:white;text-align:left;border-radius:10px;cursor:pointer;font-weight:600;}
.menu button:hover{background:rgba(255,255,255,.15);}
.cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;margin-top:20px;}
.card{background:#fff;border-radius:var(--radius);padding:18px;box-shadow:var(--soft);}
.card h4{margin:0;font-size:15px;}
.card p{margin:6px 0 0;color:var(--muted);}
.announce{display:flex;gap:10px;margin:18px 0;}
.announce input{flex:1;padding:12px;border-radius:12px;border:1px solid #ddd;}
.announce button{background:var(--green);color:white;border:none;padding:12px 18px;border-radius:12px;font-weight:700;}
.recent-announcement{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;margin-top:14px;}
.announce-card{background:#fff;border-radius:var(--radius);padding:16px;box-shadow:var(--soft);min-height:140px;}
.announce-text{font-size:14px;color:#334155;}
.modal{position:fixed;inset:0;background:rgba(0,0,0,.4);display:none;justify-content:center;align-items:center;z-index:5000;}
.record-modal{background:#fff;width:420px;max-width:90%;border-radius:18px;padding:26px;box-shadow:0 20px 40px rgba(0,0,0,.2);animation:pop .25s ease;}
@keyframes pop{from{transform:scale(.9);opacity:0}to{transform:scale(1);opacity:1}}
.record-header{display:flex;gap:14px;align-items:center;margin-bottom:20px;}
.record-header .icon{font-size:34px;background:#eaf7ef;color:#1e8d3d;padding:12px;border-radius:14px;}
.record-header h2{margin:0;font-size:18px;}
.record-header p{margin:4px 0 0;font-size:13px;color:#64748b;}
.record-body{display:flex;flex-direction:column;gap:16px;}
.record-step label{font-size:13px;font-weight:600;color:#0f6b2a;margin-bottom:6px;display:block;}
.record-step select, .record-step input, .record-step textarea{width:100%;padding:12px;border-radius:12px;border:1px solid #d1d5db;font-size:14px;outline:none;}
.record-step select:focus, .record-step input:focus, .record-step textarea:focus{border-color:#1e8d3d;box-shadow:0 0 0 3px rgba(30,141,61,.15);}
.record-footer{display:flex;justify-content:space-between;align-items:center;margin-top:24px;}
.actions{display:flex;gap:10px;}
.btn-primary{background:#1e8d3d;color:white;border:none;padding:10px 18px;border-radius:12px;font-weight:700;cursor:pointer;}
.btn-primary:disabled{opacity:.5;cursor:not-allowed;}
.btn-ghost{background:none;border:none;color:#475569;font-weight:600;cursor:pointer;}
.btn-light{background:#f1f5f9;border:none;padding:10px 14px;border-radius:12px;font-weight:600;cursor:pointer;}
</style>
</head>
<body>

<div class="topbar">
  <div class="brand">
    <div class="logo"><img src="plsp pic.jpg"></div>
    <div class="title">
      <h1>Pamantasan ng Lungsod ng San Pablo</h1>
      <p>Prime to Lead and Serve for Progress!</p>
    </div>
  </div>
  <div class="topbar-right">
    <button class="btn" id="notifBtn">🔔</button>
  </div>
</div>

<button class="hamburger" id="hamburger">☰</button>

<div class="app">
  <aside class="sidebar" id="sidebar">
    <div class="profile">
      <div class="name"><?= htmlspecialchars($fullname) ?></div>
      <div class="role"><?= htmlspecialchars($role) ?></div>
    </div>

    <nav class="menu">
      <button onclick="window.location.href='HR.php'">🏠 Dashboard</button>
      <button onclick="openRecordModal()"></button>
     <a href="javascript:void(0)" onclick="toggleDropdown('fileDropdown')" class="dropdown-toggle">
        201 File ▾
    </a>
    <ul class="dropdown-menu" id="fileDropdown">
<li><a href="../201Files/201file.php?type=fulltime">Full Time</a></li>
<li><a href="../201Files/201file.php?type=parttime">Part Time</a></li>
<li><a href="../201Files/201file.php?type=nonteaching">Non-Teaching</a></li>
      <button onclick="window.location.href='actionlog.php'">🕒 History</button>
      <button onclick="window.location.href='../inbox/inbox.php'">📥 Inbox</button>
      <button onclick="window.location.href='?logout=1'">🚪 Logout</button>
    </nav>
  </aside>

  <main class="panel">
    <h2 id="greetingText"></h2>

    <div class="announce">
      <input type="text" placeholder="Write an announcement..." disabled>
      <button onclick="openPostModal()">Post</button>
    </div>

    <!-- POST ANNOUNCEMENT MODAL -->
    <div class="modal" id="postModal">
      <div class="record-modal">
        <div class="record-header">
          <span class="icon">📢</span>
          <div><h2>Post Announcement</h2><p>Select who can see this post</p></div>
        </div>

        <form method="POST" enctype="multipart/form-data" class="record-body">
          <input type="hidden" name="post_announcement" value="1">
          <div class="record-step"><label>Title</label><input type="text" name="title" required></div>
          <div class="record-step"><label>Audience</label>
            <select name="audience" id="postAudience" required>
              <option disabled selected>Select</option>
              <option value="all">All Employees</option>
              <option value="department">Per Department</option>
            </select>
          </div>
          <div class="record-step" id="deptSelect" style="display:none">
            <label>Department</label>
            <select name="department">
              <option>CCSE</option><option>CTED</option><option>COA</option><option>CBAM</option>
              <option>CAS</option><option>CNAHS</option><option>CTHM</option>
            </select>
          </div>
          <div class="record-step"><label>Message</label><textarea name="message" rows="4" required></textarea></div>
          <div class="record-step"><label>Attachment (optional)</label><input type="file" name="image" accept="image/*"></div>

          <div class="record-footer">
            <button type="button" class="btn-light" onclick="closePostModal()">Cancel</button>
            <button type="submit" class="btn-primary">Post</button>
          </div>
        </form>
      </div>
    </div>

    

    <h3 style="margin-top:24px;">🔄 Recent Activity</h3>
    <div class="recent-announcement">
      <?php
      $q = mysqli_query($conn,"SELECT * FROM posting ORDER BY id DESC");
      while($a = mysqli_fetch_assoc($q)){
        $who = ($a['audience']==='all') ? "All Employees" : "Department: ".$a['department'];
      ?>
      <div class="announce-card">
        <?php if($a['image']){ ?>
        <img src="hr system/uploads/announcements/'/<?= $a['image'] ?>" style="width:100%;margin-top:10px;border-radius:12px">
        <?php } ?>
        <div class="announce-text">
          <strong><?= htmlspecialchars($a['title']) ?></strong><br><br>
          <?= nl2br(htmlspecialchars($a['message'])) ?><br><br>
          <small style="color:#64748b"><?= $who ?> • <?= date("M d, Y h:i A",strtotime($a['created_at'])) ?></small>
        </div>
      </div>
      <?php } ?>
    </div>

  </main>
</div>

<script>
// GREETING
(function(){
    const el = document.getElementById('greetingText');
    if(el){
        const hour = new Date().getHours();
        el.textContent = hour < 12 ? 'Good Morning !!' : (hour < 18 ? 'Good Afternoon !!' : 'Good Evening !!');
    }
})();

// SIDEBAR TOGGLE
const sidebar = document.getElementById("sidebar");
const hamburger = document.getElementById("hamburger");
const panel = document.querySelector(".panel");
hamburger.onclick = () => { sidebar.classList.toggle("open"); panel.classList.toggle("shift"); };

// POST MODAL
const postModal = document.getElementById("postModal");
const postAudience = document.getElementById("postAudience");
const deptSelect = document.getElementById("deptSelect");
function openPostModal(){ postModal.style.display="flex"; }
function closePostModal(){ postModal.style.display="none"; }
postAudience.onchange = () => { deptSelect.style.display = (postAudience.value==="department")?"block":"none"; };

// NOTIFICATIONS
const notifBtn = document.getElementById("notifBtn");
let notifBox;
notifBtn.onclick = () => {
  if(!notifBox){
    notifBox = document.createElement("div");
    notifBox.className="notif-box";
    notifBox.innerHTML=`<h4 style="margin:0 0 10px; color:#0f6b2a;">Notifications</h4><p style="font-size:14px; margin:0;">No new notifications.</p>`;
    document.body.appendChild(notifBox);
  }
  notifBox.classList.toggle("open");
};
document.addEventListener("click",(e)=>{if(notifBox && !notifBox.contains(e.target) && e.target!==notifBtn) notifBox.classList.remove("open");});
</script>

</body>
</html>
