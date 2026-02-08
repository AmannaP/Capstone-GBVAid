<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// User State
$is_logged_in = isset($_SESSION['id']);
$customer_name = $is_logged_in ? ($_SESSION['name'] ?? 'User') : 'Guest';
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
    }

    /* Safety Mask Exit Button in Navbar */
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

    .dropdown-menu {
        background-color: #1a1033;
        border: 1px solid #bf40ff;
    }
    
    .dropdown-item { color: #f0d9ff; }
    .dropdown-item:hover { background-color: #bf40ff; color: white; }
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
                <li class="nav-item"><a class="nav-link" href="#">Services</a></li>
                
                <?php if ($is_logged_in): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <?= htmlspecialchars($customer_name) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                            <li><hr class="dropdown-divider" style="border-color: #3c2a61;"></li>
                            <li><a class="dropdown-item text-danger" href="../login/logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="../login/login.php">Login</a></li>
                <?php endif; ?>

                <li class="nav-item">
                    <a href="https://www.google.com" class="nav-link btn-quick-exit">QUICK EXIT</a>
                </li>
            </ul>
        </div>
    </div>
</nav>