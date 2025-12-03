<?php
require_once '../settings/core.php';

// Restrict access to admins only
requireAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | GBVAid Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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

        .navbar-brand {
            font-weight: 800;
            color: #fff !important;
            font-size: 1.5rem;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            transition: all 0.3s;
        }
        .nav-link:hover {
            color: #fff !important;
            transform: translateY(-2px);
        }

        /* Dashboard Header */
        .dashboard-header {
            margin-top: 50px;
            margin-bottom: 40px;
        }
        .welcome-text {
            color: #c453eaff;
            font-weight: 800;
        }

        /* Card Styling */
        .dashboard-card {
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease-in-out;
            background: white;
            height: 100%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            padding: 30px 20px;
            text-align: center;
        }

        .dashboard-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(196, 83, 234, 0.15);
        }

        .card-icon {
            font-size: 2.5rem;
            color: #c453eaff;
            margin-bottom: 20px;
            background-color: #f3e8ff;
            width: 80px;
            height: 80px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .dashboard-card h5 {
            color: #333;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .dashboard-card p {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 25px;
        }

        /* Buttons */
        .btn-purple {
            background-color: #c453eaff;
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 600;
            width: 100%;
            color: white;
            transition: background 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-purple:hover {
            background-color: #a020f0;
            color: white;
        }
        
        /* Logout Button */
        .btn-logout {
            background-color: white;
            color: #c453eaff;
            border: 2px solid white;
            border-radius: 50px;
            padding: 5px 20px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
        }
        .btn-logout:hover {
            background-color: transparent;
            color: white;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-admin navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="bi bi-shield-lock-fill me-2"></i>GBVAid Admin</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item mx-2"><a href="../admin/brand.php" class="nav-link">Brands</a></li>
                    <li class="nav-item mx-2"><a href="../admin/category.php" class="nav-link">Categories</a></li>
                    <li class="nav-item mx-2"><a href="../admin/product.php" class="nav-link">Services</a></li>
                    
                    <li class="nav-item dropdown ms-4">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php
                                // Logic to check for profile image
                                $admin_img_path = "https://ui-avatars.com/api/?name=" . urlencode($_SESSION['name']) . "&background=fff&color=c453ea";

                                if (isset($_SESSION['user_image']) && !empty($_SESSION['user_image'])) {
                                    $server_path = "../uploads/users/" . $_SESSION['user_image'];
                                    // Only use the local image if it actually exists on the server
                                    if (file_exists($server_path)) {
                                        // Add time() to force browser to reload the image (Cache Busting)
                                        $admin_img_path = $server_path . "?v=" . time();
                                    }
                                }
                            ?>
                            <img src="<?= $admin_img_path ?>" alt="Admin" 
                                style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 10px; border: 2px solid white;">
                            <span class="fw-bold text-white">
                                <?= htmlspecialchars($_SESSION['name']); ?>
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow" aria-labelledby="adminDropdown">
                            <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person-gear me-2"></i>Edit Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="../login/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container dashboard-header">
        <div class="text-center mb-5">
            <h2 class="welcome-text display-5">Admin Control Center</h2>
            <p class="text-muted fs-5" style="max-width: 700px; margin: 0 auto;">
                Manage GBV-related resources, service categories, and support data to ensure victims and survivors receive timely help.
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            
            <div class="col-md-6 col-lg-4">
                <div class="dashboard-card">
                    <div class="card-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <h5>Manage Services</h5>
                    <p>View, create, and organize GBV support service categories and providers.</p>
                    <a href="../admin/product.php" class="btn-purple">Go to Services</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="dashboard-card">
                    <div class="card-icon">
                        <i class="bi bi-file-earmark-medical"></i>
                    </div>
                    <h5>Survivor Reports</h5>
                    <p>Review and manage confidential cases submitted by survivors.</p>
                    <a href="../admin/reports.php" class="btn-purple">View Reports</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="dashboard-card">
                    <div class="card-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <h5>Appointments</h5>
                    <p>Manage appointment bookings made by users with service providers.</p>
                    <a href="../admin/bookings.php" class="btn-purple">View Bookings</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="dashboard-card">
                    <div class="card-icon">
                        <i class="bi bi-megaphone"></i>
                    </div>
                    <h5>Awareness</h5>
                    <p>Post educational materials and campaigns to raise awareness about GBV.</p>
                    <a href="../admin/awareness.php" class="btn-purple">Manage Content</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="dashboard-card">
                    <div class="card-icon">
                        <i class="bi bi-chat-square-quote-fill"></i>
                    </div>
                    <h5>Chat Groups</h5>
                    <p>Create and manage community support chat rooms for different survivor groups.</p>
                    <a href="../admin/manage_groups.php" class="btn-purple">Manage Groups</a>
                </div>
            </div>
            
        </div>

        <div class="text-center mt-5 mb-4">
            <p class="text-muted small opacity-75">&copy; <?= date('Y'); ?> GBVAid Platform. Secure Admin Access.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>