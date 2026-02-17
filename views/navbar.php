<?php
// 1. Session & Cart/Booking Logic
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// $cart_count = 0;
// // Check for cart controller to fetch booking count
// $controller_path = dirname(__DIR__) . '/controllers/cart_controller.php';
// if (file_exists($controller_path)) {
//     require_once($controller_path);
//     $uid = isset($_SESSION['id']) ? $_SESSION['id'] : null;
//     $ip_addr = $_SERVER['REMOTE_ADDR'];
    
//     // Fetch items (Bookings)
//     $c_items = get_user_cart_ctr($uid ?? $ip_addr);
//     if ($c_items) {
//         $cart_count = count($c_items);
//     }
// }

// User State
$is_logged_in = isset($_SESSION['id']);
$victim_name = $is_logged_in ? ($_SESSION['name'] ?? 'User') : 'Guest';
?>

<style>
    .navbar-custom {
        background-color: rgba(15, 10, 30, 0.95);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid #bf40ff;
        padding: 12px 0;
    }
    
    .brand-logo {
        color: #d980ff !important;
        font-weight: 800;
        font-size: 24px;
        text-decoration: none;
    }

    .nav-link {
        color: #f0d9ff !important;
        font-weight: 600;
        transition: all 0.3s;
        font-size: 15px;
    }

    .nav-link:hover {
        color: white !important;
        transform: translateY(-1px);
    }

    /* Badge for Bookings */
    .badge-notification {
        background-color: #bf40ff;
        color: white;
        font-size: 0.7rem;
        font-weight: 800;
    }

    /* Safety Mask Exit Button */
    .btn-quick-exit {
        background-color: #ff4d4d;
        color: white !important;
        border-radius: 50px;
        padding: 6px 18px !important;
        font-weight: 800;
        border: 2px solid #ff4d4d;
        margin-left: 15px;
        animation: pulse-red-small 2s infinite;
    }
    
    .btn-quick-exit:hover {
        background-color: transparent;
        color: #ff4d4d !important;
    }

    @keyframes pulse-red-small {
        0% { box-shadow: 0 0 0 0 rgba(255, 77, 77, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(255, 77, 77, 0); }
        100% { box-shadow: 0 0 0 0 rgba(255, 77, 77, 0); }
    }

    /* Dropdown Styling */
    .dropdown-menu {
        background-color: #1a1033;
        border: 1px solid #bf40ff;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        border-radius: 12px;
    }
    
    .dropdown-item { 
        color: #f0d9ff; 
        padding: 8px 20px;
    }

    .dropdown-item:hover { 
        background-color: #bf40ff; 
        color: white; 
    }

    /* Auth Buttons */
    .btn-auth {
        border-radius: 50px;
        padding: 6px 20px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s;
    }
    
    .btn-login {
        background-color: transparent;
        color: white;
        border: 2px solid #bf40ff;
    }
    
    .btn-register {
        background-color: #bf40ff;
        color: white;
        border: 2px solid #bf40ff;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand brand-logo" href="../user/dashboard.php">
            <i class="bi bi-shield-shaded me-2"></i>GBVAid
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="../user/dashboard.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="../user/service_page.php">Services</a></li>
                
                <!-- <li class="nav-item position-relative">
                    <a class="nav-link" href="#">Bookings
                        <i class="bi bi-calendar-check me-1"></i> Bookings
                        <?php if ($cart_count > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill badge-notification">
                                <?= $cart_count ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </li> -->

                <li class="nav-item d-none d-lg-block mx-2">
                    <div style="border-left: 1px solid rgba(191,64,255,0.3); height: 20px;"></div>
                </li>

                <?php if ($is_logged_in): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <?php
                                // Avatar Logic
                                $user_img_path = "https://ui-avatars.com/api/?name=" . urlencode($_SESSION['name']) . "&background=bf40ff&color=fff&bold=true";
                                if (isset($_SESSION['user_image']) && !empty($_SESSION['user_image'])) {
                                    $img_name = $_SESSION['user_image'];
                                    $possible_path = dirname(__DIR__) . "/uploads/users/" . $img_name;
                                    if (file_exists($possible_path)) {
                                        $user_img_path = "../uploads/users/" . $img_name . "?v=" . time();
                                    }
                                }
                            ?>
                            <img src="<?= $user_img_path ?>" alt="Profile" style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover; margin-right: 8px; border: 1.5px solid #bf40ff;">
                            <?= htmlspecialchars($victim_name) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="../user/profile.php"><i class="bi bi-person me-2"></i>My Profile</a></li>
                            <li><a class="dropdown-item" href="../user/my_appointments.php"><i class="bi bi-journal-text me-2"></i>My Sessions</a></li>
                            <li><hr class="dropdown-divider" style="border-color: #3c2a61;"></li>
                            <li><a class="dropdown-item text-danger" href="../login/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-auth btn-login me-2" href="../login/login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-auth btn-register" href="../login/register.php">Register</a>
                    </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a href="https://wellfitinsider.com/fitness-club-guides/best-online-workout-programs/" class="nav-link btn-quick-exit">QUICK EXIT</a>
                </li>
            </ul>
        </div>
    </div>
</nav>