<?php
require_once '../settings/core.php';
require_once '../settings/db_class.php';

$valid_token = false;
$token = $_GET['token'] ?? '';
$user_id = null;

if (!empty($token)) {
    $db = new db_conn();
    if ($db->db_connect()) {
        $now = date('Y-m-d H:i:s');
        $sql = "SELECT victim_id FROM victim WHERE reset_token = ? AND token_expires_at > ?";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([$token, $now]);
        
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $valid_token = true;
            $user_id = $user['victim_id'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | GBVAid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0f0a1e;
            color: #ffffff;
            font-family: 'Poppins', sans-serif;
            background-image: radial-gradient(#3c2a61 1px, transparent 1px);
            background-size: 30px 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .auth-card {
            background: rgba(26, 16, 51, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid #bf40ff;
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 15px 35px rgba(191, 64, 255, 0.2);
            text-align: center;
        }

        .auth-icon { font-size: 3rem; color: #d980ff; margin-bottom: 20px; }
        
        .form-control {
            background-color: #0f0a1e;
            border: 1px solid #3c2a61;
            color: #fff;
            padding: 12px 20px;
            border-radius: 10px;
        }
        .form-control:focus {
            background-color: #150d2b;
            border-color: #bf40ff;
            color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(191, 64, 255, 0.25);
        }

        .btn-auth {
            background: linear-gradient(135deg, #0dcaf0 0%, #4c1d95 100%);
            border: none;
            color: white;
            padding: 12px 20px;
            border-radius: 50px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
            margin-top: 20px;
        }
        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(13, 202, 240, 0.5);
            color: white;
        }
        .error-state { color: #ff4d4d; }
    </style>
</head>
<body>

<div class="auth-card">
    <?php if (!$valid_token): ?>
        <i class="fa fa-times-circle auth-icon error-state"></i>
        <h3 class="fw-bold mb-3 error-state">Invalid or Expired Link</h3>
        <p class="text-muted mb-4">The password reset link is invalid or has expired. Please request a new one.</p>
        <a href="forgot_password.php" class="btn btn-auth" style="background: #3c2a61;">Request New Link</a>
    <?php else: ?>
        <i class="fa fa-key auth-icon text-info"></i>
        <h3 class="fw-bold mb-2">Create New Password</h3>
        <p class="text-muted small mb-4">Enter a strong new password for your account.</p>

        <form id="resetForm">
            <input type="hidden" id="token" value="<?= htmlspecialchars($token) ?>">
            
            <div class="mb-3 text-start">
                <label class="form-label text-light fw-bold ms-1">New Password</label>
                <div class="input-group">
                    <span class="input-group-text" style="background:#150d2b; border:1px solid #3c2a61; color:#0dcaf0;">
                        <i class="fa fa-lock"></i>
                    </span>
                    <input type="password" class="form-control" id="password" required minlength="6">
                </div>
            </div>

            <div class="mb-4 text-start">
                <label class="form-label text-light fw-bold ms-1">Confirm Password</label>
                <div class="input-group">
                    <span class="input-group-text" style="background:#150d2b; border:1px solid #3c2a61; color:#0dcaf0;">
                        <i class="fa fa-lock"></i>
                    </span>
                    <input type="password" class="form-control" id="confirm_password" required minlength="6">
                </div>
            </div>

            <button type="submit" class="btn btn-auth" id="submitBtn">
                <i class="fa fa-save me-2"></i>Reset Password
            </button>
        </form>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('#resetForm').submit(function(e) {
        e.preventDefault();
        
        const pwd = $('#password').val();
        const confirmPts = $('#confirm_password').val();
        const token = $('#token').val();

        if (pwd !== confirmPts) {
            Swal.fire('Error', 'Passwords do not match.', 'error');
            return;
        }

        const btn = $('#submitBtn');
        const defaultText = btn.html();
        btn.html('<i class="fa fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

        $.post('../actions/reset_password_action.php', { token: token, password: pwd }, function(res) {
            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Password Re-Secured',
                    text: 'Your password has been successfully reset. You can now login.',
                    confirmButtonColor: '#0dcaf0',
                    background: '#1a1033',
                    color: '#fff'
                }).then(() => {
                    window.location.href = 'login.php';
                });
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        }, 'json').fail(function() {
            Swal.fire('Error', 'Connection failed.', 'error');
        }).always(function() {
            btn.html(defaultText).prop('disabled', false);
        });
    });
});
</script>

</body>
</html>
