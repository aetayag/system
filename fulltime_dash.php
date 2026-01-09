<?php
session_start();
require_once __DIR__ . '/../db.php';

// Fetch logged-in user profile picture from file_201
$profile_img = 'default.png'; // fallback
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT profile_photo FROM file_201 WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row && !empty($row['profile_photo'])) {
        $profile_img = '../register/uploads/' . $row['profile_photo'];
    }
}

// Fetch new notifications (announcements posted in last 30 minutes)
$notifications = [];
$stmt = $pdo->prepare("SELECT id, title, message, image, created_at FROM posting WHERE created_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE) ORDER BY created_at DESC");
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
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}

:root{
    --green:#1f8a2b;
    --gray:#f0f0f0;
}

/* ===== HEADER ===== */
.top-header{
    height:140px;
    background:var(--green);
    color:#fff;
    display:flex;
    align-items:center;
    padding:20px 30px;
}

.logo{
    display:flex;
    align-items:center;
    gap:20px;
}

.logo img{
    width:90px;
    height:90px;
    border-radius:50%;
    border:2px solid #fff;
}

.logo h1{font-size:24px;}
.logo span{font-size:14px;font-style:italic;}

/* ===== LAYOUT ===== */
.wrapper{
    display:flex;
    height:calc(100vh - 140px);
    overflow: hidden;
}

/* ===== SIDEBAR ===== */
.sidebar{
    width:250px;
    background:var(--gray);
    padding:25px 15px;
    display:flex;
    flex-direction:column;
    align-items:center;
    position:relative;
    z-index:2;
    transition: width 0.3s;
    overflow-y:auto;
}

.profile{margin-bottom:30px;}
.profile-circle{
    width:120px;
    height:120px;
    border:2px solid #ccc;
    border-radius:50%;
    overflow:hidden;
    display:flex;
    justify-content:center;
    align-items:center;
    margin-bottom:20px;
    cursor:pointer;
}
.profile-circle img{
    width:100%; 
    height:100%; 
    object-fit:cover; 
    border-radius:50%;
}

/* SIDEBAR MAIN BUTTONS */
.sidebar-icon{
    width:100%;
    margin-bottom:10px;
    cursor:pointer;
    display:flex;
    flex-direction:row;
    justify-content:flex-start;
    align-items:center;
    gap:12px;
    transition:all 0.3s;
    background:#fff;
    padding:8px 12px;
    border-radius:70px;
    box-shadow:0 2px 6px rgba(0,0,0,0.1);
    text-decoration:none;
    color:inherit;
}

/* DROPDOWN SECTIONS */
.sub-buttons{
    width:100%;
    display:flex;
    flex-direction:column;
    margin-bottom:10px;
    gap:10px;
    max-height:0;
    overflow:hidden;
    opacity:0;
    transition: max-height 0.4s ease, opacity 0.4s ease;
}
.sub-buttons.show{
    max-height:500px;
    opacity:1;
}

.sub-buttons a{
    display:flex;
    align-items:center;
    gap:10px;
    padding:10px;
    text-align:left;
    border-radius:15px;
    background:#e8e8e8;
    font-weight:500;
    position:relative;
    transition: transform 0.2s;
}
.sub-buttons a:hover{
    transform: scale(1.03);
}

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

/* ===== MAIN CONTENT ===== */
.main{
    flex:1;
    padding:30px;
    overflow-y:auto;
}

.greeting{
    font-size:32px;
    font-weight:700;
    color:var(--green);
    margin-bottom:25px;
    font-family:"Times New Roman", serif;
}

/* ===== CARDS ===== */
.cards{
    display:flex;
    gap:20px;
    flex-wrap:wrap;
}

/* REPORT */
.report{
    flex:1;
    background:#e5e5e5;
    border-radius:12px;
    padding:20px;
    position:relative;
    min-height:420px;
}

