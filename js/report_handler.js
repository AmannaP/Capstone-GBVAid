// js/report_handler.js
document.addEventListener('DOMContentLoaded', function () {
    const reportForm = document.getElementById('reportForm');

    if (reportForm) {
        reportForm.addEventListener('submit', function (e) {
            e.preventDefault(); // This stops the browser from leaving the page

            const formData = new FormData(this);

            fetch('../actions/submit_report_action.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json()) // We expect JSON back from PHP
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        title: 'Report Received',
                        text: data.message,
                        icon: 'success',
                        background: '#1a1033', 
                        color: '#ffffff',
                        backdrop: 'rgba(15, 10, 30, 0.8)', 
                        confirmButtonColor: '#9d4edd', 
                        customClass: {
                            popup: 'neon-border'
                        }
                    }).then(() => {
                        window.location.href = '../user/dashboard.php';
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message,
                        icon: 'error',
                        background: '#1a1033',
                        color: '#ffffff',
                        confirmButtonColor: '#d33'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Could not connect to the server.', 'error');
            });
        });
    }
});

// Injecting CSS to blend the popup border with your neon theme
const style = document.createElement('style');
style.innerHTML = `
    .neon-border {
        border: 1px solid #bf40ff !important;
        box-shadow: 0 0 20px rgba(191, 64, 255, 0.4) !important;
        border-radius: 20px !important;
    }
`;
document.head.appendChild(style);