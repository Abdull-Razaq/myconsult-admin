document.querySelectorAll('.action-btn').forEach(button => {
    button.addEventListener('click', function() {
        const logId = this.getAttribute('data-id');

        // Send AJAX request to approve the hours
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'approve_hours.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status == 200 && xhr.responseText == 'success') {
                // Update the status in the table and disable the button
                const row = button.closest('tr');
                row.querySelector('.pending').innerText = 'Approved';
                button.disabled = true;
            }
        };
        xhr.send('id=' + logId);
    });
});

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".action-btn").forEach(button => {
        button.addEventListener("click", function () {
            let logId = this.getAttribute("data-id");

            fetch("approve_hours.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ log_id: logId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Hours Approved!");
                    location.reload(); // Refresh page to update status
                } else {
                    alert("Error approving hours.");
                }
            });
        });
    });
});

function closeForm() {
    document.getElementById('logHoursForm').style.display = 'none';
}
    document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('logHoursBtn')?.addEventListener('click', function () {
        document.getElementById('logHoursForm').style.display = 'block';
    });

    document.querySelectorAll('.close, .close-btn').forEach(el => {
        el.addEventListener('click', closeForm);
    });
    
    // Fetch user name greeting
     fetch('get_user.php')
     .then(res => res.json())
     .then(data => {
         if (data.username) {
             document.getElementById('greeting').textContent = `Hi, ${data.username}`;
         }
     })
     .catch(err => console.error('Failed to fetch user:', err));

    // Fetch logged hours table
    fetch('fetch_hours.php')
     .then(response => response.json())
     .then(data => {
         const tableBody = document.querySelector('#hoursTable tbody');
         tableBody.innerHTML = ''; // clear

         data.forEach(hour => {
             const row = document.createElement('tr');
             const formattedDate = new Date(hour.date).toLocaleDateString();
             row.innerHTML = `
                 <td>${formattedDate}</td>
                 <td>${hour.task_name}</td>
                 <td>${hour.hours}</td>
                 <td>${hour.task_description}</td>
                 <td class="status ${hour.status}">${hour.status}</td>
             `;
             tableBody.appendChild(row);
         });
     })
     .catch(err => console.error('Error loading hours:', err));

    // Fetch analytics
    fetch('get_analytics.php')
     .then(res => res.json())
     .then(data => {
         document.getElementById('hoursToday').textContent = data.hours_today || 0;
         document.getElementById('hoursThisWeek').textContent = data.hours_week || 0;
         document.getElementById('hoursThisMonth').textContent = data.hours_month || 0;
     })
     .catch(err => console.error('Analytics error:', err));
    
});



