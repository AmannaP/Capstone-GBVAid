<?php
require_once '../settings/core.php';
if (!isset($_SESSION['id']) || $_SESSION['role'] != 3) {
    header("Location: ../login/login.php");
    exit();
}

// If they are approved, send them to dashboard
if (isset($_SESSION['sp_approved']) && $_SESSION['sp_approved'] == 1) {
    header("Location: dashboard.php");
    exit();
}

// Check if they are rejected - refresh approval status from DB to get latest
require_once '../settings/db_class.php';
$db = new db_conn();
$db->db_connect();
$stmt = $db->db->prepare("SELECT sp_approved FROM victim WHERE victim_id = ?");
$stmt->execute([$_SESSION['id']]);
$fresh = $stmt->fetch(PDO::FETCH_ASSOC);
$sp_status = $fresh ? (int)$fresh['sp_approved'] : 0;
$is_rejected = ($sp_status == 2);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $is_rejected ? 'Registration Declined' : 'Pending Approval' ?> - GBVAid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0f0a1e;
            color: #ffffff;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            background-image: radial-gradient(#3c2a61 1px, transparent 1px);
            background-size: 30px 30px;
        }
        .approval-card {
            background: #1a1033;
            border-radius: 20px;
            padding: 3rem;
            text-align: center;
            max-width: 500px;
            box-shadow: 0 10px 30px rgba(191, 64, 255, 0.2);
        }
        .approval-card.pending { border: 1px solid #bf40ff; }
        .approval-card.rejected { border: 1px solid #ef4444; box-shadow: 0 10px 30px rgba(239, 68, 68, 0.2); }
        .approval-icon {
            font-size: 5rem;
            margin-bottom: 20px;
            animation: pulse-glow 2s infinite;
        }
        .approval-icon.pending { color: #ffb703; }
        .approval-icon.rejected { color: #ef4444; }
        .btn-custom {
            background-color: #9d4edd;
            border: none;
            color: #fff;
            padding: 10px 30px;
            border-radius: 50px;
            margin-top: 20px;
            transition: all 0.3s;
        }
        .btn-custom:hover {
            background-color: #bf40ff;
            color: white;
            box-shadow: 0 4px 15px rgba(191, 64, 255, 0.4);
        }
        .btn-danger-custom {
            background-color: rgba(239,68,68,0.2);
            border: 1px solid #ef4444;
            color: #ff6b6b;
            padding: 10px 30px;
            border-radius: 50px;
            margin-top: 20px;
            transition: all 0.3s;
            text-decoration: none;
        }
        .btn-danger-custom:hover {
            background-color: #ef4444;
            color: white;
        }
        @keyframes pulse-glow {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>

    <div class="approval-card <?= $is_rejected ? 'rejected' : 'pending' ?> animate__animated animate__zoomIn">
        <?php if ($is_rejected): ?>
            <i class="fa fa-ban approval-icon rejected"></i>
            <h2 class="mb-3 text-danger">Registration Declined</h2>
            <p class="text-white mb-3">
                Unfortunately, your Service Provider registration request has been reviewed and <strong>declined</strong> by our administrative team.
            </p>
            <p class="text-white mb-4">
                This may be due to incomplete information or eligibility criteria not being met. Please contact our support team for more details or to appeal this decision.
            </p>
            <a href="mailto:support@gbvaid.org" class="btn btn-danger-custom me-2"><i class="fa fa-envelope me-1"></i> Contact Support</a>
            <a href="../login/logout.php" class="btn btn-custom"><i class="fa fa-sign-out-alt me-1"></i> Return</a>
        <?php else: ?>
            <i class="fa fa-user-clock approval-icon pending"></i>
            <h2 class="mb-3 text-white">Pending Approval</h2>
            <p class="text-white mb-4">
                Your Service Provider account has been created successfully, but it requires verification from the System Administrator before you can access the portal and receive bookings.
            </p>
            <p class="text-white mb-4">
                Please contact the administrator for more details, or check back later.
            </p>
            <a href="../login/logout.php" class="btn btn-custom"><i class="fa fa-sign-out-alt me-1"></i> Return</a>
        <?php endif; ?>
    </div>

</body>
</html>

