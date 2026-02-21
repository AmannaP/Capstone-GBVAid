// js/archive_handler.js
function toggleDecoy() {
    // Instantly swap the vault for a fake "System Update" or "Calculator"
    document.querySelector('.vault-container').innerHTML = `
        <div class="text-center py-5">
            <h5 class="text-muted">System Optimization in Progress...</h5>
            <div class="spinner-border text-secondary mt-3"></div>
            <p class="small text-muted mt-4">Please do not close this window.</p>
        </div>
    `;
}