.report-title{
    position:absolute;
    bottom:15px;
    left:50%;
    transform:translateX(-50%);
    background:#fff;
    padding:8px 18px;
    border-radius:30px;
    font-size:18px;
    box-shadow:0 2px 6px rgba(0,0,0,.2);
}

.report canvas{
    width:100% !important;
    height:100% !important;
}

/* SCHEDULE */
.schedule{
    flex:1;
    background:#dcdcdc;
    border-radius:12px;
    padding:10px;
    overflow:auto;
}

.schedule table{
    width:100%;
    border-collapse:collapse;
    background:#eee;
}

.schedule th,
.schedule td{
    border:1px solid #000;
    padding:8px;
    text-align:center;
    font-size:14px;
}

.schedule th{
    background:#cfcfcf;
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

    <!-- LOGOUT BUTTON -->
    <a href="../logout.php" class="sidebar-icon" style="margin-top:auto;">
        <i data-feather="log-out"></i>
        <span class="icon-label">Logout</span>
    </a>
</div>


    <!-- MAIN -->
    <div class="main">

        <div id="greetingText" class="greeting"></div>

        <!-- NOTIFICATION POPUP (INLINE) -->
        <div id="notifPopup" style="display:none; background:#f0f8f4; border-left:5px solid #1f8a2b; border-radius:10px; padding:20px; margin-bottom:25px; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                <h3 style="margin:0; color:#1f8a2b;">📢 New Announcement</h3>
                <button onclick="closeNotifPopup()" style="background:none; border:none; font-size:24px; cursor:pointer; color:#1f8a2b;">×</button>
            </div>
            <div id="notifPopupContent"></div>
        </div>

        <div class="cards">

            <!-- REPORT -->
            <div class="report">
                <canvas id="reportChart"></canvas>
                <div class="report-title">Report</div>
            </div>

            <!-- SCHEDULE -->
            <div class="schedule">
                <table>
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Mon</th>
                            <th>Tue</th>
                            <th>Wed</th>
                            <th>Thu</th>
                            <th>Fri</th>
                            <th>Sat</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
require_once __DIR__ . '/../db.php';
$days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
$stmt = $pdo->prepare(
    "SELECT c.*, f.name AS instructor_name
    FROM class c
    LEFT JOIN file_201 f ON f.id = c.instructor_id
    WHERE f.employee_type LIKE 'full%'
    ORDER BY STR_TO_DATE(SUBSTRING_INDEX(c.time,'-',1),'%H:%i') ASC"
);
$stmt->execute();
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// build unique times
$times = [];
foreach ($classes as $cl) {
    if (!empty($cl['time'])) $times[$cl['time']] = true;
}
$times = array_keys($times);
usort($times, function($a,$b){
    return strtotime(explode('-',$a)[0]) <=> strtotime(explode('-',$b)[0]);
});

// build grid [time][day] => class info
$grid = [];
foreach ($times as $time) {
    foreach ($days as $d) $grid[$time][$d] = '';
}
foreach ($classes as $cl) {
    $time = $cl['time'];
    $day = $cl['day'] ?? '';
    $grid[$time][$day] = htmlspecialchars((($cl['coursecode_title'] ?? '') . ($cl['room'] ? ' / ' . $cl['room'] : '') . ($cl['instructor_name'] ? ' / ' . $cl['instructor_name'] : '')));
}

// compute schedule counts per instructor
$instructorStats = [];
foreach ($classes as $cl) {
    $iid = $cl['instructor_id'];
    if (!isset($instructorStats[$iid])) {
        $instructorStats[$iid] = [
            'name' => $cl['instructor_name'] ?? '(Unknown)',
            'count' => 0,
            'years' => []
        ];
    }
    $instructorStats[$iid]['count']++;
    if (!empty($cl['year_section'])) $instructorStats[$iid]['years'][$cl['year_section']] = true;
}

// render a compact summary of instructors with multiple schedules
if (!empty($instructorStats)) {
    echo '<div style="margin:10px 0;">';
    foreach ($instructorStats as $iid => $s) {
        $multi = $s['count'] > 1 ? ' <strong style="color:#c00;">(Multiple schedules: ' . (int)$s['count'] . ')</strong>' : '';
        echo '<div style="font-size:14px; margin-bottom:4px;">' . htmlspecialchars($s['name']) . $multi . '</div>';
    }
    echo '</div>';
}

if (empty($times)) {
    echo '<tr><td colspan="7" style="text-align:center;">No scheduled classes</td></tr>';
} else {
    foreach ($grid as $time => $cols) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($time) . '</td>';
        foreach ($days as $d) {
            echo '<td>' . ($cols[$d] ?? '') . '</td>';
        }
        echo '</tr>';
    }
}
?>
                    </tbody>
                </table>
            </div>

        </div>

        <?php require_once __DIR__ . '/../inc/announcements.php'; ?>

    </div>

