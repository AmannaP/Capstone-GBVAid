<?php
require_once '../settings/core.php';
require_once '../controllers/awareness_controller.php'; 

if (!checkLogin()) header("Location: ../login/login.php");

$resources = get_all_awareness_ctr();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Safety Resources | GBVAid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #0f0a1e;
            font-family: 'Poppins', sans-serif;
            color: #ffffff;
            background-image: radial-gradient(#3c2a61 1px, transparent 1px);
            background-size: 30px 30px;
            background-attachment: fixed;
        }
        
        .page-header {
            background: linear-gradient(to bottom, #ffffff 20%, #e0aaff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
            margin-bottom: 40px;
            text-align: center;
        }

        /* Emergency Box - Matches SOS Monitor vibe */
        .emergency-box {
            background: rgba(255, 0, 0, 0.1);
            border: 1px dashed rgba(255, 77, 77, 0.5);
            color: #ff4d4d;
            border-radius: 20px;
            padding: 25px;
            backdrop-filter: blur(5px);
            margin-bottom: 50px;
            box-shadow: 0 0 20px rgba(255, 0, 0, 0.1);
        }

        /* Resource Cards - Glassmorphism style */
        .resource-card {
            border: 1px solid #3c2a61;
            border-radius: 20px;
            background: rgba(26, 16, 51, 0.8);
            backdrop-filter: blur(10px);
            height: 100%;
            transition: all 0.3s ease;
            overflow: hidden;
        }
        .resource-card:hover {
            transform: translateY(-8px);
            border-color: #bf40ff;
            box-shadow: 0 12px 25px rgba(191, 64, 255, 0.2);
            background: rgba(36, 20, 69, 0.95);
        }

        .card-body { padding: 35px; }

        .card-title {
            color: #e0aaff;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        
        .card-title i {
            color: #d980ff;
            margin-right: 12px;
        }

        .card-text {
            color: #cbd5e1;
            line-height: 1.8;
            font-size: 0.95rem;
        }

        .posted-date {
            color: rgba(224, 170, 255, 0.6);
            font-size: 0.8rem;
            margin-top: 20px;
        }
        
        footer {
            border-top: 1px solid #3c2a61;
            padding: 30px 0;
            margin-top: 60px;
            background: rgba(10, 7, 20, 0.8);
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

<div class="container my-5">
    <h2 class="page-header display-5">Safety Resources & Guides</h2>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="emergency-box d-md-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center mb-3 mb-md-0">
                    <div style="background: rgba(255,77,77,0.2); width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 20px;">
                        <i class="bi bi-telephone-outbound-fill fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Emergency Hotlines (Ghana)</h5>
                        <small class="opacity-75">Immediate help is available 24/7.</small>
                    </div>
                </div>
                <div class="text-md-end">
                    <div class="fw-bold">DOVVSU: 055-100-0900</div>
                    <div class="fw-bold">Police: 191, 112, and 18555.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <?php if (empty($resources)): ?>
            <div class="col-12 text-center py-5">
                <i class="bi bi-journal-x text-muted opacity-25" style="font-size: 5rem;"></i>
                <h4 class="mt-3 text-muted">No resources found.</h4>
            </div>
        <?php else: ?>
            <?php foreach ($resources as $res): ?>
            <div class="col-md-6">
                <div class="card resource-card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-patch-check-fill"></i><?= htmlspecialchars($res['title']) ?>
                        </h5>
                        <div class="card-text">
                            <?= nl2br(htmlspecialchars($res['content'])) ?>
                        </div>
                        <p class="posted-date text-end">
                            <i class="bi bi-calendar3 me-1"></i> Posted <?= date('M d, Y', strtotime($res['created_at'])) ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<footer class="text-center">
    <small class="opacity-75">© <?= date('Y'); ?> GBVAid Platform — Secure Survivor Resources.</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>