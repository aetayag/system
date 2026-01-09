<?php require_once __DIR__ . '/../db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Message View — HR</title>

<style>
:root {
  --green: #1e8d3d;
  --green-dark: #157033;
  --bg: #f3f4f6;
  --card: #fff;
  --text: #111;
  --muted: #64748b;
  --radius: 12px;
  --shadow: 0 10px 25px rgba(0,0,0,.08);
  --soft: 0 4px 12px rgba(0,0,0,.06);
}

/* Basic Reset */
*{box-sizing:border-box}
body{margin:0;font-family:Inter,Arial,sans-serif;background:var(--bg);color:var(--text)}

/* Topbar */
.topbar{
  background:linear-gradient(90deg,var(--green),var(--green-dark));
  color:#fff;padding:16px 24px;font-weight:700;font-size:16px;
  box-shadow:var(--soft);
}

/* Layout */
.layout{display:flex;height:calc(100vh - 56px);}

/* ===== SIDEBAR FROM NON-TEACHING ===== */
.sidebar{
  width:250px;
  background:#f3f4f6;
  color:#111;
  padding:25px 15px;
  display:flex;
  flex-direction:column;
  align-items:center;
  position:relative;
  overflow-y:auto;
  border-radius:var(--radius);
  box-shadow:var(--soft);
  margin:12px;
  border:2px solid #333;
}
.profile{ margin-bottom:30px; text-align:center; }
.profile-circle{
  width:120px;
  height:120px;
  border-radius:50%;
  overflow:hidden;
  border:2px solid #ccc;
  cursor:pointer;
  margin-bottom:15px;
}
.profile-circle img{ width:100%; height:100%; object-fit:cover; border-radius:50%; }
.sidebar-icon{
  width:100%;
  margin-bottom:10px;
  cursor:pointer;
  display:flex;
  align-items:center;
  gap:12px;
  background:#fff;
  padding:10px 15px;
  border-radius:70px;
  box-shadow:var(--shadow);
  transition:all 0.3s;
  font-weight:500;
}
.sidebar-icon:hover{ background:#e0e0e0; }
.sub-buttons{
  width:100%;
  display:flex;
  flex-direction:column;
  margin-bottom:10px;
  gap:10px;
  max-height:0;
  overflow:hidden;
  opacity:0;
  transition:max-height 0.4s ease, opacity 0.4s ease;
}
.sub-buttons.show{ max-height:500px; opacity:1; }
.sub-buttons a{
  display:flex;
  align-items:center;
  gap:10px;
  padding:10px;
  text-align:left;
  border-radius:15px;
  background:#e8e8e8;
  font-weight:500;
  transition:transform 0.2s;
}
.sub-buttons a:hover{ transform:scale(1.03); }
.sub-buttons a .inbox-dot{
  position:absolute;
  top:8px;
  right:12px;
  width:10px;
  height:10px;
  background:red;
  border-radius:50%;
  display:none;
}

/* Sidebar footer */
.sidebar-footer{ margin-top:auto; font-size:12px; opacity:.8; }

/* Main Content */
.content{ flex:1; padding:24px; overflow:auto; }

/* Message Viewer */
.viewer{
  background:#e0e0e0;
  border-radius:12px;
  padding:20px;
  display:flex;
  flex-direction:column;
  gap:16px;
  box-shadow:var(--soft);
  position:relative;
  min-height:400px;
}
.viewer-header{
  display:flex;
  align-items:center;
  justify-content:space-between;
}
.viewer-header-left{
  display:flex;
  align-items:center;
  gap:10px;
}
.viewer-header .avatar{
  width:40px;
  height:40px;
  border-radius:50%;
  background:#fff;
  flex-shrink:0;
}
.sender-info{
  display:flex;
  flex-direction:column;
  gap:2px;
}
.sender-info .from{ font-weight:600; font-size:14px; }
.sender-info .email{ font-size:12px; color:#666; }
.sender-info .department{ font-size:12px; color:#666; }

/* BACK BUTTON */
.back-btn{
  background:none;
  border:none;
  cursor:pointer;
  font-size:16px;
  color:var(--green-dark);
  font-weight:600;
  margin-right:10px;
}
.back-btn:hover{ text-decoration:underline; }

.viewer-body{ flex:1; display:flex; flex-direction:column; gap:12px; font-size:14px; line-height:1.5; overflow-y:auto; padding-bottom:80px; }
.approve-decline{
  display:flex;
  flex-direction:column;
  gap:14px;
  position:absolute;
  left:50%;
  top:70%;
  transform:translate(-50%, -50%);
  z-index:10;
}
.approve-decline button{
  padding:10px 16px;
  border-radius:8px;
  border:0;
  cursor:pointer;
  font-weight:700;
  font-size:14px;
  min-width:140px;
}
.approve-decline button.approve{
  background:#d4edda;
  color:#155724;
  box-shadow: 0 8px 24px rgba(21,87,36,0.16);
}
.approve-decline button.approve:hover{
  background:#c3e6cb;
  box-shadow: 0 12px 30px rgba(21,87,36,0.22);
}
.approve-decline button.decline{
  background:#f8d7da;
  color:#721c24;
  box-shadow: 0 8px 24px rgba(114,28,36,0.16);
}
.approve-decline button.decline:hover{
  background:#f5c6cb;
  box-shadow: 0 12px 30px rgba(114,28,36,0.22);
}

/* Responsive */
@media (max-width:900px){ .sidebar{display:none;} }
</style>
</head>
<body>

<div class="topbar">Pamantasan ng Lungsod ng San Pablo — HR Inbox</div>
<div class="layout">

<!-- SIDEBAR FROM NON-TEACHING -->
<aside class="sidebar">
  <div class="profile">
    <div class="profile-circle" onclick="window.location.href='profile.php'">
      <img src="../register/uploads/default.png" alt="Profile Picture">
    </div>
    <div class="name">HR Admin</div>
    <div class="email">hr@plsp.edu.ph</div>
  </div>

  <div class="sidebar-icon" onclick="toggleDropdown('forms')">
    <span>Forms</span>
  </div>
  <div class="sub-buttons" id="forms">
    <a href="sick.php">Sick Leave</a>
    <a href="coc.php">COC</a>
  </div>

  <div class="sidebar-icon" onclick="window.location.href='inbox.php'">
    <span>Inbox</span>
  </div>

  <div class="sub-buttons" id="logout">
    <a href="logout.php">Logout</a>
  </div>

  <div class="sidebar-footer">Version 1.0</div>
</aside>

<!-- MAIN CONTENT -->
<main class="content">
  <div class="viewer">
    <div class="viewer-header">
      <div class="viewer-header-left">
        <button class="back-btn" onclick="goBack()">← Back</button>
        <div class="avatar"></div>
        <div class="sender-info">
          <div class="from" id="viewer-from">John Marlon Mendoza</div>
          <div class="email" id="viewer-email">jmmendoza@gmail.com</div>
          <div class="department" id="viewer-dept">HR Department</div>
        </div>
      </div>
    </div>

    <div class="viewer-body" id="viewer-body">
      <p class="empty">A profile edit request has been submitted for the account associated with jmmendoza@gmail.com</p>
    </div>

    <div class="approve-decline">
      <button class="approve" id="approve-btn">✅ Approve</button>
      <button class="decline" id="decline-btn">❌ Decline</button>
    </div>
  </div>
</main>

<script>
function toggleDropdown(id){
  const el=document.getElementById(id);
  el.classList.toggle('show');
}

// BACK BUTTON FUNCTION
function goBack(){
  document.getElementById("viewer-body").innerHTML = '<p class="empty">Select a message to view</p>';
  document.getElementById("viewer-from").textContent = "";
  document.getElementById("viewer-email").textContent = "";
  document.getElementById("viewer-dept").textContent = "";
}
</script>
</body>
</html>
