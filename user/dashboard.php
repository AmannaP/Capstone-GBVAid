<?php
// user/dashboard.php
require_once '../settings/core.php';

// Ensure user is logged in
// if (!checkLogin()) {
//     header("Location: ../login/login.php");
//     exit();
// }
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
            background-color: #0f0a1e; /* Consistent deep purple base */
            font-family: 'Poppins', sans-serif;
            color: #ffffff;
            /* Star-like background dots */
            background-image: radial-gradient(#3c2a61 1px, transparent 1px);
            background-size: 30px 30px;
            background-attachment: fixed;
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, rgba(76, 29, 149, 0.8) 0%, rgba(30, 27, 75, 0.6) 100%);
            backdrop-filter: blur(10px);
            color: white;
            padding: 60px 0;
            margin-bottom: 40px;
            border-radius: 0 0 40px 40px;
            border-bottom: 1px solid #bf40ff;
            box-shadow: 0 10px 30px rgba(191, 64, 255, 0.2);
        }

        .dashboard-header h2 {
            background: linear-gradient(to bottom, #ffffff 20%, #e0aaff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s;
            border: 1px solid #3c2a61;
            border-radius: 20px;
            background: rgba(26, 16, 51, 0.9);
            height: 100%;
            backdrop-filter: blur(5px);
        }
        
        .card-hover:hover {
            transform: translateY(-8px);
            border-color: #bf40ff;
            box-shadow: 0 12px 25px rgba(191, 64, 255, 0.2);
            background: rgba(36, 20, 69, 0.95);
        }

        .icon-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 20px;
            /* Using neon glows instead of solid light colors */
            background: rgba(191, 64, 255, 0.1);
            border: 1px solid rgba(191, 64, 255, 0.3);
            color: #d980ff;
        }

        .card-title {
            color: #e0aaff;
            letter-spacing: 0.5px;
        }

        .text-muted-custom {
            color: #cbd5e1 !important;
        }

        .btn-action {
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 600;
            width: 100%;
            background-color: #9d4edd;
            border: none;
            color: white;
            transition: 0.3s;
            box-shadow: 0 4px 15px rgba(157, 78, 221, 0.3);
        }

        .btn-action:hover {
            background-color: #bf40ff;
            color: white;
            box-shadow: 0 6px 20px rgba(191, 64, 255, 0.5);
            transform: scale(1.02);
        }

        footer {
            border-top: 1px solid #3c2a61;
            padding: 40px 0;
            background: rgba(10, 7, 20, 0.8);
        }
    </style>
</head>
<body>

<?php 
// Mocking the name for the welcome header if the session isn't set yet
$display_name = isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : "Friend";

if (file_exists('../includes/navbar.php')) {
    include '../includes/navbar.php';
} elseif (file_exists('../views/navbar.php')) {
    include '../views/navbar.php';
}
?>

<div class="dashboard-header text-center">
    <div class="container animate__animated animate__fadeIn">
        <h2 class="fw-bold display-5">Welcome, <?= $display_name; ?></h2>
        <p class="fs-5 opacity-75 mt-2">You are in a safe space. How can we support you today?</p>
    </div>
</div>

<div class="container mb-5">
    <div class="row g-4 justify-content-center">
        <div class="emergency-zone text-center animate__animated animate__pulse">
        <h4 class="text-danger fw-bold mb-3"><i class="bi bi-geo-alt-fill"></i> Active Incident Response</h4>
        <p class="small text-light opacity-75 mb-4">Clicking the button below notifies the nearest police station with your live location and audio stream.</p>
        <button class="emergency-btn"><i class="bi bi-broadcast"></i> TRIGGER SOS SIGNAL</button>
    </div>

        <div class="col-md-4">
            <div class="card card-hover p-4">
                <div class="text-center">
                    <div class="icon-circle mx-auto">
                        <i class="bi bi-megaphone-fill"></i>
                    </div>
                    <h5 class="fw-bold card-title">Report an Incident</h5>
                    <p class="text-muted-custom small mb-4">If you or someone you know has experienced violence, report it securely and confidentially.</p>
                    <a href="#" class="btn btn-action">Service Coming Soon...</a>
                    <!-- <a href="report_incident.php" class="btn btn-action">Report Now</a> -->
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-hover p-4">
                <div class="text-center">
                    <div class="icon-circle mx-auto">
                        <i class="bi bi-chat-heart-fill"></i>
                    </div>
                    <h5 class="fw-bold card-title">Community Space</h5>
                    <p class="text-muted-custom small mb-4">Connect with other victims/survivors with similar cases for survival tips and empathy.</p>
                    <a href="#" class="btn btn-action">Service Coming Soon...</a>
                    <!-- <a href="chat.php" class="btn btn-action">Start Chat</a> -->
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-hover p-4">
                <div class="text-center">
                    <div class="icon-circle mx-auto">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h5 class="fw-bold card-title">Safety Resources</h5>
                    <p class="text-muted-custom small mb-4">Access tailored safety plans, legal rights information, and emergency contacts.</p>
                    <a href="#" class="btn btn-action">Service Coming Soon...</a>
                    <!-- <a href="resources.php" class="btn btn-action">View Resources</a> -->
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-hover p-4">
                <div class="text-center">
                    <div class="icon-circle mx-auto">
                        <i class="bi bi-box2-heart-fill"></i>
                    </div>
                    <h5 class="fw-bold card-title">Support & Tools</h5>
                    <p class="text-muted-custom small mb-4">Browse legal aid, medical kits, and safety devices available for your protection.</p>
                    <a href="#" class="btn btn-action">Service Coming Soon...</a>
                    <!-- <a href="product_page.php" class="btn btn-action">Browse Services</a> -->
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-hover p-4">
                <div class="text-center">
                    <div class="icon-circle mx-auto">
                        <i class="bi bi-person-lines-fill"></i>
                    </div>
                    <h5 class="fw-bold card-title">AI Safety Room</h5>
                    <p class="text-muted-custom small mb-4">Talk to an AI listener without judgment or fear.</p>
                    <a href="#" class="btn btn-action">Service Coming Soon...</a>
                    <!-- <a href="profile.php" class="btn btn-action">Manage Profile</a> -->
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-hover p-4">
                <div class="text-center">
                    <div class="icon-circle mx-auto">
                        <i class="bi bi-person-lines-fill"></i>
                    </div>
                    <h5 class="fw-bold card-title">My Profile</h5>
                    <p class="text-muted-custom small mb-4">Manage your personal details, view your history, and update privacy settings.</p>
                    <a href="#" class="btn btn-action">Service Coming Soon...</a>
                    <!-- <a href="profile.php" class="btn btn-action">Manage Profile</a> -->
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-hover p-4">
                <div class="text-center">
                    <div class="icon-circle mx-auto">
                        <i class="bi bi-headset"></i>
                    </div>
                    <h5 class="fw-bold card-title">Contact Help Desk</h5>
                    <p class="text-muted-custom small mb-4">Need technical assistance or have a specific question? Our team is here for you.</p>
                    <a href="#" class="btn btn-action">Service Coming Soon...</a>
                </div>
            </div>
        </div>

    </div>
</div>

<footer class="text-center mt-5">
    <div class="container">
        <p class="mb-1 fw-semibold">© <?= date('Y'); ?> <span style="color: #bf40ff;">GBVAid</span></p>
        <p class="small text-muted">Empowering safety and support through technology.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>