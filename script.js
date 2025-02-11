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
