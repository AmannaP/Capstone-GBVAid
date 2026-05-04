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

/**
 * Fetch Direct Messages (Shared PINs)
 */
$msg_query = "
    SELECT m.*, v.victim_name 
    FROM direct_messages m 
    JOIN victim v ON m.sender_id = v.victim_id 
    WHERE m.receiver_id = $sp_id 
    ORDER BY m.created_at DESC
";
$inbox_messages = $db->db_fetch_all($msg_query) ?: [];
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
            background-color: #0f0a1e; /* Matches User Dashboard */
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            color: #ffffff;
            background-image: radial-gradient(#3c2a61 1px, transparent 1px);
            background-size: 30px 30px;
            background-attachment: fixed;
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

        /* User Profile Style Standard Header Cards */
        .profile-card {
            background: #1a1033;
            border: 1px solid #bf40ff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(191, 64, 255, 0.2);
            margin-bottom: 30px;
        }

        .profile-card-header {
            background-color: #1a1033;
            color: #e0aaff;
            font-weight: 800;
            border-bottom: 1px solid #3c2a61;
            padding: 1.5rem;
            text-align: center;
        }

        .inbox-table { color: #fff; width: 100%; border-collapse: separate; border-spacing: 0 10px; }
        .inbox-table th { color: #d980ff; font-weight: 500; padding: 10px; border-bottom: 1px solid #3c2a61; }
        .inbox-table td { background: #150d2b; padding: 15px; vertical-align: middle; }
        .inbox-table tr td:first-child { border-top-left-radius: 10px; border-bottom-left-radius: 10px; }
        .inbox-table tr td:last-child { border-top-right-radius: 10px; border-bottom-right-radius: 10px; }

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

    <div class="container dashboard-header">
        <div class="text-center mb-5">
            <h2 class="welcome-text display-5">Provider Workspace</h2>
            <h2 class="fw-normal fs-4 text-light mt-2">Welcome back, <?= htmlspecialchars($_SESSION['name']); ?></h2>
            <div class="mt-3">
                <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning border border-warning px-3 py-2">
                    <i class="bi bi-shield-check me-2"></i><?= htmlspecialchars($cat_name) ?> Division
                </span>
            </div>
        </div>

        <!-- Statistics Section (Mirrored from Admin Grid Format) -->
        <h4 class="fw-bold mb-4" style="color: #e0aaff;">Overview Analytics</h4>
        <div class="row g-4 justify-content-center mb-5">
            <div class="col-md-6 col-lg-4">
                <div class="dashboard-card">
                    <div class="card-icon" style="color: #ffc107; border-color: rgba(255, 193, 7, 0.3); background: rgba(255, 193, 7, 0.1);"><i class="bi bi-hourglass-split"></i></div>
                    <h5>Pending Requests</h5>
                    <h2 class="fw-bold text-white mb-0 display-4"><?= $pending_count ?></h2>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="dashboard-card">
                    <div class="card-icon" style="color: #0dcaf0; border-color: rgba(13, 202, 240, 0.3); background: rgba(13, 202, 240, 0.1);"><i class="bi bi-activity"></i></div>
                    <h5>In-Progress Cases</h5>
                    <h2 class="fw-bold text-white mb-0 display-4"><?= $active_count ?></h2>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="dashboard-card">
                    <div class="card-icon" style="color: #198754; border-color: rgba(25, 135, 84, 0.3); background: rgba(25, 135, 84, 0.1);"><i class="bi bi-check2-circle"></i></div>
                    <h5>Resolved Incidents</h5>
                    <h2 class="fw-bold text-white mb-0 display-4"><?= $resolved_count ?></h2>
                </div>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <h4 class="fw-bold mb-4" style="color: #e0aaff;">Provider Operations</h4>
        <div class="row g-4 justify-content-center mb-5">
            <div class="col-md-6 col-lg-4">
                <div class="dashboard-card">
                    <div class="card-icon"><i class="bi bi-clipboard-pulse"></i></div>
                    <h5>Active Case Triage</h5>
                    <p>Review incoming crisis alerts, monitor AI triage suggestions, and update victim statuses.</p>
                    <a href="triage.php" class="btn-purple">Enter Triage</a>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="dashboard-card">
                    <div class="card-icon"><i class="bi bi-shield-lock-fill"></i></div>
                    <h5>Evidence Vault Handshake</h5>
                    <p>Securely access survivor-uploaded media using One-Time Authorization PINs for review.</p>
                    <a href="evidence_handshake.php" class="btn-purple">Open Vault Tool</a>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="dashboard-card">
                    <div class="card-icon"><i class="bi bi-chat-quote-fill"></i></div>
                    <h5>Community Support Groups</h5>
                    <p>Enter survivor community groups to offer professional advice carrying your agency badge.</p>
                    <a href="../user/chat.php" class="btn-purple" >Join Discussions</a>
                </div>
            </div>
        </div>

        <!-- Inbox Section directly mimicking User Profile forms -->
        <div class="profile-card">
            <div class="profile-card-header">
                <h4 class="mb-0"><i class="bi bi-envelope-check-fill me-2"></i>Inbox / Shared Actions</h4>
            </div>
            <div class="card-body p-4">
                <?php if(empty($inbox_messages)): ?>
                    <p class="text-muted text-center mb-0 my-4 py-4">No messages or shared PINs in your inbox.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="inbox-table">
                            <thead>
                                <tr>
                                    <th style="padding-left: 20px;">From Survivor</th>
                                    <th>Direct Message Component</th>
                                    <th class="text-end" style="padding-right: 20px;">Received Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($inbox_messages as $msg): ?>
                                    <tr>
                                        <td style="padding-left: 20px;"><strong><?= htmlspecialchars($msg['victim_name']) ?></strong></td>
                                        <td><span style="color: #e0aaff;"><i class="bi bi-chat-left-dots-fill me-2" style="color: #bf40ff;"></i><?= htmlspecialchars($msg['message']) ?></span></td>
                                        <td class="text-end" style="padding-right: 20px;">
                                            <span style="color: #b89fd4; font-size: 0.85em;"><i class="bi bi-clock me-1"></i><?= date('M j, Y g:i A', strtotime($msg['created_at'])) ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="text-center mt-5 mb-4">
            <p class="text-muted small opacity-75" style="color: #cbd5e1;">&copy; <?= date('Y'); ?> GBVAid Professional Portal.</p>
        </div>
    </div>

<footer class="text-center py-4">
    <p class="small text-muted opacity-50 mb-0">GBVAid Professional Portal &copy; <?= date('Y') ?></p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>