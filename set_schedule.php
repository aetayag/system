<?php
session_start();
require_once "db.php";

/* FETCH EXISTING SCHEDULE */
$classes = [];
if (isset($_GET['instructor_id'])) {
    $stmt = $pdo->prepare("
        SELECT c.*, d.name AS instructor_name
        FROM class c
        INNER JOIN file_201 d ON d.id = c.instructor_id
        WHERE c.instructor_id = :id
    ");
    $stmt->execute([':id'=>$_GET['instructor_id']]);
    $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/* AJAX CONFLICT CHECK */
if (
    isset($_POST['instructor_id'], $_POST['day'], $_POST['time'])
    && isset($_SERVER['HTTP_X_REQUESTED_WITH'])
) {
    $id = $_POST['id'] ?? 0;
    $instructor_id = $_POST['instructor_id'];
    $room = $_POST['room'] ?? '';
    $year_section = $_POST['year_section'] ?? '';
    $day = $_POST['day'];
    $time = $_POST['time'];

    if (!str_contains($time,'-')) {
        echo json_encode(['status'=>'invalid_time']); exit;
    }

    list($start,$end)=explode('-',$time);

    $stmt=$pdo->prepare("
        SELECT * FROM class
        WHERE day=:day AND id!=:id
        AND SUBSTRING_INDEX(time,'-',1)<:end
        AND SUBSTRING_INDEX(time,'-',-1)>:start
    ");
    $stmt->execute([
        ':day'=>$day,
        ':start'=>$start,
        ':end'=>$end,
        ':id'=>$id
    ]);

    $types=[];
    foreach($stmt->fetchAll() as $c){
        if($c['room']===$room) $types[]='room';
        if($c['year_section']===$year_section) $types[]='year_section';
        if($c['instructor_id']===$instructor_id) $types[]='instructor';
    }

    echo json_encode($types
        ? ['status'=>'conflict','types'=>array_unique($types)]
        : ['status'=>'ok']
    );
    exit;
}

/* AJAX SAVE ROW */
if (isset($_POST['save_row']) && isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    $id = $_POST['id'] ?? 0;
    $fields = [
        'instructor_id','coursecode_title','time','day','room',
        'modality_type','year_section','semester','sy','unit'
    ];
    $data = [];
    foreach($fields as $f) $data[$f] = $_POST[$f] ?? '';

    if($id){ // update
        $set = implode(',', array_map(fn($k)=>"$k=:".$k, $fields));
        $stmt = $pdo->prepare("UPDATE class SET $set WHERE id=:id");
        $data['id'] = $id;
        $stmt->execute($data);
    } else { // insert
        $cols = implode(',', $fields);
        $placeholders = implode(',', array_map(fn($f)=>":$f", $fields));
        $stmt = $pdo->prepare("INSERT INTO class ($cols) VALUES ($placeholders)");
        $stmt->execute($data);
        $id = $pdo->lastInsertId();
    }

    echo json_encode(['status'=>'ok','id'=>$id]);
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Set Schedule</title>
<style>
body { font-family: Arial; background: #f4f4f4; padding: 20px; }
.schedule-title { text-align: center; font-weight: bold; margin-top: 100px; font-size: 24px; color: #000; }
table { border-collapse: collapse; width: 100%; min-width: 1000px; }
th, td { border:1px solid #444; padding: 8px; text-align: left; white-space: nowrap; }
thead th { background: #f2f2f2; position: sticky; top: 0; z-index: 10; cursor: pointer; }
tbody tr:nth-child(even) { background: #e6e6e6; }
.top-header { position: fixed; top: 0; left: 0; width: 100%; background: #1f8f2d; color: white; padding: 10px 20px; display: flex; align-items: center; gap: 15px; z-index: 100; }
.header-logo { width: 50px; height: auto; }
.table-container { margin-top: 100px; border:1px solid #e6e6e6; border-radius:6px; overflow:hidden; }
.table-scroll { max-height: 500px; overflow-y:auto; }
.add-btn { color: black; background: #f4f4f4; padding: 2px 6px; border-radius:4px; cursor:pointer; }
.add-btn:hover { background: #6c6b6b; color:white; }
.bottom-right-btn { position: fixed; bottom: 20px; right: 20px; background-color: #1f8f2d; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.2); transition: background-color 0.3s ease; }
.bottom-right-btn:hover { background-color: #166f1f; }
.conflict-cell { background:#ffcccc; } /* red for conflicts */
.conflict-duplicate-cell { background:#c080ff; } /* purple for both */
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

<h2 class="schedule-title">Schedule</h2>
<a href="javascript:history.back()" class="back-link">&larr;</a>

<div class="table-container">
    <div class="table-scroll">
        <table id="classTable">
            <thead>
                <tr>
                    <th id="addRowBtn" class="add-btn">+</th>
                    <th>Instructor ID</th>
                    <th>Instructor Name</th>
                    <th>CourseCode & Title</th>
                    <th>Time</th>
                    <th>Day</th>
                    <th>Room</th>
                    <th>Type of Modality</th>
                    <th>Year & Section</th>
                    <th>Semester</th>
                    <th>SY</th>
                    <th>Unit</th>
                </tr>
            </thead>
            <tbody>
                <?php if($classes): $rowNum=1; ?>
                    <?php foreach($classes as $cls): ?>
                        <tr data-id="<?= $cls['id'] ?>" data-name="<?= htmlspecialchars($cls['instructor_name']) ?>">
                            <td><?= $rowNum++; ?></td>
                            <td contenteditable><?= htmlspecialchars($cls['instructor_id'] ?? '') ?></td>
                            <td><?= htmlspecialchars($cls['instructor_name'] ?? '') ?></td>
                            <td contenteditable><?= htmlspecialchars($cls['coursecode_title'] ?? '') ?></td>
                            <td contenteditable><?= htmlspecialchars($cls['time'] ?? '') ?></td>
                            <td>
                                <select>
                                    <?php
                                    $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
                                    foreach($days as $day){
                                        $sel = ($day==$cls['day'])?'selected':''; 
                                        echo "<option value='$day' $sel>$day</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td contenteditable><?= htmlspecialchars($cls['room'] ?? '') ?></td>
                            <td>
                                <select>
                                    <?php
                                    $types = ['F2F','Offline'];
                                    foreach($types as $type){
                                        $sel = ($type==$cls['modality_type'])?'selected':''; 
                                        echo "<option value='$type' $sel>$type</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td contenteditable><?= htmlspecialchars($cls['year_section'] ?? '') ?></td>
                            <td>
                                <select>
                                    <?php
                                    $sems = ['1st','2nd'];
                                    foreach($sems as $sem){
                                        $sel = ($sem==$cls['semester'])?'selected':''; 
                                        echo "<option value='$sem' $sel>$sem</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td contenteditable><?= htmlspecialchars($cls['sy'] ?? '') ?></td>
                            <td contenteditable><?= htmlspecialchars($cls['unit'] ?? '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="12" style="text-align:center;">No schedule selected</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<a href="#" id="setScheduleBtn" class="bottom-right-btn">Set Schedule</a>

<!-- Modal -->
<div id="confirmModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
    background: rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:200;">
    <div style="background:white; padding:20px; border-radius:8px; max-width:400px; width:90%; text-align:center;">
        <p id="modalText" style="margin-bottom:20px; font-size:16px;">
            Please confirm sending the schedule for <strong id="modalInstructorName"><?= htmlspecialchars($_GET['name'] ?? 'Instructor') ?></strong>
        </p>
        <button id="cancelModal" style="margin-right:10px; padding:8px 16px; border:none; border-radius:4px; background:#ccc; cursor:pointer;">Cancel</button>
        <button id="sendModal" style="padding:8px 16px; border:none; border-radius:4px; background:#1f8f2d; color:white; cursor:pointer;">Send</button>
    </div>
</div>

<script>
const table = document.getElementById('classTable');

/* ADD ROW */
document.getElementById('addRowBtn').onclick = ()=>{
    const r = table.insertRow(-1);
    r.dataset.id = '';
    const instructorId = "<?= htmlspecialchars($_GET['instructor_id'] ?? '') ?>";
    const instructorName = "<?= htmlspecialchars($_GET['name'] ?? '') ?>";
    
    for(let i=0;i<12;i++){
        const c = r.insertCell(i);
        if(i===1) c.textContent = instructorId;
        else if(i===2) c.textContent = instructorName;
        else if([5,7,9].includes(i)){
            c.innerHTML = i==5
                ? `<select><option>Monday</option><option>Tuesday</option><option>Wednesday</option><option>Thursday</option><option>Friday</option><option>Saturday</option></select>`
                : i==7
                    ? `<select><option>F2F</option><option>Offline</option></select>`
                    : `<select><option>1st</option><option>2nd</option></select>`;
        } else if(i!==0) {
            c.contentEditable = true;
        }
    }
    bindRow(r);
};

/* BIND ROW EVENTS */
function bindRow(row){
    row.querySelectorAll('select,[contenteditable]').forEach(el=>{
        el.addEventListener('input',()=>{ checkAllRows(); saveRow(row); });
        el.addEventListener('change',()=>{ checkAllRows(); saveRow(row); });
    });
}
document.querySelectorAll('#classTable tbody tr').forEach(bindRow);

/* SAVE ROW */
function saveRow(row){
    const c = row.cells;
    const data = {
        save_row:1,
        id: row.dataset.id,
        instructor_id: c[1].textContent.trim(),
        coursecode_title: c[3].textContent.trim(),
        time: c[4].textContent.trim(),
        day: c[5].querySelector('select').value,
        room: c[6].textContent.trim(),
        modality_type: c[7].querySelector('select').value,
        year_section: c[8].textContent.trim(),
        semester: c[9].querySelector('select').value,
        sy: c[10].textContent.trim(),
        unit: c[11].textContent.trim()
    };
    fetch('set_schedule.php',{
        method:'POST',
        headers:{'X-Requested-With':'XMLHttpRequest'},
        body:new URLSearchParams(data)
    }).then(r=>r.json())
      .then(res=>{ if(res.id) row.dataset.id = res.id; });
}

/* CHECK ALL ROWS FOR DUPLICATES AND CONFLICTS */
function checkAllRows(){
    const rows = [...document.querySelectorAll('#classTable tbody tr')];

    // Clear previous highlights
    rows.forEach(r=>{
        r.classList.remove('conflict-cell','duplicate-cell','conflict-duplicate-cell');
        [...r.cells].forEach(c=>c.classList.remove('conflict-cell','duplicate-cell','conflict-duplicate-cell'));
    });

    // Track duplicates
    const scheduleMap = {};

    rows.forEach((row, i)=>{
        const c = row.cells;
        const key = [
            c[3].textContent.trim(),
            c[4].textContent.trim(),
            c[5].querySelector('select').value,
            c[6].textContent.trim(),
            c[8].textContent.trim(),
            c[9].querySelector('select').value
        ].join('|');

        if(!scheduleMap[key]) scheduleMap[key] = [];
        scheduleMap[key].push(row);
    });

    // Highlight duplicates
    Object.values(scheduleMap).forEach(rowsWithSameKey=>{
        if(rowsWithSameKey.length > 1){
            rowsWithSameKey.forEach(r=>{
                if(!r.classList.contains('conflict-cell')) r.classList.add('duplicate-cell');
            });
        }
    });

    // Check conflicts via AJAX
    rows.forEach(row=>{
        const c = row.cells;
        const data = {
            id: row.dataset.id,
            instructor_id: c[1].textContent.trim(),
            day: c[5].querySelector('select').value,
            time: c[4].textContent.trim(),
            room: c[6].textContent.trim(),
            year_section: c[8].textContent.trim()
        };
        fetch('set_schedule.php',{
            method:'POST',
            headers:{'X-Requested-With':'XMLHttpRequest'},
            body:new URLSearchParams(data)
        })
        .then(r=>r.json())
        .then(res=>{
            if(res.status==='conflict'){
                res.types.forEach(t=>{
                    if(t==='room') c[6].classList.add('conflict-cell');
                    if(t==='year_section') c[8].classList.add('conflict-cell');
                    if(t==='instructor') c[1].classList.add('conflict-cell');
                });

                // Purple if both conflict + duplicate
                if(row.classList.contains('duplicate-cell')){
                    row.classList.add('conflict-duplicate-cell');
                    row.classList.remove('conflict-cell');
                    row.classList.remove('duplicate-cell');
                }
            }
        });
    });
}

/* MODAL LOGIC */
const setBtn = document.getElementById('setScheduleBtn');
const modal = document.getElementById('confirmModal');
const cancelBtn = document.getElementById('cancelModal');
const sendBtn = document.getElementById('sendModal');

setBtn.addEventListener('click', (e)=>{
    e.preventDefault();
    const rows = [...document.querySelectorAll('#classTable tbody tr')];
    let issuesExist = false;
    rows.forEach(row=>{
        if(row.classList.contains('conflict-cell') || row.classList.contains('duplicate-cell') || row.classList.contains('conflict-duplicate-cell')) issuesExist = true;
    });
    if(issuesExist){
        alert('Please fix duplicate or conflicting rows before sending.');
        return;
    }
    modal.style.display = 'flex';
});

cancelBtn.addEventListener('click', ()=> modal.style.display = 'none');

sendBtn.addEventListener('click', ()=> {
    modal.style.display = 'none';
    const instructorId = "<?= htmlspecialchars($_GET['instructor_id'] ?? '') ?>";
    window.location.href = `set_schedule_submit.php?instructor_id=${instructorId}`;
});
</script>

</body>
</html>
