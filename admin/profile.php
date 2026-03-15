<?php
require_once '../settings/core.php';
require_once '../controllers/victim_controller.php';

// Restrict access to admins only
if (!checkLogin() || !isAdmin()) {
    header("Location: ../login/login.php");
    exit();
}

// Fetch Admin Data
$user = get_victim_ctr($_SESSION['id']);

// Determine Profile Image
$profile_pic = !empty($user['victim_image']) 
    ? "../uploads/users/" . $user['victim_image'] 
    : "https://ui-avatars.com/api/?name=" . urlencode($user['victim_name']) . "&background=9d4edd&color=fff&bold=true";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile | GBVAid Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #0f0a1e;
            background-image: radial-gradient(#3c2a61 1px, transparent 1px);
            background-size: 30px 30px;
            background-attachment: fixed;
            font-family: 'Poppins', sans-serif;
            color: #ffffff;
            min-height: 100vh;
        }

        /* Navbar */
        .navbar-admin {
            background: rgba(26, 16, 51, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #3c2a61;
            padding: 15px 0;
        }
        .navbar-brand { font-weight: 800; color: #e0aaff !important; font-size: 1.5rem; }
        .nav-link { color: rgba(255,255,255,0.8) !important; font-weight: 500; }
        .nav-link:hover { color: #d980ff !important; }

        /* Profile banner */
        .profile-banner {
            background: linear-gradient(135deg, rgba(76, 29, 149, 0.8) 0%, rgba(30, 27, 75, 0.6) 100%);
            border-bottom: 1px solid #bf40ff;
            padding: 50px 0 40px;
            margin-bottom: 40px;
            text-align: center;
        }

        /* Main card */
        .profile-card {
            background: rgba(26, 16, 51, 0.95);
            border: 1px solid #3c2a61;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);
            margin-bottom: 30px;
        }

        .section-header {
            color: #e0aaff;
            font-weight: 700;
            font-size: 0.85rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            border-bottom: 1px solid #3c2a61;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        /* Image Upload */
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
            border: 4px solid #bf40ff;
            box-shadow: 0 0 20px rgba(191, 64, 255, 0.4);
        }
        .camera-icon {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: #9d4edd;
            color: white;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 3px solid #0f0a1e;
            transition: all 0.3s;
        }
        .camera-icon:hover { background: #bf40ff; transform: scale(1.1); }
        #fileInput { display: none; }

        /* Inputs */
        .form-label { font-weight: 600; color: #d980ff; font-size: 0.9rem; }
        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid #3c2a61;
            color: #ffffff;
            border-radius: 10px;
            padding: 12px;
        }
        .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: #bf40ff;
            color: #ffffff;
            box-shadow: 0 0 0 4px rgba(191, 64, 255, 0.2);
        }
        .form-control[readonly] {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .form-control::placeholder { color: #6c4898; }

        .btn-purple {
            background: linear-gradient(135deg, #9d4edd 0%, #bf40ff 100%);
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 700;
            width: 100%;
            color: white;
            transition: all 0.3s;
            box-shadow: 0 4px 20px rgba(191, 64, 255, 0.4);
        }
        .btn-purple:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(191, 64, 255, 0.6);
            color: white;
        }

        .admin-badge {
            display: inline-block;
            background: rgba(191, 64, 255, 0.2);
            border: 1px solid #bf40ff;
            color: #e0aaff;
            border-radius: 50px;
            padding: 4px 16px;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .stat-box {
            background: rgba(191, 64, 255, 0.08);
            border: 1px solid rgba(191, 64, 255, 0.2);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
        }
        .stat-number { font-size: 1.8rem; font-weight: 800; color: #e0aaff; }
        .stat-label { font-size: 0.8rem; color: #c8a8e9; text-transform: uppercase; letter-spacing: 1px; }
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
                            <div style="width: 35px; height: 35px; background: rgba(191,64,255,0.2); border: 1px solid #bf40ff; color: #e0aaff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <span class="fw-bold"><?= htmlspecialchars($_SESSION['name']); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" style="background: #1a1033; border: 1px solid #bf40ff; border-radius: 12px;">
                            <li><a class="dropdown-item" href="profile.php" style="color: #e0aaff;"><i class="bi bi-person-gear me-2"></i>Edit Profile</a></li>
                            <li><hr class="dropdown-divider" style="border-color: #3c2a61;"></li>
                            <li><a class="dropdown-item text-danger" href="../login/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Profile Banner -->
    <div class="profile-banner">
        <div class="container">
            <div class="profile-img-box mx-auto mb-4">
                <img src="<?= $profile_pic ?>" id="previewImg" class="profile-img" alt="Admin Profile">
                <label for="fileInput" class="camera-icon" title="Change Photo">
                    <i class="bi bi-camera-fill"></i>
                </label>
                <input type="file" id="fileInput" name="profile_image" accept="image/*" onchange="previewFile(this)">
            </div>
            <h2 class="fw-bold mb-2" style="background: linear-gradient(to right, #ffffff, #e0aaff); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                <?= htmlspecialchars($user['victim_name']) ?>
            </h2>
            <span class="admin-badge"><i class="bi bi-shield-check me-1"></i>System Administrator</span>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <form id="profile-form" enctype="multipart/form-data">
                    <!-- Personal Info Card -->
                    <div class="profile-card">
                        <div class="section-header"><i class="bi bi-person-fill me-2"></i>Personal Information</div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user['victim_name']) ?>" required placeholder="Your full name">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Email Address <span style="color: #c8a8e9; font-size: 0.75rem;">(read-only)</span></label>
                                <input type="email" class="form-control" value="<?= htmlspecialchars($user['victim_email']) ?>" readonly>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone_number" class="form-control" value="<?= htmlspecialchars($user['victim_contact']) ?>" placeholder="Your phone number">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($user['victim_city']) ?>" placeholder="Your city">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Country</label>
                                <input type="text" name="country" class="form-control" value="<?= htmlspecialchars($user['victim_country']) ?>" placeholder="Your country">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-purple mt-4 shadow-sm">
                            <i class="bi bi-check-circle-fill me-2"></i>Save Changes
                        </button>
                    </div>
                </form>

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
            // Append file from the banner input
            const fileInput = document.getElementById('fileInput');
            if (fileInput.files[0]) {
                formData.append('profile_image', fileInput.files[0]);
            }

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
                            confirmButtonColor: '#bf40ff',
                            background: '#1a1033',
                            color: '#fff'
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: res.message, background: '#1a1033', color: '#fff' });
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Server connection failed', background: '#1a1033', color: '#fff' });
                },
                complete: function() {
                    btn.html(originalText).prop('disabled', false);
                }
            });
        });
    </script>
</body>
</html>