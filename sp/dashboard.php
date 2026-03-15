<?php
/**
 * Service Provider Dashboard
 * Part of the "Unified Aid" Gateway
 */
require_once '../settings/core.php';
require_once '../settings/db_class.php';

// Ensure user is logged in, has the Provider role (3), and is approved
// Note: Adjusted to use getUserId() or session ID based on your core.php setup
if (!isset($_SESSION['id']) || $_SESSION['role'] != 3) {
    header("Location: ../login/login.php");
    exit();
}

// Check if the SP is approved by the system admin
if (!isset($_SESSION['sp_approved']) || $_SESSION['sp_approved'] == 0) {
    header("Location: pending_approval.php");
    exit();
}

$sp_id = $_SESSION['id'];
$db = new db_conn();

/**
 * Fetch Service Provider Details
 * We assume the SP is stored in the 'victim' table with role 3
 * and has a provider_category_id assigned.
 */
$sp_query = "
    SELECT v.provider_category_id, c.cat_name 
    FROM victim v 
    LEFT JOIN categories c ON v.provider_category_id = c.cat_id 
    WHERE v.victim_id = $sp_id
";
$sp_info = $db->db_fetch_one($sp_query);

$cat_id = $sp_info['provider_category_id'] ?? 0;
$cat_name = $sp_info['cat_name'] ?? 'General Support';

/**
 * Fetch Categorized Counts for the SP's specific Category
 * This logic ensures a legal aid provider only sees counts for legal cases.
 */
$booking_query = "
    SELECT a.status 
    FROM appointments a 
    INNER JOIN services s ON a.service_id = s.service_id 
    WHERE s.service_cat = $cat_id
";
$all_bookings = $db->db_fetch_all($booking_query);

$pending_count = 0;
$active_count = 0;
$resolved_count = 0;

if ($all_bookings) {
    foreach ($all_bookings as $b) {
        $s = strtolower($b['status']);
        if ($s == 'pending') $pending_count++;
        if ($s == 'assigned' || $s == 'investigating') $active_count++;
        if ($s == 'resolved' || $s == 'completed') $resolved_count++;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SP Dashboard | GBVAid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #0f0a1e;
            color: #ffffff;
            font-family: 'Poppins', sans-serif;
            background-image: radial-gradient(#3c2a61 1px, transparent 1px);
            background-size: 30px 30px;
            background-attachment: fixed;
        }

        .dashboard-header {
            background: linear-gradient(135deg, rgba(76, 29, 149, 0.8) 0%, rgba(30, 27, 75, 0.6) 100%);
            backdrop-filter: blur(10px);
            padding: 60px 0;
            border-bottom: 1px solid #bf40ff;
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(191, 64, 255, 0.2);
        }

        .text-neon-purple {
            color: #e0aaff;
            text-shadow: 0 0 10px rgba(191, 64, 255, 0.3);
        }

        .stat-card {
            background: rgba(26, 16, 51, 0.9);
            border: 1px solid #3c2a61;
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .stat-card:hover {
            transform: translateY(-8px);
            border-color: #bf40ff;
            box-shadow: 0 12px 25px rgba(191, 64, 255, 0.2);
        }

        .stat-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            filter: drop-shadow(0 0 8px rgba(191, 64, 255, 0.4));
        }

        .stat-number {
            font-size: 2.8rem;
            font-weight: 800;
            color: #ffffff;
            line-height: 1;
        }

        .text-custom-muted {
            color: #b89fd4 !important;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
            margin-top: 5px;
        }

        .action-card {
            background: rgba(191, 64, 255, 0.1);
            border: 1px solid #3c2a61;
            border-radius: 24px;
            padding: 40px;
            color: white;
            text-decoration: none;
            display: block;
            text-align: center;
            transition: 0.3s;
            height: 100%;
        }

        .action-card:hover {
            background: rgba(191, 64, 255, 0.2);
            transform: scale(1.03);
            color: white;
            border-color: #bf40ff;
            box-shadow: 0 15px 35px rgba(191, 64, 255, 0.3);
        }

        .action-icon {
            font-size: 3.5rem;
            margin-bottom: 20px;
            color: #e0aaff;
        }

        .vault-gradient {
            background: linear-gradient(135deg, #1e1b4b, #4c1d95) !important;
        }
    </style>
</head>
<body>

<?php 
// Including the SP navbar - path updated to match your project structure
if (file_exists('../includes/sp_navbar.php')) {
    include '../includes/sp_navbar.php';
} else {
    include '../views/sp_navbar.php';
}
?>

<div class="dashboard-header text-center">
    <div class="container animate__animated animate__fadeIn">
        <h1 class="fw-bold display-5 text-neon-purple">Provider Workspace</h1>
        <h2 class="fw-normal fs-4 text-light mt-2">Welcome back, <?= htmlspecialchars($_SESSION['name']); ?></h2>
        <div class="mt-3">
            <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning border border-warning px-3 py-2">
                <i class="bi bi-shield-check me-2"></i><?= htmlspecialchars($cat_name) ?> Division
            </span>
        </div>
    </div>
</div>

<div class="container mb-5">
    <!-- Statistics Section -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="stat-card">
                <i class="bi bi-hourglass-split stat-icon text-warning"></i>
                <div class="stat-number"><?= $pending_count ?></div>
                <div class="text-custom-muted">Pending Requests</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <i class="bi bi-activity stat-icon text-info"></i>
                <div class="stat-number"><?= $active_count ?></div>
                <div class="text-custom-muted">In-Progress Cases</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <i class="bi bi-check2-circle stat-icon text-success"></i>
                <div class="stat-number"><?= $resolved_count ?></div>
                <div class="text-custom-muted">Resolved Incidents</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <h4 class="fw-bold mb-4 text-neon-purple ms-2">Operations</h4>
    <div class="row g-4">
        <div class="col-md-6">
            <a href="triage.php" class="action-card">
                <i class="bi bi-clipboard-pulse action-icon"></i>
                <h3 class="fw-bold">Active Case Triage</h3>
                <p class="opacity-75 mb-0 px-md-4">Review incoming crisis alerts, monitor AI triage suggestions, and update victim statuses.</p>
            </a>
        </div>
        <div class="col-md-6">
            <a href="evidence_handshake.php" class="action-card vault-gradient">
                <i class="bi bi-shield-lock-fill action-icon"></i>
                <h3 class="fw-bold">Evidence Vault Handshake</h3>
                <p class="opacity-75 mb-0 px-md-4">Securely access survivor-uploaded media using One-Time Authorization PINs for legal or medical review.</p>
            </a>
        </div>
    </div>
</div>

<footer class="text-center py-4">
    <p class="small text-muted opacity-50 mb-0">GBVAid Professional Portal &copy; <?= date('Y') ?></p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>