<?php
require_once '../settings/core.php';
require_once '../controllers/customer_controller.php';

// Restrict access to admins only
if (!checkLogin() || !isAdmin()) {
    header("Location: ../login/login.php");
    exit();
}

// Fetch Admin Data
$user = get_customer_ctr($_SESSION['id']);

// Determine Profile Image
$profile_pic = !empty($user['customer_image']) 
    ? "../uploads/users/" . $user['customer_image'] 
    : "https://ui-avatars.com/api/?name=" . urlencode($user['customer_name']) . "&background=c453ea&color=fff";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile | GBVAid Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Admin Navbar */
        .navbar-admin {
            background-color: #c453eaff;
            box-shadow: 0 4px 12px rgba(196, 83, 234, 0.3);
            padding: 15px 0;
        }
        .navbar-brand { font-weight: 800; color: #fff !important; font-size: 1.5rem; }
        .nav-link { color: rgba(255,255,255,0.9) !important; font-weight: 500; }
        .nav-link:hover { color: #fff !important; }

        /* Profile Card */
        .content-card {
            border: none;
            border-radius: 15px;
            background: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            padding: 40px;
            margin-top: 40px;
            margin-bottom: 40px;
        }

        /* Image Upload Styling */
        .profile-img-box {
            position: relative;
            width: 130px;
            height: 130px;
            margin: 0 auto;
        }
        .profile-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid #f3e8ff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .camera-icon {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: #c453eaff;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 3px solid white;
            transition: background 0.3s;
        }
        .camera-icon:hover { background-color: #a020f0; }
        #fileInput { display: none; }

        /* Inputs */
        .form-label { font-weight: 700; color: #555; font-size: 0.9rem; }
        .form-control {
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #dee2e6;
        }
        .form-control:focus {
            border-color: #c453eaff;
            box-shadow: 0 0 0 4px rgba(196, 83, 234, 0.1);
        }

        .btn-purple {
            background-color: #c453eaff;
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 700;
            width: 100%;
            color: white;
            transition: background 0.3s;
        }
        .btn-purple:hover { background-color: #a020f0; color: white; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-admin navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php"><i class="bi bi-shield-lock-fill me-2"></i>GBVAid Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item mx-2"><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                    <li class="nav-item dropdown ms-4">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                            <div style="width: 35px; height: 35px; background: white; color: #c453eaff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <span class="fw-bold"><?= htmlspecialchars($_SESSION['name']); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                            <li><a class="dropdown-item active" href="profile.php" style="background-color: #c453eaff;"><i class="bi bi-person-gear me-2"></i>Edit Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="../login/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                
                <div class="content-card">
                    <h3 class="text-center fw-bold mb-4" style="color: #c453eaff;">Admin Profile</h3>
                    
                    <form id="profile-form" enctype="multipart/form-data">
                        
                        <div class="text-center mb-5">
                            <div class="profile-img-box">
                                <img src="<?= $profile_pic ?>" id="previewImg" class="profile-img" alt="Admin Profile">
                                <label for="fileInput" class="camera-icon" title="Change Photo">
                                    <i class="bi bi-camera-fill"></i>
                                </label>
                                <input type="file" id="fileInput" name="profile_image" accept="image/*" onchange="previewFile(this)">
                            </div>
                            <div class="mt-2 text-muted small">Administrator</div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user['customer_name']) ?>" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Email Address (Read Only)</label>
                                <input type="email" class="form-control bg-light" value="<?= htmlspecialchars($user['customer_email']) ?>" readonly>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone_number" class="form-control" value="<?= htmlspecialchars($user['customer_contact']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($user['customer_city']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Country</label>
                                <input type="text" name="country" class="form-control" value="<?= htmlspecialchars($user['customer_country']) ?>">
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-purple mt-4 shadow-sm">
                            <i class="bi bi-check-circle-fill me-2"></i>Save Changes
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Image Preview
        function previewFile(input) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function() {
                    $("#previewImg").attr("src", reader.result);
                }
                reader.readAsDataURL(file);
            }
        }

        // Submit Form
        $('#profile-form').on('submit', function(e) {
            e.preventDefault();
            
            const btn = $(this).find('button[type="submit"]');
            const originalText = btn.html();
            btn.html('<span class="spinner-border spinner-border-sm"></span> Saving...').prop('disabled', true);

            const formData = new FormData(this);

            // Reusing the same action file since Admins are in the same table!
            $.ajax({
                url: '../actions/update_profile_action.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(res) {
                    if(res.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Profile Updated',
                            text: res.message,
                            confirmButtonColor: '#c453eaff'
                        }).then(() => location.reload());
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    Swal.fire('Error', 'Server connection failed', 'error');
                },
                complete: function() {
                    btn.html(originalText).prop('disabled', false);
                }
            });
        });
    </script>
</body>
</html>