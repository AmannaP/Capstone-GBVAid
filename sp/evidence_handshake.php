<?php
require_once '../settings/core.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 3 || $_SESSION['sp_approved'] == 0) {
    header("Location: ../login/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Evidence Vault Handshake | SP Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #0f0a1e;
            color: #ffffff;
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            background-image: radial-gradient(#3c2a61 1px, transparent 1px);
            background-size: 30px 30px;
            background-attachment: fixed;
        }
        .handshake-card {
            background: #1a1033;
            border: 1px solid #bf40ff;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            max-width: 500px;
            margin: 80px auto;
            box-shadow: 0 10px 30px rgba(191, 64, 255, 0.2);
            backdrop-filter: blur(10px);
        }
        .shield-icon {
            font-size: 4rem;
            color: #bf40ff;
            margin-bottom: 20px;
            animation: pulse-glow 2s infinite;
        }
        .pin-input {
            background: #150d2b;
            border: 1px solid #3c2a61;
            color: #e0aaff;
            text-align: center;
            font-size: 2rem;
            letter-spacing: 15px;
            padding: 15px;
            border-radius: 10px;
            text-transform: uppercase;
        }
        .pin-input:focus {
            background: #1a1033;
            border-color: #bf40ff;
            color: #e0aaff;
            box-shadow: 0 0 15px rgba(191, 64, 255, 0.4);
            outline: none;
        }
        .btn-unlock {
            background: linear-gradient(135deg, #9d4edd, #bf40ff);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 1.1rem;
            width: 100%;
            transition: 0.3s;
            margin-top: 20px;
        }
        .btn-unlock:hover {
            box-shadow: 0 5px 20px rgba(191, 64, 255, 0.5);
            transform: translateY(-2px);
            color: white;
        }
        .text-muted { color: #b89fd4 !important; }
        @keyframes pulse-glow {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); text-shadow: 0 0 20px rgba(191, 64, 255, 0.5); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>

<?php include '../views/sp_navbar.php'; ?>

<div class="container">
    <div class="handshake-card">
        <i class="bi bi-shield-lock-fill shield-icon"></i>
        <h3 class="fw-bold mb-3" style="color:#e0aaff;">Safe Space Handshake</h3>
        <p class="text-muted small mb-4">
            You are attempting to access highly sensitive trauma materials. 
            Enter the Temporary Access PIN provided by the survivor to securely decrypt and unlock their Evidence Vault.
        </p>

        <form id="handshakeForm">
            <div class="mb-3">
                <input type="text" id="vaultPin" class="form-control pin-input" maxlength="6" placeholder="******" required autocomplete="off">
            </div>
            <button type="submit" class="btn btn-unlock" id="unlockBtn">
                <i class="bi bi-unlock-fill me-2"></i> Verify & Unlock
            </button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('#handshakeForm').submit(function(e) {
        e.preventDefault();
        
        const pin = $('#vaultPin').val().toUpperCase().trim();
        if (pin.length !== 6) {
            Swal.fire('Invalid FORMAT', 'PIN must be exactly 6 characters.', 'warning');
            return;
        }

        const btn = $('#unlockBtn');
        const oText = btn.html();
        btn.html('<i class="fa fa-spinner fa-spin"></i> Verifying...').prop('disabled', true);

        $.post('../actions/verify_pin_action.php', { pin: pin }, function(response) {
            if (response.success) {
                // Play a success sound? Or just redirect
                Swal.fire({
                    title: 'Access Granted',
                    text: 'The handshake was successful. Opening Evidence Vault...',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false,
                    background: '#1a1033',
                    color: '#fff'
                }).then(() => {
                    window.location.href = 'vault_viewer.php';
                });
            } else {
                Swal.fire({
                    title: 'Access Denied',
                    text: response.message,
                    icon: 'error',
                    background: '#1a1033',
                    color: '#fff'
                });
            }
        }, 'json').fail(function() {
            Swal.fire('Error', 'Connection failed.', 'error');
        }).always(function() {
            btn.html(oText).prop('disabled', false);
        });
    });
});
</script>
</body>
</html>
