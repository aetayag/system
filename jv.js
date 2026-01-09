const tableBody = document.querySelector("#employeeTable tbody");
const modal = document.getElementById("modalForm");
const addBtn = document.getElementById("addRowBtn");
const closeBtn = document.getElementById("closeBtn");
const form = document.getElementById("dataForm");

// Load employees from database
async function loadEmployees(){
    const res = await fetch("b201.php?action=read");
    const data = await res.json();
    tableBody.innerHTML = "";
    data.forEach((emp, index)=>{
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${index+1}</td>
            <td>${emp.name}</td>
            <td>${emp.dob}</td>
            <td>${emp.pob}</td>
            <td>${emp.sex}</td>
            <td>${emp.age}</td>
            <td>${emp.email}</td>
            <td>${emp.civil_status}</td>
            <td>${emp.address}</td>
            <td>${emp.date_hired}</td>
            <td>${emp.emergency_contact}</td>
            <td>${emp.emergency_contact_no}</td>
            <td>${emp.id_number}</td>
            <td>${emp.employee_selects}</td>
            <td>${emp.position}</td>
            <td>${emp.department}</td>
            <td>${emp.employee_type}</td>
            <td><button class="deleteBtn" data-id="${emp.id}">Delete</button></td>
        `;
        tableBody.appendChild(row);
    });

    // Delete functionality
    document.querySelectorAll(".deleteBtn").forEach(btn=>{
        btn.addEventListener("click", async ()=>{
            const id = btn.dataset.id;
            if(confirm("Are you sure you want to delete this employee?")){
                await fetch("b201.php?action=delete",{
                    method:"POST",
                    body: new URLSearchParams({id})
                });
                loadEmployees();
            }
        });
    });
}

// Show modal
addBtn.addEventListener("click", ()=> modal.style.display="block");
closeBtn.addEventListener("click", ()=> modal.style.display="none");

// Submit form
form.addEventListener("submit", async e=>{
    e.preventDefault();
    const formData = new FormData(form);
    await fetch("b201.php?action=create",{
        method:"POST",
        body: formData
    });
    modal.style.display="none";
    form.reset();
    loadEmployees();
});

// Initial load
loadEmployees();



document.getElementById("postBtn").onclick = function() {
  let text = document.getElementById("announceInput").value;

  if (text.trim() === "") return alert("Please enter an announcement.");

  fetch("post_announcement.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "announcement=" + encodeURIComponent(text)
  })
  .then(res => res.text())
  .then(data => {
      if (data === "success") {
          alert("Announcement posted!");
          location.reload();
      } else {
          alert("Failed to post announcement.");
      }
  });
};


