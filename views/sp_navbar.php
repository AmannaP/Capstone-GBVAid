<?php
/**
 * Service Provider Navbar Component
 * Part of the "Unified Aid" Gateway
 */
require_once __DIR__ . '/../settings/core.php';

// Safe retrieval of session name
$sp_name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Provider';
$current_page = basename($_SERVER['PHP_SELF']);
?>
<style>
    /* Midnight Neon Navbar Styles */
    .navbar-sp {
        background: rgba(15, 10, 30, 0.9);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid #3c2a61;
        padding: 12px 0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
    }

    .sp-brand {
        color: #e0aaff !important;
        font-weight: 800;
        font-size: 1.4rem;
        letter-spacing: 1px;
        text-shadow: 0 0 10px rgba(191, 64, 255, 0.3);
    }

    .sp-brand i {
        color: #bf40ff;
    }

    .navbar-sp .nav-link {
        color: rgba(255, 255, 255, 0.7) !important;
        font-weight: 500;
        padding: 8px 16px !important;
        transition: all 0.3s ease;
        border-bottom: 2px solid transparent;
    }

    .navbar-sp .nav-link:hover,
    .navbar-sp .nav-link.active {
        color: #d980ff !important;
    }

    .navbar-sp .nav-link.active {
        border-bottom: 2px solid #bf40ff;
    }

    /* Dropdown Customization */
    .navbar-sp .dropdown-menu {
        background-color: #1a1033;
        border: 1px solid #3c2a61;
        border-radius: 12px;
        margin-top: 10px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.5);
    }

    .navbar-sp .dropdown-item {
        color: #e0aaff;
        padding: 10px 20px;
        font-weight: 500;
        transition: 0.2s;
    }

    .navbar-sp .dropdown-item:hover {
        background-color: rgba(191, 64, 255, 0.15);
        color: #fff;
    }

    .navbar-sp .divider-vert {
        border-left: 1px solid rgba(191, 64, 255, 0.2);
        height: 24px;
        margin: 0 15px;
    }

    /* Badge for Live Alerts */
    .badge-alert {
        font-size: 0.6rem;
        vertical-align: top;
        margin-left: -5px;
        background-color: #ff4d4d;
        box-shadow: 0 0 8px rgba(255, 77, 77, 0.5);
    }

    @media (max-width: 991px) {
        .navbar-sp .divider-vert { display: none; }
        .navbar-sp .nav-link.active { border-bottom: none; border-left: 3px solid #bf40ff; padding-left: 20px !important; }
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark navbar-sp sticky-top">
    <div class="container">
        <!-- Unified Brand Home -->
        <a class="navbar-brand sp-brand d-flex align-items-center" href="../sp/dashboard.php">
            <i class="bi bi-shield-shaded me-2"></i>
            <span>GBVAid <small class="fs-6 opacity-50 fw-light">Provider</small></span>
        </a>

        <!-- Hamburger Menu -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'dashboard.php') ? 'active' : '' ?>" href="dashboard.php">
                        <i class="bi bi-grid-1x2 me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'triage.php') ? 'active' : '' ?>" href="triage.php">
                        <i class="bi bi-activity me-1"></i> Case Triage
                        <span class="badge rounded-pill badge-alert">Live</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'evidence_handshake.php') ? 'active' : '' ?>" href="evidence_handshake.php">
                        <i class="bi bi-key me-1"></i> Evidence Vault
                    </a>
                </li>

                <!-- Separator -->
                <li class="nav-item d-none d-lg-block">
                    <div class="divider-vert"></div>
                </li>

                <!-- Profile Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($sp_name) ?>&background=9d4edd&color=fff&bold=true"
                             alt="SP Profile"
                             style="width:32px; height:32px; border-radius:50%; margin-right:10px; border:2px solid #bf40ff;">
                        <span><?= htmlspecialchars($sp_name) ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="profileDropdown">
                        <li>
                            <a class="dropdown-item" href="profile.php">
                                <i class="bi bi-person-badge me-2 text-info"></i> My Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="settings.php">
                                <i class="bi bi-gear me-2 text-secondary"></i> Availability
                            </a>
                        </li>
                        <li><hr class="dropdown-divider" style="border-color:#3c2a61;"></li>
                        <li>
                            <a class="dropdown-item text-danger fw-bold" href="../login/logout.php">
                                <i class="bi bi-power me-2"></i> Sign Out
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>