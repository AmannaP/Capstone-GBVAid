<?php
// admin/admin_nav.php
require_once '../settings/core.php';
requireAdmin(); // Ensures session is active and user is role 2
?>

<nav class="navbar navbar-expand-lg navbar-admin navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="../admin/dashboard.php">
            <i class="bi bi-shield-lock-fill me-2"></i>GBVAid Admin
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item mx-2"><a href="../admin/brand.php" class="nav-link">Brands</a></li>
                <li class="nav-item mx-2"><a href="../admin/category.php" class="nav-link">Categories</a></li>
                <li class="nav-item mx-2"><a href="../admin/service.php" class="nav-link">Services</a></li>
                <li class="nav-item mx-2"><a href="../admin/manage_providers.php" class="nav-link">Approvals <span class="badge bg-danger rounded-pill">!</span></a></li>
                <li class="nav-item mx-2"><a href="../admin/help_desk.php" class="nav-link">Help Desk</a></li>
                
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