<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>History — HR Dashboard</title>
<style>
  :root{
    --green:#1f7c28;
    --light:#f6f6f6;
    --muted:#666;
    --border:#dcdcdc;
    --table-head:#f1f1f1;
    --accent:#0a8a29;
  }
  *{box-sizing:border-box}
  body{
    margin:0;
    font-family: "Segoe UI", Roboto, Arial, sans-serif;
    background: var(--light);
    color:#222;
  }

  /* HEADER */
  .header{
    background: var(--green);
    color: #fff;
    display:flex;
    align-items:center;
    gap:14px;
    padding:12px 18px;
    position:relative;
    box-shadow: 0 1px 0 rgba(0,0,0,0.06);
  }
  .logo{
    width:56px;
    height:56px;
    border-radius:50%;
    background: white;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    font-size:14px;
    color:var(--green);
    font-weight:700;
    border:4px solid rgba(255,255,255,0.12);
  }
  .title{
    line-height:1;
  }
  .title h1{
    margin:0;
    font-size:20px;
    letter-spacing:0.2px;
  }
  .title p{
    margin:2px 0 0 0;
    opacity:0.95;
    font-size:12px;
  }

  /* top controls row (below header) */
  .controls {
    display:flex;
    align-items:center;
    gap:14px;
    padding:12px 18px;
    background: #fff;
    border-bottom:1px solid var(--border);
  }

  .back-btn{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:6px 10px;
    border-radius:8px;
    background:transparent;
    border:1px solid transparent;
    cursor:pointer;
    color:var(--accent);
    font-weight:600;
  }
  .back-btn:hover{background:rgba(0,0,0,0.03)}

  .dropdown{
    position:relative;
  }
  .select{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:8px 12px;
    border-radius:8px;
    border:1px solid #d4d4d4;
    background:white;
    cursor:pointer;
    min-width:180px;
  }
  .select .caret{font-size:12px; color:var(--muted)}

  /* table container */
  .table-wrap{
    margin:18px;
    background:white;
    border-radius:8px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.03);
    overflow:hidden;
    border:1px solid var(--border);
  }

  table{
    width:100%;
    border-collapse:collapse;
  }

  thead th{
    background:var(--table-head);
    padding:12px 14px;
    text-align:left;
    font-weight:600;
    font-size:13px;
    border-bottom:1px solid var(--border);
    position:sticky;
    top:0; /* sticky header */
    z-index:5;
  }

  tbody td{
    padding:11px 14px;
    border-bottom:1px solid #f0f0f0;
    font-size:13px;
    vertical-align:middle;
  }

  tbody tr:hover{ background: #fbfbfb; }

  .user-role{
    display:flex;
    flex-direction:column;
  }
  .user-role .email{ font-weight:600; color:#0b4b09; text-decoration:underline; cursor: pointer; }
  .user-role .role{ font-size:12px; color:var(--muted); margin-top:4px; }

  /* small pill for activity */
  .pill{
    display:inline-block;
    padding:6px 10px;
    border-radius:999px;
    font-size:12px;
    font-weight:600;
  }
  .pill.downloaded{ background:#e7f9ee; color:#0b8a35; border:1px solid #d7f1db; }
  .pill.pending{ background:#fff6e7; color:#a46b00; border:1px solid #f0ddbe; }
  .pill.approved{ background:#e8f4ff; color:#0a5bbd; border:1px solid #dfeeff; }

  /* responsive tweaks */
  @media (max-width:900px){
    .select{ min-width:140px }
    .title h1{ font-size:18px }
    .logo{ width:46px; height:46px }
  }

  /* empty state */
  .empty {
    padding:28px;
    text-align:center;
    color:var(--muted);
  }

  /* scrollbar area */
  .table-body{
    max-height:420px; /* scroll area */
    overflow:auto;
  }

  /* small helper for header-right note */
  .right-note{
    margin-left:auto;
    color:#fff;
    opacity:0.95;
    font-size:13px;
    display:flex;
    gap:8px;
    align-items:center;
  }
.logo{
    width:56px;
    height:56px;
    object-fit:contain;   /* FIX: show whole logo properly */
    background:white;
    padding:4px;          /* spacing around logo */
    border-radius:50%;     /* circle effect like your sample */
    border:3px solid rgba(255,255,255,0.4);
}

</style>
</head>
<body>

  <!-- HEADER -->
  <div class="header" role="banner">
  <img src="plsp pic.jpg" class="logo" alt="School Logo">


    <div class="title" role="heading" aria-level="1">
      <h1>Pamantasan ng Lungsod ng San Pablo</h1>
      <p>Prime to Lead and Serve for Progress!</p>
    </div>

    <div class="right-note" aria-hidden="true">
      <!-- small label on the far right of the green bar -->
      <span style="opacity:.95; font-weight:700; font-size:13px; margin-left:16px"> </span>
    </div>
  </div>

  <!-- CONTROLS (back + dropdowns) -->
  <div class="controls" role="region" aria-label="controls">
    <button class="back-btn" id="backBtn">←</button>

    <div class="dropdown">
      <div class="select" id="formSelect" title="Filter by form">
        <span id="formSelectLabel">Type of request Form</span>
        <span class="caret">▾</span>
      </div>
      <!-- hidden custom dropdown menu -->
      <div class="menu" id="formMenu" style="display:none; position:absolute; margin-top:8px; background:#fff; border:1px solid #ddd; border-radius:8px; box-shadow:0 8px 24px rgba(0,0,0,0.08); z-index:30; min-width:220px;">
        <div style="padding:8px 12px; cursor:pointer" data-form="All">All forms</div>
        <div style="padding:8px 12px; cursor:pointer" data-form="Coc">Coc</div>
        <div style="padding:8px 12px; cursor:pointer" data-form="Sick leave">Sick leave</div>
        <div style="padding:8px 12px; cursor:pointer" data-form="Certificate">Certificate</div>
        <div style="padding:8px 12px; cursor:pointer" data-form="Edit Profile">Edit Profile</div>
      </div>
    </div>

    <div class="dropdown">
      <div class="select" id="viewSelect" title="Manage view">
        <span id="viewSelectLabel">Manage View</span>
        <span class="caret">▾</span>
      </div>

      <div class="menu" id="viewMenu" style="display:none; position:absolute; margin-top:8px; background:#fff; border:1px solid #ddd; border-radius:8px; box-shadow:0 8px 24px rgba(0,0,0,0.08); z-index:30; min-width:180px;">
        <div style="padding:8px 12px; cursor:pointer" data-view="All">All</div>
        <div style="padding:8px 12px; cursor:pointer" data-view="Pending">Pending</div>
        <div style="padding:8px 12px; cursor:pointer" data-view="Downloaded">Downloaded</div>
        <div style="padding:8px 12px; cursor:pointer" data-view="Approved">Approved</div>
      </div>
    </div>
  </div>

  <!-- TABLE -->
  <div class="table-wrap" role="table" aria-label="activity table">
    <table>
      <thead>
        <tr>
          <th style="width:190px">Date/Time</th>
          <th style="width:220px">Form</th>
          <th style="width:220px">Activity</th>
          <th>User and Role</th>
        </tr>
      </thead>
    </table>

    <div class="table-body" id="tableBody">
      <table>
        <tbody id="rowsContainer">
          <!-- rows will be injected by JS -->
        </tbody>
      </table>
    </div>
  </div>

<script>
/*
  Functional JS:
  - Creates sample rows (like your image)
  - Filters rows by Form and by Activity (Manage View)
  - Dropdowns implemented with custom menus
  - Back button scrolls to top (sample action)
*/
// render rows
const rowsContainer = document.getElementById('rowsContainer');

function createRow(item){
  const tr = document.createElement('tr');
  tr.dataset.form = item.form;
  tr.dataset.activity = item.activity;
  tr.innerHTML = `
    <td style="width:190px">${item.dt}</td>
    <td style="width:220px">${escapeHtml(item.form)}</td>
    <td style="width:220px"><span class="pill ${pillClass(item.activity)}">${escapeHtml(item.activity)}</span></td>
    <td><div class="user-role"><div class="email">${escapeHtml(item.user)}</div><div class="role">${escapeHtml(item.role)}</div></div></td>
  `;
  return tr;
}

function pillClass(activity){
  const a = activity.toLowerCase();
  if(a.includes('download')) return 'downloaded';
  if(a.includes('pending')) return 'pending';
  if(a.includes('approved')) return 'approved';
  // fallback
  return 'downloaded';
}

function escapeHtml(s){
  return String(s).replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;');
}

function renderAll(){
  rowsContainer.innerHTML = '';
  sampleData.forEach(d=>{
    rowsContainer.appendChild(createRow(d));
  });
}
renderAll();

/* FILTER LOGIC */

let currentForm = 'All';
let currentView = 'All';

function loadData(){
  fetch(`history_api.php?form=${currentForm}&view=${currentView}`)
    .then(r=>r.json())
    .then(data=>{
      rowsContainer.innerHTML='';
      if(!data.length){
        rowsContainer.innerHTML =
          `<tr><td colspan="4" class="empty">No records found</td></tr>`;
        return;
      }

      data.forEach(item=>{
        rowsContainer.appendChild(createRow({
          dt:item.dt,
          form:item.form,
          activity:item.activity,
          user:item.user_email,
          role:item.user_role
        }));
      });
    });
}

loadData();


/* dropdown menu behaviour */
const formSelect = document.getElementById('formSelect');
const formMenu = document.getElementById('formMenu');
const formSelectLabel = document.getElementById('formSelectLabel');

const viewSelect = document.getElementById('viewSelect');
const viewMenu = document.getElementById('viewMenu');
const viewSelectLabel = document.getElementById('viewSelectLabel');

formSelect.addEventListener('click', (e)=>{
  e.stopPropagation();
  formMenu.style.display = formMenu.style.display === 'block' ? 'none' : 'block';
  viewMenu.style.display = 'none';
});

viewSelect.addEventListener('click', (e)=>{
  e.stopPropagation();
  viewMenu.style.display = viewMenu.style.display === 'block' ? 'none' : 'block';
  formMenu.style.display = 'none';
});

// pick form
formMenu.querySelectorAll('[data-form]').forEach(node=>{
  node.addEventListener('click', (e)=>{
    const v = e.currentTarget.dataset.form;
    currentForm = v === 'All' ? 'All' : v;
    formSelectLabel.textContent = v === 'All' ? 'Type of request Form' : v;
    formMenu.style.display = 'none';
    applyFilters();
  });
});

// pick view
viewMenu.querySelectorAll('[data-view]').forEach(node=>{
  node.addEventListener('click', (e)=>{
    const v = e.currentTarget.dataset.view;
    currentView = v === 'All' ? 'All' : v;
    viewSelectLabel.textContent = v === 'All' ? 'Manage View' : v;
    viewMenu.style.display = 'none';
    applyFilters();
  });
});

// click outside closes menus
document.addEventListener('click',(e)=>{
  const isInForm = formMenu.contains(e.target) || formSelect.contains(e.target);
  const isInView = viewMenu.contains(e.target) || viewSelect.contains(e.target);
  if(!isInForm) formMenu.style.display = 'none';
  if(!isInView) viewMenu.style.display = 'none';
});

// back button example (you can change behavior)
document.getElementById('backBtn').addEventListener('click', ()=>{
  // sample action: scroll to top
  window.scrollTo({top:0, behavior:'smooth'});
});

// clickable email in user column (example action)
document.querySelectorAll('.email').forEach(el=>{
  el.addEventListener('click', (e)=>{
    alert('Open profile/email actions for: ' + e.currentTarget.textContent);
  });
});
</script>
</body>
</html>
