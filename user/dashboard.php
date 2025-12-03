<?php
// user/dashboard.php
require_once '../settings/core.php';

// Ensure user is logged in
if (!checkLogin()) {
    header("Location: ../login/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | GBVAid Support Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .dashboard-header {
            background-color: #c453eaff;
            color: white;
            padding: 40px 0;
            margin-bottom: 40px;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 4px 20px rgba(196, 83, 234, 0.2);
        }

        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 15px;
            background: white;
            height: 100%;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(196, 83, 234, 0.15);
        }

        .icon-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .btn-action {
            border-radius: 50px;
            padding: 8px 25px;
            font-weight: 600;
            width: 100%;
        }
    </style>
</head>
<body>

<?php 
if (file_exists('../includes/navbar.php')) {
    include '../includes/navbar.php';
} else {
    include '../views/navbar.php';
}
?>

<div class="dashboard-header text-center">
    <div class="container">
        <h2 class="fw-bold">Welcome, <?= htmlspecialchars($_SESSION['name']); ?></h2>
        <p class="opacity-75">You are in a safe space. How can we support you today?</p>
    </div>
</div>

<div class="container mb-5">
    <div class="row g-4 justify-content-center">

        <div class="col-md-4">
            <div class="card card-hover p-4">
                <div class="text-center">
                    <div class="icon-circle mx-auto" style="background-color: #fee2e2; color: #c453eaff;">
                        <i class="bi bi-megaphone-fill"></i>
                    </div>
                    <h5 class="fw-bold">Report an Incident</h5>
                    <p class="text-muted small mb-4">If you or someone you know has experienced violence, report it securely and confidentially.</p>
                    <a href="report_incident.php" class="btn btn-action" style="background-color: #c453eaff">Report Now</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-hover p-4">
                <div class="text-center">
                    <div class="icon-circle mx-auto" style="background-color: #d1fae5; color: #c453eaff;">
                        <i class="bi bi-chat-heart-fill"></i>
                    </div>
                    <h5 class="fw-bold">Community Dialogue Space</h5>
                    <p class="text-muted small mb-4">Connect with other victims/survivor with similar cases for survival tips.</p>
                    <a href="chat.php" class="btn btn-success btn-action" style="background-color: #c453eaff; border:none;">Start Chat</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-hover p-4">
                <div class="text-center">
                    <div class="icon-circle mx-auto" style="background-color: #e0f2fe; color: #c453eaff;">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h5 class="fw-bold">Safety Resources</h5>
                    <p class="text-muted small mb-4">Access safety plans, legal rights information, and emergency contacts.</p>
                    <a href="resources.php" class="btn btn-info btn-action text-white" style="background-color: #c453eaff; border:none;">View Resources</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-hover p-4">
                <div class="text-center">
                    <div class="icon-circle mx-auto" style="background-color: #f3e8ff; color: #c453eaff;">
                        <i class="bi bi-box2-heart-fill"></i>
                    </div>
                    <h5 class="fw-bold">Support Services & Tools</h5>
                    <p class="text-muted small mb-4">Browse legal aid, medical kits, and safety devices available for you.</p>
                    <a href="product_page.php" class="btn btn-action text-white" style="background-color: #c453eaff;">Browse Services</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-hover p-4">
                <div class="text-center">
                    <div class="icon-circle mx-auto" style="background-color: #f4f4f5; color: #c453eaff;">
                        <i class="bi bi-person-lines-fill"></i>
                    </div>
                    <h5 class="fw-bold">My Profile</h5>
                    <p class="text-muted small mb-4">Manage your personal details, view your incident history, and update settings.</p>
                    <a href="profile.php" class="btn btn-secondary btn-action"style="background-color: #c453eaff;">Manage Profile</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-hover p-4">
                <div class="text-center">
                    <div class="icon-circle mx-auto" style="background-color: #fff7ed; color: #c453eaff;">
                        <i class="bi bi-headset"></i>
                    </div>
                    <h5 class="fw-bold">Contact Help Desk</h5>
                    <p class="text-muted small mb-4">Need technical assistance or have a question? Our team is here to help.</p>
                    <a href="contact.php" class="btn btn-warning btn-action text-white" style="background-color: #c453eaff; border:none;">Contact Us</a>
                </div>
            </div>
        </div>

    </div>
</div>

<footer class="text-center text-muted mt-5 mb-3">
    <small>© <?= date('Y'); ?> GBVAid — Empowering safety and support for all.</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>