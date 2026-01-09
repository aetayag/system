// Load account data
fetch('fetch_user.php')
.then(res => res.json())
.then(user => {
    document.querySelector('input[name="full_name"]').value = user.full_name;
    document.querySelector('input[name="birthday"]').value = user.birthday;
    document.querySelector('input[name="age"]').value = user.age;
    document.querySelector('input[name="contact_number"]').value = user.contact_number;
    document.querySelector('select[name="sex"]').value = user.sex;
    document.querySelector('input[name="address"]').value = user.address;
    document.querySelector('select[name="civil_status"]').value = user.civil_status;
    document.querySelector('input[name="date_hired"]').value = user.date_hired;
    document.querySelector('input[name="identification_id"]').value = user.identification_id;
    document.querySelector('input[name="email"]').value = user.email;
    document.querySelector('input[name="emergency_name"]').value = user.emergency_name;
    document.querySelector('input[name="emergency_contact"]').value = user.emergency_contact;
});

// Update Account
document.querySelector('#account .update-btn').addEventListener('click', () => {
    const formData = new FormData(document.querySelector('#account form'));

    fetch('update_account.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        alert(data.status === 'success' ? 'Account updated!' : 'Failed to update.');
    });
});

// Change password
document.querySelector('#security .update-btn').addEventListener('click', () => {
    const formData = new FormData(document.querySelector('#security form'));
    fetch('update_password.php', { method: 'POST', body: formData })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') alert('Password updated!');
        else if(data.status === 'wrong') alert('Current password is incorrect');
        else alert('Failed to update password');
    });
});

// Load activity log
fetch('fetch_activity.php')
.then(res => res.json())
.then(activities => {
    const table = document.querySelector('#activity table');
    activities.forEach(a => {
        const row = table.insertRow();
        row.innerHTML = `<td>${new Date(a.created_at).toLocaleTimeString()}</td>
                         <td>${new Date(a.created_at).toLocaleDateString()}</td>
                         <td>${a.activity}</td>`;
    });
});
