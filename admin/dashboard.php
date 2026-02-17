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
            background-color: #0f0a1e; /* Matches User Dashboard */
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            color: #ffffff;
            /* Star-like background dots */
            background-image: radial-gradient(#3c2a61 1px, transparent 1px);
            background-size: 30px 30px;
            background-attachment: fixed;
        }

        /* Admin Navbar - Adjusted to match the dark theme */
        .navbar-admin {
            background-color: rgba(196, 83, 234, 0.2);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(191, 64, 255, 0.3);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
            padding: 15px 0;
        }

        .navbar-brand {
            font-weight: 800;
            color: #e0aaff !important;
            font-size: 1.5rem;
        }

        .nav-link {
            color: rgba(224, 170, 255, 0.8) !important;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .nav-link:hover {
            color: #ffffff !important;
            transform: translateY(-2px);
        }

        /* Dashboard Header */
        .dashboard-header {
            margin-top: 50px;
            margin-bottom: 40px;
        }
        
        .welcome-text {
            background: linear-gradient(to bottom, #ffffff 20%, #e0aaff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
        }

        /* Card Styling - Matches User Dashboard Glassmorphism */
        .dashboard-card {
            border: 1px solid #3c2a61;
            border-radius: 20px;
            transition: all 0.3s ease-in-out;
            background: rgba(26, 16, 51, 0.9);
            height: 100%;
            backdrop-filter: blur(5px);
            padding: 30px 20px;
            text-align: center;
        }

        .dashboard-card:hover {
            transform: translateY(-8px);
            border-color: #bf40ff;
            box-shadow: 0 12px 25px rgba(191, 64, 255, 0.2);
            background: rgba(36, 20, 69, 0.95);
        }

        /* SOS Monitor Specific Styling */
        .emergency-card {
            border: 1px dashed rgba(255, 77, 77, 0.5);
            background: rgba(255, 0, 0, 0.05);
        }
        
        .emergency-card:hover {
            border-color: #ff4d4d;
            background: rgba(255, 0, 0, 0.1);
            box-shadow: 0 0 30px rgba(255, 0, 0, 0.2) !important;
        }

        .card-icon {
            font-size: 2.5rem;
            color: #d980ff;
            margin-bottom: 20px;
            background: rgba(191, 64, 255, 0.1);
            border: 1px solid rgba(191, 64, 255, 0.3);
            width: 80px;
            height: 80px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .icon-emergency {
            background: rgba(255, 0, 0, 0.15);
            border-color: rgba(255, 77, 77, 0.4);
            color: #ff4d4d;
        }

        .dashboard-card h5 {
            color: #e0aaff;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .dashboard-card p {
            color: #cbd5e1;
            font-size: 0.9rem;
            margin-bottom: 25px;
        }

        /* Buttons */
        .btn-purple {
            background-color: #9d4edd;
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 600;
            width: 100%;
            color: white;
            transition: 0.3s;
            display: inline-block;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(157, 78, 221, 0.3);
        }

        .btn-purple:hover {
            background-color: #bf40ff;
            color: white;
            box-shadow: 0 6px 20px rgba(191, 64, 255, 0.5);
        }

        .btn-danger-pill {
            background: linear-gradient(45deg, #ff0000, #990000);
            color: white;
            border-radius: 50px;
            font-weight: 600;
            padding: 10px 25px;
            width: 100%;
            display: inline-block;
            text-decoration: none;
            transition: 0.3s;
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.3);
        }

        .btn-danger-pill:hover {
            background: linear-gradient(45deg, #ff3333, #bb0000);
            color: white;
            box-shadow: 0 0 25px rgba(255, 0, 0, 0.6);
        }

        /* Pulse Animation for Active Badge */
        .pulse-badge {
            animation: pulse-red-glow 2s infinite;
        }

        @keyframes pulse-red-glow {
            0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
            100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
        }

        footer {
            border-top: 1px solid #3c2a61;
            padding: 40px 0;
            background: rgba(10, 7, 20, 0.8);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-admin navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="bi bi-shield-lock-fill me-2"></i>GBVAid Admin</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item mx-2"><a href="../admin/brand.php" class="nav-link">Brands</a></li>
                    <li class="nav-item mx-2"><a href="../admin/category.php" class="nav-link">Categories</a></li>
                    <li class="nav-item mx-2"><a href="../admin/service.php" class="nav-link">Services</a></li>
                    <li class="nav-item dropdown ms-4">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                            <?php
                                $admin_img_path = "https://ui-avatars.com/api/?name=" . urlencode($_SESSION['name']) . "&background=3c2a61&color=fff";
                                if (isset($_SESSION['user_image']) && !empty($_SESSION['user_image'])) {
                                    $server_path = "../uploads/users/" . $_SESSION['user_image'];
                                    if (file_exists($server_path)) $admin_img_path = $server_path . "?v=" . time();
                                }
                            ?>
                            <img src="<?= $admin_img_path ?>" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 10px; border: 2px solid #bf40ff;">
                            <span class="fw-bold" style="color: #e0aaff;"><?= htmlspecialchars($_SESSION['name']); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow bg-dark">
                            <li><a class="dropdown-item text-light" href="profile.php"><i class="bi bi-person-gear me-2"></i>Edit Profile</a></li>
                            <li><hr class="dropdown-divider bg-secondary"></li>
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
            <p class="text-muted-custom fs-5" style="max-width: 700px; margin: 0 auto; color: #cbd5e1;">
                High-priority monitoring and platform resource management.
            </p>
        </div>

        <div class="row justify-content-center mb-5">
            <div class="col-md-8 col-lg-5">
                <div class="dashboard-card emergency-card shadow">
                    <div class="card-icon icon-emergency">
                        <i class="bi bi-broadcast"></i>
                    </div>
                    <h5 class="text-danger">SOS Live Monitor</h5>
                    <p>Real-time emergency tracking. <span id="activeCountBadge" class="badge bg-danger pulse-badge">0 Active</span></p>
                    <a href="../admin/sos_monitor.php" class="btn-danger-pill">Open Live Monitor</a>
                </div>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="dashboard-card">
                    <div class="card-icon"><i class="bi bi-box-seam"></i></div>
                    <h5>Manage Services</h5>
                    <p>View, create, and organize GBV support service categories and providers.</p>
                    <a href="../admin/service.php" class="btn-purple">Go to Services</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="dashboard-card">
                    <div class="card-icon"><i class="bi bi-file-earmark-medical"></i></div>
                    <h5>Survivor Reports</h5>
                    <p>Review and manage confidential cases submitted by survivors.</p>
                    <a href="../admin/reports.php" class="btn-purple">View Reports</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="dashboard-card">
                    <div class="card-icon"><i class="bi bi-calendar-check"></i></div>
                    <h5>Appointments</h5>
                    <p>Manage appointment bookings made by users with service providers.</p>
                    <a href="../admin/bookings.php" class="btn-purple">View Bookings</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="dashboard-card">
                    <div class="card-icon"><i class="bi bi-megaphone"></i></div>
                    <h5>Awareness</h5>
                    <p>Post educational materials and campaigns to raise awareness about GBV.</p>
                    <a href="../admin/awareness.php" class="btn-purple">Manage Content</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="dashboard-card">
                    <div class="card-icon"><i class="bi bi-chat-square-quote-fill"></i></div>
                    <h5>Chat Groups</h5>
                    <p>Create and manage community support chat rooms for different survivor groups.</p>
                    <a href="../admin/manage_groups.php" class="btn-purple">Manage Groups</a>
                </div>
            </div>
        </div>

        <audio id="policeSiren" src="../assets/sounds/police_siren.mp3" preload="auto"></audio>
        <audio id="voiceFeed" src="../assets/sounds/ambient_bg_noise.mp3" preload="auto" loop></audio>

        <div class="text-center mt-5 mb-4">
            <p class="text-muted small opacity-75" style="color: #cbd5e1;">&copy; <?= date('Y'); ?> GBVAid Platform. Secure Admin Access.</p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/admin_sos.js"></script>
</body>
</html>