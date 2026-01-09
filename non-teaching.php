<?php
session_start();
require_once __DIR__ . '/../db.php';

/* ===== PROFILE IMAGE ===== */
$profile_img = 'default.png';
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT profile_photo FROM file_201 WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row && !empty($row['profile_photo'])) {
        $profile_img = '../register/uploads/' . $row['profile_photo'];
    }
}

/* ===== FETCH ANNOUNCEMENTS ===== */
$stmt = $pdo->prepare("
    SELECT id, title, message, image, created_at
    FROM posting
    WHERE created_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)
    ORDER BY created_at DESC
");
$stmt->execute();
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>PLSP Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Fonts & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:'Poppins',sans-serif; }
:root { --green:#1f8a2b; --gray:#f0f0f0; --white:#fff; --dark-gray:#555; --shadow:0 4px 12px rgba(0,0,0,0.08); }

/* ===== HEADER ===== */
.top-header {
    height:140px;
    background:var(--green);
    color:var(--white);
    display:flex;
    align-items:center;
    padding:20px 30px;
    box-shadow:0 4px 8px rgba(0,0,0,0.1);
}
.logo { display:flex; align-items:center; gap:20px; }
.logo img { width:90px; height:90px; border-radius:50%; border:2px solid #fff; object-fit:cover; }
.logo h1 { font-size:24px; font-weight:700; }
.logo span { font-size:14px; font-style:italic; }

/* ===== BODY LAYOUT ===== */
.wrapper { display:flex; height:calc(100vh - 140px); overflow:hidden; background:#f9f9f9; }

/* ===== SIDEBAR ===== */
.sidebar {
    width:250px;
    background:var(--gray);
    padding:25px 15px;
    display:flex;
    flex-direction:column;
    align-items:center;
    position:relative;
    overflow-y:auto;
}
.profile { margin-bottom:30px; }
.profile-circle {
    width:120px;
    height:120px;
    border-radius:50%;
    overflow:hidden;
    border:2px solid #ccc;
    cursor:pointer;
    transition: transform 0.2s;
}
.profile-circle:hover { transform:scale(1.05); }
.profile-circle img { width:100%; height:100%; object-fit:cover; }

/* SIDEBAR ICONS */
.sidebar-icon {
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
.sidebar-icon:hover { background:#e0e0e0; }
.sub-buttons { width:100%; display:flex; flex-direction:column; margin-bottom:10px; gap:10px; max-height:0; overflow:hidden; opacity:0; transition:max-height 0.4s ease, opacity 0.4s ease; }
.sub-buttons.show { max-height:500px; opacity:1; }
.sub-buttons a { display:flex; align-items:center; gap:10px; padding:10px; text-align:left; border-radius:15px; background:#e8e8e8; font-weight:500; transition:transform 0.2s; }
.sub-buttons a:hover { transform:scale(1.03); }
.sub-buttons a .inbox-dot { position:absolute; top:8px; right:12px; width:10px; height:10px; background:red; border-radius:50%; display:none; }

/* ===== MAIN CONTENT ===== */
.main { flex:1; padding:30px; overflow-y:auto; }
.greeting { font-size:36px; font-weight:700; color:var(--green); margin-bottom:30px; font-family:"Times New Roman", serif; }

/* ===== ANNOUNCEMENTS ===== */
.announcement-section { margin-top:10px; }
.announcement-header { display:flex; align-items:center; gap:10px; font-weight:600; margin-bottom:15px; }
.announcement-header h2 { font-size:22px; }
.announcement-cards { display:flex; gap:25px; flex-wrap:wrap; }
.announcement-card { width:300px; background:#fff; border-radius:15px; padding:15px; box-shadow:var(--shadow); transition: transform 0.2s; }
.announcement-card:hover { transform:translateY(-5px); }
.announcement-preview img { width:100%; height:180px; object-fit:cover; border-radius:12px; margin-bottom:10px; }
.memo-title { font-size:17px; font-weight:700; margin-bottom:10px; }
.announcement-preview p { color:var(--dark-gray); font-size:14.5px; line-height:1.6; margin-bottom:10px; }
.announcement-preview small { color:#999; font-size:13px; }

/* ===== NOTIFICATION POPUP ===== */
#notifPopup {
    display:none;
    background:#fff;
    border-radius:15px;
    box-shadow:0 8px 20px rgba(0,0,0,0.15);
    padding:20px;
    max-width:400px;
    position:fixed;
    top:160px;
    right:30px;
    z-index:1000;
    animation:popupFade 0.4s ease-in-out;
    overflow:hidden;
}
@keyframes popupFade {
    from {opacity:0; transform:translateY(-10px);}
    to {opacity:1; transform:translateY(0);}
}
#notifPopupContent img {
    width:100%;
    height:180px;
    object-fit:cover;
    border-radius:12px;
    margin-bottom:15px;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
}
#notifPopup h4 { margin:0 0 10px 0; color:#1f8a2b; font-size:18px; font-weight:700; }
#notifPopup p { margin:0 0 10px 0; color:#555; font-size:15px; line-height:1.6; }
#notifPopup small { color:#999; font-size:13px; }
#notifPopup button {
    background:none; border:none; font-size:22px; color:#1f8a2b; cursor:pointer; font-weight:600;
    position:absolute; top:10px; right:10px; line-height:1;
}
</style>
</head>
<body>

<!-- HEADER -->
<div class="top-header">
    <div class="logo">
        <img src="../hr/plsp pic.jpg" class="header-logo" alt="PLSP Logo">
        <div>
            <h1>Pamantasan ng Lungsod ng San Pablo</h1>
            <span>Prime to Lead and Serve for Progress!</span>
        </div>
    </div>
    <div style="margin-left:auto; display:flex; align-items:center; gap:15px;">
        <button id="notifBell" style="background:none; border:none; font-size:28px; cursor:pointer; color:#fff;">
            🔔 <span id="notifCount" style="background:red; color:white; border-radius:50%; padding:2px 6px; font-size:12px; margin-left:5px; display:<?= count($notifications) > 0 ? 'inline-block' : 'none' ?>;"><?= count($notifications) ?></span>
        </button>
    </div>
</div>

<!-- BODY -->
<div class="wrapper">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="profile">
            <div class="profile-circle" onclick="window.location.href='../profile/profile.php'">
                <img src="<?= htmlspecialchars($profile_img) ?>" alt="Profile Picture">
            </div>
        </div>

        <div class="sidebar-icon" onclick="toggleDropdown('forms')">
            <i data-feather="file-text"></i>
            <span class="icon-label">Forms</span>
        </div>
        <div class="sub-buttons" id="forms">
            <a href="sick.php"><i data-feather="thermometer"></i> Sick Leave</a>
            <a href="coc.php"><i data-feather="file"></i> COC</a>
        </div>

        <div class="sidebar-icon" onclick="window.location.href='../inbox/inbox.php'">
            <i data-feather="inbox"></i>
            <span class="icon-label">Inbox</span>
        </div>

        <div class="sub-buttons" id="inbox" style="display:none;">
            <a href="../inbox/inbox.php"><i data-feather="edit"></i> Drafts <span class="inbox-dot" id="dot-drafts"></span></a>
            <a href="../inbox/inbox.php"><i data-feather="send"></i> Sent <span class="inbox-dot" id="dot-sent"></span></a>
            <a href="../inbox/inbox.php"><i data-feather="alert-circle"></i> Spam <span class="inbox-dot" id="dot-spam"></span></a>
            <a href="../inbox/inbox.php"><i data-feather="trash-2"></i> Trash <span class="inbox-dot" id="dot-trash"></span></a>
        </div>

        <div class="sidebar-icon" onclick="toggleDropdown('logout')">
            <i data-feather="logout"></i>
            <span class="icon-label">Logout</span>
        </div>
        <div class="sub-buttons" id="logout">
            <a href="Yes.php"><i data-feather="check"></i> Yes</a>
            <a href="No.php"><i data-feather="x"></i> No</a>
        </div>
    </div>

    <!-- NOTIFICATION POPUP -->
    <div id="notifPopup">
        <button onclick="closeNotifPopup()">×</button>
        <div id="notifPopupContent"></div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main">
        <div id="greetingText" class="greeting"></div>

        <!-- ANNOUNCEMENTS -->
        <div class="announcement-section">
            <div class="announcement-header">
                <h2>Recent Announcements</h2>
            </div>
            <div class="announcement-cards">
                <?php foreach ($notifications as $notif): ?>
                    <div class="announcement-card">
                        <div class="announcement-preview">
                            <?php if (!empty($notif['image'])): ?>
                                <img src="<?= htmlspecialchars(str_replace(' ', '%20', '../hr system/uploads/' . $notif['image'])) ?>" alt="Announcement Image">
                            <?php endif; ?>
                            <div class="memo-title"><?= htmlspecialchars($notif['title']) ?></div>
                            <p><?= nl2br(htmlspecialchars($notif['message'])) ?></p>
                            <small><?= date("M d, Y h:i A", strtotime($notif['created_at'])) ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/../inc/announcements.php'; ?>

<script>
feather.replace();

/* GREETING */
(function(){
    const el = document.getElementById('greetingText');
    if(el){
        const hour = new Date().getHours();
        el.textContent = hour < 12 ? 'Good Morning !!' : (hour < 18 ? 'Good Afternoon !!' : 'Good Evening !!');
    }
})();

/* DROPDOWN */
function toggleDropdown(id){
    const el = document.getElementById(id);
    el.classList.toggle('show');
}

/* NOTIFICATION POPUP */
const notifBell = document.getElementById('notifBell');
const notifPopup = document.getElementById('notifPopup');
const notifPopupContent = document.getElementById('notifPopupContent');
const notifications = <?= json_encode($notifications) ?>;

function showNotifPopup() {
    if(notifications.length === 0) return;
    const notif = notifications[0];
    const date = new Date(notif.created_at).toLocaleDateString('en-US', {
        month:'short', day:'numeric', year:'numeric', hour:'2-digit', minute:'2-digit'
    });
    let imgHtml = '';
    if(notif.image) {
        const imgPath = ('../hr system/uploads/' + notif.image).replace(/ /g, '%20');
        imgHtml = `<img src="${imgPath}" alt="Notification Image">`;
    }
    notifPopupContent.innerHTML = `${imgHtml}<h4>${notif.title}</h4><p>${notif.message}</p><small>${date}</small>`;
    notifPopup.style.display = 'block';
}

function closeNotifPopup() {
    notifPopup.style.display = 'none';
    const notifCount = document.getElementById('notifCount');
    if(notifCount) notifCount.style.display='none';
}

notifBell.addEventListener('click', e => {
    e.stopPropagation();
    notifPopup.style.display = (notifPopup.style.display==='block')?'none':'block';
});

document.addEventListener('click', e => {
    if(notifPopup.style.display==='block' && !notifPopup.contains(e.target) && !notifBell.contains(e.target)){
        closeNotifPopup();
    }
});

document.addEventListener('keydown', e => { if(e.key==='Escape') closeNotifPopup(); });
document.querySelectorAll('.sidebar-icon, .sub-buttons a').forEach(el => {
    el.addEventListener('click', closeNotifPopup);
});
</script>
</body>
</html>
