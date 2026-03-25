<?php
require_once '../settings/core.php';
require_once '../controllers/chat_controller.php';

if (!checkLogin()) header("Location: ../login/login.php");

$groups = get_chat_groups_ctr();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Support Groups | GBVAid</title>
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

        .page-title {
            background: linear-gradient(to bottom, #ffffff 20%, #e0aaff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
        }

        /* Glassmorphism Group Cards */
        .group-card {
            background: rgba(26, 16, 51, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid #3c2a61;
            border-radius: 20px;
            transition: all 0.3s ease-in-out;
            height: 100%;
        }

        .group-card:hover {
            transform: translateY(-8px);
            border-color: #bf40ff;
            box-shadow: 0 12px 25px rgba(191, 64, 255, 0.2);
            background: rgba(36, 20, 69, 0.95);
        }

        .icon-box {
            width: 70px; height: 70px;
            background: rgba(196, 83, 234, 0.1);
            color: #d980ff;
            border: 1px solid rgba(191, 64, 255, 0.3);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem; margin-bottom: 20px;
        }

        /* Neon Purple Button */
        .btn-join {
            background-color: #9d4edd;
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 600;
            color: white;
            transition: 0.3s;
            box-shadow: 0 4px 15px rgba(157, 78, 221, 0.3);
        }

        .btn-join:hover {
            background-color: #bf40ff;
            color: white;
            box-shadow: 0 6px 20px rgba(191, 64, 255, 0.5);
        }

        /* Suggestion Button Styling */
        .btn-suggest {
            border: 2px solid #9d4edd;
            color: #e0aaff;
            background: transparent;
            transition: 0.3s;
        }

        .btn-suggest:hover {
            background: #9d4edd;
            color: white;
        }

        /* Modal Customization */
        .modal-content {
            background: #1a1033;
            border: 1px solid #3c2a61;
            border-radius: 25px;
            color: white;
        }
        .form-control {
            background: rgba(255,255,255,0.05);
            border: 1px solid #3c2a61;
            color: white;
        }
        .form-control:focus {
            background: rgba(255,255,255,0.1);
            color: white;
            border-color: #bf40ff;
            box-shadow: none;
        }
        /* Custom Placeholder Styling */
        .form-control::placeholder {
            color: #a49db5 !important; 
            opacity: 1; 
        }

        /* For Internet Explorer 10-11 */
        .form-control:-ms-input-placeholder {
            color: #a49db5 !important;
        }

        /* For Microsoft Edge */
        .form-control::-ms-input-placeholder {
            color: #a49db5 !important;
        }
    </style>
</head>
<body>

<?php 
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 2) {
        echo '<nav class="navbar navbar-expand-lg" style="background-color: rgba(196, 83, 234, 0.2); backdrop-filter: blur(10px); padding: 15px 0; border-bottom: 1px solid rgba(191, 64, 255, 0.3);"><div class="container"><a class="navbar-brand fw-bold" href="../admin/dashboard.php" style="color: #e0aaff;"><i class="bi bi-shield-lock-fill me-2"></i>GBVAid Admin</a><a href="../admin/dashboard.php" class="btn btn-sm btn-outline-light rounded-pill ms-auto">Back to Dashboard</a></div></nav>';
    } elseif ($_SESSION['role'] == 3) {
        if (file_exists('../includes/sp_navbar.php')) {
            include '../includes/sp_navbar.php';
        } else {
            include '../views/sp_navbar.php';
        }
    } else {
        include '../views/navbar.php';
    }
} else {
    include '../views/navbar.php';
}
?>
<div class="container my-5">
    <div class="text-center mb-5">
        <h2 class="page-title display-5">Community Support Groups</h2>
        <p class="text-muted" style="color: #cbd5e1 !important;">Join a safe space to share, listen, and heal together.</p>
    </div>

    <div class="row g-4">
        <?php foreach ($groups as $group): ?>
        <div class="col-md-4 col-sm-6">
            <div class="card group-card p-4 text-center">
                <div class="d-flex justify-content-center">
                    <div class="icon-box">
                        <i class="bi <?= htmlspecialchars($group['icon'] ?? 'bi-people') ?>"></i>
                    </div>
                </div>
                <h5 class="fw-bold" style="color: #e0aaff;"><?= htmlspecialchars(htmlspecialchars_decode($group['group_name'], ENT_QUOTES)) ?></h5>
                <p class="small mb-4" style="color: #cbd5e1;"><?= htmlspecialchars(htmlspecialchars_decode($group['description'], ENT_QUOTES)) ?></p>
                <a href="chat_room.php?id=<?= $group['group_id'] ?>" class="btn btn-join w-100">
                    Join Discussion
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="text-center mt-5">
        <button class="btn btn-suggest rounded-pill px-5 py-2" data-bs-toggle="modal" data-bs-target="#suggestGroupModal">
            <i class="bi bi-plus-circle me-2"></i>Suggest a New Group
        </button>
    </div>
</div>

<div class="modal fade" id="suggestGroupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0">
                <h5 class="fw-bold" style="color: #e0aaff;">Suggest a Support Group</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="suggestGroupForm">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Group Name</label>
                        <input type="text" name="suggested_name" class="form-control rounded-pill" placeholder="e.g. Survivor's Hope" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Reason/Description</label>
                        <textarea name="reason_description" class="form-control" rows="4" style="border-radius: 15px;" placeholder="Why is this group needed?" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-join w-100 mt-3">
                        Submit Suggestion
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/suggest_group.js"></script>

</body>
</html>