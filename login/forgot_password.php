<?php
require_once '../settings/core.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | GBVAid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
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

        .auth-icon {
            font-size: 3rem;
            color: #d980ff;
            margin-bottom: 20px;
        }

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
            background: linear-gradient(135deg, #9d4edd 0%, #bf40ff 100%);
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
            box-shadow: 0 8px 25px rgba(191, 64, 255, 0.5);
            color: white;
        }

        .link-muted {
            color: #cbd5e1;
            text-decoration: none;
            font-size: 0.9rem;
            transition: 0.3s;
        }

        .link-muted:hover {
            color: #bf40ff;
        }
    </style>
</head>
<body class="animate__animated animate__fadeIn">

<div class="auth-card animate__animated animate__zoomIn">
    <i class="fa fa-lock auth-icon"></i>
    <h3 class="fw-bold mb-2">Forgot Password?</h3>
    <p class="text-muted small mb-4">Enter your registered email address and we'll send you a link to reset your password securely.</p>

    <form id="forgotForm">
        <div class="mb-4 text-start">
            <label class="form-label text-light fw-bold ms-1">Email Space</label>
            <div class="input-group">
                <span class="input-group-text" style="background:#150d2b; border:1px solid #3c2a61; color:#bf40ff;">
                    <i class="fa fa-envelope"></i>
                </span>
                <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" required>
            </div>
        </div>

        <button type="submit" class="btn btn-auth mb-4" id="submitBtn">
            <i class="fa fa-paper-plane me-2"></i>Send Reset Link
        </button>
    </form>

    <div class="mt-2">
        <a href="login.php" class="link-muted"><i class="fa fa-arrow-left me-1"></i> Back to Login</a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('#forgotForm').submit(function(e) {
        e.preventDefault();
        
        const btn = $('#submitBtn');
        const defaultText = btn.html();
        btn.html('<i class="fa fa-spinner fa-spin"></i> Sending...').prop('disabled', true);

        $.post('../actions/forgot_password_action.php', { email: $('#email').val() }, function(res) {
            if (res.success) {
                // If the backend returned a demo link (because Composer isn't installed locally), show it
                if (res.reset_link) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Email Simulation Mode',
                        html: `${res.message}<br><br><small class="text-warning"><a href="${res.reset_link}" style="color:#bf40ff;">Click here to simulate opening email</a></small>`,
                        confirmButtonColor: '#bf40ff',
                        background: '#1a1033',
                        color: '#fff'
                    });
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Email Sent!',
                        text: res.message,
                        confirmButtonColor: '#bf40ff',
                        background: '#1a1033',
                        color: '#fff'
                    });
                }
                $('#forgotForm')[0].reset();
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: res.message, confirmButtonColor: '#bf40ff', background: '#1a1033', color: '#fff' });
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
