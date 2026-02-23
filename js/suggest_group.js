// js/suggest_group.js
document.getElementById('suggestGroupForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const modalElement = document.getElementById('suggestGroupModal');
    const modal = bootstrap.Modal.getInstance(modalElement);

    fetch('../actions/suggest_group_action.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (modal) modal.hide();

        if (data.status === 'success') {
            Swal.fire({
                title: 'Suggestion Sent!',
                text: data.message,
                icon: 'success',
                background: '#1a1033',
                color: '#ffffff',
                backdrop: 'rgba(15, 10, 30, 0.8)',
                confirmButtonColor: '#9d4edd',
                customClass: {
                    popup: 'neon-border'
                }
            });
            this.reset();
        } else {
            Swal.fire({
                title: 'Error',
                text: data.message,
                icon: 'error',
                background: '#1a1033',
                color: '#ffffff',
                confirmButtonColor: '#d33',
                customClass: {
                    popup: 'neon-border'
                }
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'System Error',
            text: 'Could not connect to the server.',
            icon: 'error',
            background: '#1a1033',
            color: '#ffffff',
            confirmButtonColor: '#d33'
        });
    });
});

// Injecting CSS to ensure the neon border matches across all scripts
if (!document.getElementById('neon-border-style')) {
    const style = document.createElement('style');
    style.id = 'neon-border-style';
    style.innerHTML = `
        .neon-border {
            border: 1px solid #bf40ff !important;
            box-shadow: 0 0 20px rgba(191, 64, 255, 0.4) !important;
            border-radius: 20px !important;
        }
    `;
    document.head.appendChild(style);
}