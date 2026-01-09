function checkConflict(row){
    const cells = row.cells;

    // clear old highlights
    [...cells].forEach(c=>c.classList.remove('conflict-cell'));

    const data = {
        id: row.dataset.id || '',
        instructor_id: cells[1].textContent.trim(),
        coursecode_title: cells[3].textContent.trim(),
        time: cells[4].textContent.trim().replace(/\s+/g,''),
time: cells[4].textContent.trim().replace(/\s+/g,''),

        day: cells[5].querySelector('select').value,
        room: cells[6].textContent.trim(),
        modality_type: cells[7].querySelector('select').value,
        year_section: cells[8].textContent.trim(),
        semester: cells[9].querySelector('select').value,
        sy: cells[10].textContent.trim(),
        unit: cells[11].textContent.trim()
    };

    fetch('set_schedule.php', {
        method: 'POST',
        headers: {'X-Requested-With':'XMLHttpRequest'},
        body: new URLSearchParams(data)
    })
    .then(res=>res.json())
    .then(resp=>{
        if(resp.status === 'conflict') {

            row.classList.add('conflict');

            // 🔴 highlight specific columns
            resp.types.forEach(type=>{
                if(type === 'room') cells[6].classList.add('conflict-cell');
                if(type === 'year_section') cells[8].classList.add('conflict-cell');
                if(type === 'instructor') cells[1].classList.add('conflict-cell');
            });

        } else {

            row.classList.remove('conflict');

            // ✅ SAVE IF NO CONFLICT
            fetch('save_class.php',{
                method:'POST',
                body:new URLSearchParams(data)
            })
            .then(res=>res.json())
            .then(r=>{
                if(r.id) row.dataset.id = r.id;
            });
        }
    });
}