</div> 

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

/* DROPDOWN FUNCTION */
function toggleDropdown(id){
    const el = document.getElementById(id);
    el.classList.toggle('show');
}

/* REPORT CHART */
new Chart(document.getElementById('reportChart'), {
    type: 'line',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May'],
        datasets: [{
            label: 'Reports',
            data: [0, 0,0 , 0, 0],
            borderColor: '#1f8a2b',
            backgroundColor: 'rgba(31,138,43,0.2)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive:true,
        maintainAspectRatio:false
    }
});



// NOTIFICATION POPUP HANDLING
const notifBell = document.getElementById('notifBell');
const notifPopup = document.getElementById('notifPopup');
const notifPopupContent = document.getElementById('notifPopupContent');

// Show notification popup on page load if there are notifications
window.addEventListener('load', () => {
    const hasNotif = <?= count($notifications) > 0 ? 'true' : 'false' ?>;
    if (hasNotif) {
        showNotifPopup();
    }
});

function showNotifPopup() {
    notifPopup.style.display = 'block';
    
    // Generate first notification HTML
    const notifications = <?= json_encode($notifications) ?>;
    
    if (notifications.length > 0) {
        const notif = notifications[0]; // Show first/latest notification
        const date = new Date(notif.created_at).toLocaleDateString('en-US', {month:'short', day:'numeric', year:'numeric', hour:'2-digit', minute:'2-digit'});
        let imgHtml = '';
        if (notif.image) {
            imgHtml = `<img src="../hr/uploads/announcements/${notif.image}" style="width:100%; height:200px; object-fit:cover; border-radius:8px; margin-bottom:15px;">`;
        }
        
        let html = `
            ${imgHtml}
            <h4 style="margin:0 0 10px 0; color:#1f8a2b; font-size:18px;">${notif.title}</h4>
            <p style="margin:0 0 10px 0; color:#555; font-size:15px; line-height:1.6;">${notif.message}</p>
            <small style="color:#999;">${date}</small>
        `;
        
        notifPopupContent.innerHTML = html;
    }
}

function closeNotifPopup() {
    notifPopup.style.display = 'none';
    // Hide only the red count badge, keep the bell visible
    const notifCount = document.getElementById('notifCount');
    if (notifCount) {
        notifCount.style.display = 'none';
    }
}

// Bell click to toggle popup
if (notifBell) {
    notifBell.addEventListener('click', (e) => {
        e.stopPropagation();
        if (notifPopup.style.display === 'block') {
            closeNotifPopup();
        } else {
            showNotifPopup();
        }
    });
}

// Close notification on any click (except on the popup itself)
document.addEventListener('click', (e) => {
    if (notifPopup.style.display === 'block' && !notifPopup.contains(e.target) && !notifBell?.contains(e.target)) {
        closeNotifPopup();
    }
});

// Close on any page navigation
document.querySelectorAll('.sidebar-icon, .sub-buttons a').forEach(el => {
    el.addEventListener('click', () => {
        closeNotifPopup();
    });
});

// Close on escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeNotifPopup();
    }
});
</script>

</body>
</html>


