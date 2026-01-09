<?php
session_start();
require_once "db.php"; // Make sure $pdo is a PDO instance

// Fetch logged-in user profile picture from register table
$profile_img = 'default.png'; // fallback
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT profile_photo FROM file_201 WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row && !empty($row['profile_photo'])) {
        $profile_img = $row['profile_photo'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>PLSP Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- CHART JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
/* GENERAL RESET */
html, body {
    height: 100%;
    margin: 0;
    font-family: Arial, sans-serif;
}


/* HEADER */
.top-header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background: #1f8f2d;
    color: white;
    display: flex;
    align-items: center;
    padding: 50px 20px;
    height: 70px;      /* FIXED height */
    box-sizing: border-box;
    z-index: 100;
}



.header-logo { width: 50px; margin-right: 15px; }

/* LAYOUT */
.layout {
    display: flex;
    height: calc(100vh - 70px); /* 70px = header height */
    margin-top: 70px;           /* push layout below header */
    overflow: hidden;           /* only main content scrolls */
}

/* SIDEBAR */
.side-panel {
    width: 220px;
    background: #eaeaea;
    padding: 20px;
    overflow-y: auto;
}
.profile-btn {
    background: none;
    border: none;
    margin-bottom: 20px;
    margin-top: 30px;
    align-items: center;
    cursor: pointer;

}
.profile-img { width: 60px; border-radius: 50%; }
.side-panel ul { 
    list-style: none; 
    padding: 0; }
.side-panel li {
     margin: 10px 0;
     }
.side-panel a {
    text-decoration: none;
    background: #dcdcdc;
    padding: 10px;
    display: block;
    border-radius: 20px;
    color: black;
}

/* 201 FILE DROPDOWN */
.dropdown { position: relative; }
.dropdown-toggle { cursor: pointer; }
.dropdown-menu {
    display: none;
    list-style: none;
    padding: 0;
    margin: 5px 0 0 0;
    background: #eaeaea;
    border-radius: 10px;
}
.dropdown-menu li a {
    padding: 8px 12px;
    display: block;
    border-radius: 15px;
    margin: 5px;
    background: #dcdcdc;
    color: black;
    text-decoration: none;
}
.dropdown-menu li a:hover { background: #c0c0c0; }

/* MAIN CONTENT */
.content {
    flex: 1;
    padding: 25px;
    overflow-y: auto;
}

/* GREETING + NOTIF */
.top-content { display: flex; justify-content: space-between; align-items: center; }
#greetingText { color: green; }
.notification-container { position: relative; }
.notification-btn { background: none; border: none; font-size: 22px; cursor: pointer; }
.notif-badge {
    background: red;
    color: white;
    border-radius: 50%;
    padding: 2px 6px;
    font-size: 12px;
    position: absolute;
    top: -5px;
    right: -10px;
}
.notification-dropdown {
    display: none;
    position: absolute;
    right: 0;
    top: 30px;
    background: white;
    width: 220px;
    border: 1px solid #ccc;
    border-radius: 6px;
    padding: 10px;
}

/* STATS */
.stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    margin: 20px 0;
}
.card {
    background: #dedede;
    padding: 15px;
    text-align: center;
    border-radius: 6px;
}

/* GRAPH */
.graph-box {
    background: #dedede;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    text-align: center; /* <-- center everything inside */
}



/* ANNOUNCEMENTS */
.announcements { display: flex; gap: 15px; }
.announce-card { width: 200px; height: 130px; background: #dedede; border-radius: 8px; }

</style>
</head>
<body>

<!-- HEADER -->
<header class="top-header">
    <img src="../hr/plsp pic.jpg" class="header-logo" alt="PLSP Logo">
    <div>
        <h1>Pamantasan ng Lungsod ng San Pablo</h1>
        <p>Prime to Lead and Serve for Progress!</p>
    </div>
</header>

<div class="layout">
    <!-- SIDEBAR -->
    <aside class="side-panel">
<ul>
    <li class="dropdown">
        <a href="javascript:void(0)" onclick="toggleDropdown('personnelDropdown')" class="dropdown-toggle">
            Personnel Management ▾
        </a>
        <ul class="dropdown-menu" id="personnelDropdown">
            <li><a href="teaching_fulltime.php?type=fulltime">Full Time</a></li>
            <li><a href="teaching_parttime.php?type=parttime">Part Time</a></li>
            <li><a href="teaching_non.php?type=nonteaching">Non-Teaching</a></li>
        </ul>
    </li>
    <li class="dropdown">
    <a href="javascript:void(0)" onclick="toggleDropdown('fileDropdown')" class="dropdown-toggle">
        201 File ▾
    </a>
    <ul class="dropdown-menu" id="fileDropdown">
<li><a href="../201Files/201file.php?type=fulltime">Full Time</a></li>
<li><a href="../201Files/201file.php?type=parttime">Part Time</a></li>
<li><a href="../201Files/201file.php?type=nonteaching">Non-Teaching</a></li>

    </ul>
</li>

</ul>

 
            <li><a href="set_schedule.php">Request Schedule</a></li>
            <li><a href="logout.php">Logout</a></li>
        
    </aside>

    <!-- MAIN CONTENT -->
    <main class="content">
        <div class="top-content">
            <h2 id="greetingText"></h2>
            <div class="notification-container">
                <button class="notification-btn" id="notifBtn">
                    🔔 <span class="notif-badge" id="notifCount">3</span>
                </button>
                <div class="notification-dropdown" id="notifDropdown">
                    <strong>Notifications</strong>
                </div>
            </div>
        </div>

        <!-- STATS -->
        <div class="stats">
            <div class="card">
                <p>Total Teaching</p>
                <h3>
                <?php
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM `file_201` WHERE employee_type = ?");
                    $stmt->execute(['teaching']);
                    echo $stmt->fetchColumn();
                ?>
                </h3>
            </div>
            <div class="card">
                <p>Total Non-Teaching</p>
                <h3>
                <?php
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM `file_201` WHERE employee_type = ?");
                    $stmt->execute(['non-teaching']);
                    echo $stmt->fetchColumn();
                ?>
                </h3>
            </div>
            <div class="card">
                <p>Total Active</p>
 
            </div>
            <div class="card">
                <p>Total Inactive</p>

            </div>
        </div>

        <!-- GRAPH -->
        <div class="graph-box">
            <h4>Teaching Evaluation</h4>
            <p>1st Semester 2023-2024</p>
            <canvas id="barChart"></canvas>
        </div>

        <!-- ANNOUNCEMENTS -->
        <?php require_once __DIR__ . '/../inc/announcements.php'; ?>
    </main>
</div>

<script>
/* GREETING */
const greetingText = document.getElementById("greetingText");
const hour = new Date().getHours();
if(hour < 12) greetingText.textContent = "Good Morning !!";
else if(hour < 18) greetingText.textContent = "Good Afternoon !!";
else greetingText.textContent = "Good Evening !!";

/* NOTIFICATION */
const notifBtn = document.getElementById("notifBtn");
const notifDropdown = document.getElementById("notifDropdown");
const notifCount = document.getElementById("notifCount");
notifBtn.addEventListener("click", ()=>{
    notifDropdown.style.display = notifDropdown.style.display === "block" ? "none" : "block";
    notifCount.style.display = "none";
});

/* BAR CHART */
new Chart(document.getElementById("barChart"), {
    type:'bar',
    data:{
        labels:['A','B','C','D','E'],
        datasets:[{
            label:'Evaluation Score',
            data:[0,0,0,0,0],
            backgroundColor:'#ffffff'
        }]
    },
    options:{ scales:{ y:{ beginAtZero:true } } }
});

/* 201 FILE DROPDOWN */
function toggleDropdown(id) {
    const dropdown = document.getElementById(id);
    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
}

</script>

</body>
</html>
