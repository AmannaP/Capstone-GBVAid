<?php
require_once '../settings/core.php';
require_once '../controllers/customer_controller.php';

if (!checkLogin()) {
    header("Location: ../login/login.php");
    exit();
}

$user = get_customer_ctr($_SESSION['id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile | GBVAid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body style="background-color: #f8f9fa;">

<?php include '../views/navbar.php'; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow p-4 rounded-4">
                <h3 class="fw-bold mb-4 text-center" style="color: #c453eaff;">Profile Settings</h3>
                
                <form id="profile-form">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Full Name</label>
                            <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user['customer_name']) ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Email (Cannot Change)</label>
                            <input type="email" class="form-control bg-light" value="<?= htmlspecialchars($user['customer_email']) ?>" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Phone Number</label>
                            <input type="text" name="phone_number" class="form-control" value="<?= htmlspecialchars($user['customer_contact']) ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">City</label>
                            <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($user['customer_city']) ?>">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Country</label>
                            <input type="text" name="country" class="form-control" value="<?= htmlspecialchars($user['customer_country']) ?>">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn w-100 text-white fw-bold mt-3" style="background-color: #c453eaff;">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $('#profile-form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '../actions/update_profile_action.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if(res.status === 'success') {
                    Swal.fire('Updated!', res.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            }
        });
    });
</script>
</body>
</html>