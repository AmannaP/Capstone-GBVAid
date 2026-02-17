<style>
    .vault-container {
        background: rgba(26, 16, 51, 0.95);
        border: 1px solid #3c2a61;
        border-radius: 20px;
        padding: 30px;
        backdrop-filter: blur(10px);
    }
    
    .file-row {
        background: rgba(15, 10, 30, 0.6);
        border: 1px solid #3c2a61;
        border-radius: 12px;
        margin-bottom: 10px;
        padding: 15px;
        transition: 0.3s;
    }
    
    .file-row:hover {
        border-color: #bf40ff;
    }

    .decoy-mode {
        display: none; /* Triggered by JS to show a "Weather" or "Calculator" screen */
    }
</style>

<div class="container mt-5">
    <div class="vault-container animate__animated animate__fadeIn">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="feature-title mb-0"><i class="bi bi-lock-fill"></i> Secure Evidence Vault</h4>
            <button class="btn btn-sm btn-outline-secondary" onclick="toggleDecoy()">
                <i class="bi bi-eye-slash"></i> Activate Mask
            </button>
        </div>

        <form id="evidence-upload-form" enctype="multipart/form-data" class="mb-5">
            <div class="input-group">
                <input type="file" class="form-control" name="evidence_file" id="evidence_file">
                <button class="btn btn-custom" type="submit">Upload to Cloud</button>
            </div>
            <small class="text-muted mt-2 d-block">Files are encrypted before upload. Access is only possible via your secure login.</small>
        </form>

        <div id="archive-list">
            <div class="file-row d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-file-earmark-music-fill text-info me-2"></i>
                    <span>audio_recording_2024_01_10.mp3</span>
                </div>
                <button class="btn btn-sm btn-outline-light"><i class="bi bi-download"></i></button>
            </div>
            </div>
    </div>
</div>