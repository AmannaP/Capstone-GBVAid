<?php
require_once '../settings/core.php';
require_once '../controllers/victim_controller.php';
require_once '../controllers/evidence_controller.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 3 || !isset($_SESSION['unlocked_vault'])) {
    header("Location: evidence_handshake.php");
    exit();
}

$victim_id = $_SESSION['unlocked_vault'];
$victim = get_victim_ctr($victim_id);
if (!$victim) {
    echo "Victim not found.";
    exit();
}

$evidences = get_victim_evidence_ctr($victim_id) ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Unlocked Vault | <?= htmlspecialchars($victim['victim_name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0f0a1e;
            color: #ffffff;
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            background-image: radial-gradient(#3c2a61 1px, transparent 1px);
            background-size: 30px 30px;
            background-attachment: fixed;
        }
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
        .evidence-table { color: #fff; width: 100%; border-collapse: separate; border-spacing: 0 10px; }
        .evidence-table th { color: #e0aaff; font-weight: 500; padding: 10px; border-bottom: 1px solid #3c2a61; }
        .evidence-table td { background: #1a1033; padding: 15px; vertical-align: middle; border-top: 1px solid #3c2a61; border-bottom: 1px solid #3c2a61; }
        .evidence-table tr td:first-child { border-left: 1px solid #3c2a61; border-top-left-radius: 10px; border-bottom-left-radius: 10px; }
        .evidence-table tr td:last-child { border-right: 1px solid #3c2a61; border-top-right-radius: 10px; border-bottom-right-radius: 10px; }
        .evidence-icon { font-size: 1.5rem; color: #bf40ff; }
        .btn-view-custom {
            background-color: rgba(191, 64, 255, 0.2);
            color: #e0aaff;
            border: 1px solid #bf40ff;
            padding: 8px 20px;
            border-radius: 50px;
            transition: 0.3s;
            text-decoration: none;
            font-size: 0.9em;
            font-weight: 600;
        }
        .btn-view-custom:hover { background-color: #bf40ff; color: #fff; }
        .card-custom {
            background: #1a1033;
            border: 1px solid #3c2a61;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(191, 64, 255, 0.1);
        }
        .text-muted { color: #b89fd4 !important; }
    </style>
</head>
<body>

<?php include '../views/sp_navbar.php'; ?>

<div class="container dashboard-header d-flex justify-content-between align-items-center">
    <div>
        <h2 class="welcome-text display-6 mb-1"><i class="bi bi-shield-lock-fill me-2" style="color:#bf40ff;"></i><?= htmlspecialchars($victim['victim_name']) ?>'s Vault</h2>
        <p class="opacity-75 mb-0 d-flex align-items-center text-muted-custom">
            <i class="bi bi-circle-fill text-success me-2" style="font-size: 10px;"></i>
            Connection Secured (PIN Validated)
        </p>
    </div>
    <div>
        <a href="../actions/lock_vault_action.php" class="btn btn-outline-danger rounded-pill px-4">
            <i class="bi bi-lock-fill me-2"></i>Lock Vault
        </a>
    </div>
</div>

<div class="container mb-5">
    <div class="card card-custom p-4 mb-4">
        <h4 class="fw-bold mb-3" style="color:#e0aaff;"><i class="fa fa-info-circle me-2"></i>Survivor Information</h4>
        <div class="row">
            <div class="col-md-4 mb-2">
                <small class="text-muted text-uppercase fw-bold">Contact Number</small>
                <div class="fs-5"><?= htmlspecialchars($victim['victim_contact']) ?></div>
            </div>
            <div class="col-md-4 mb-2">
                <small class="text-muted text-uppercase fw-bold">Location</small>
                <div class="fs-5"><?= htmlspecialchars($victim['victim_city'] . ', ' . $victim['victim_country']) ?></div>
            </div>
            <div class="col-md-4 mb-2">
                <small class="text-muted text-uppercase fw-bold">Registered Date</small>
                <div class="fs-5"><?= date('F j, Y', strtotime($victim['victim_created_at'] ?? 'now')) ?></div>
            </div>
        </div>
    </div>

    <h4 class="fw-bold mt-5 mb-3" style="color:#e0aaff;"><i class="fa fa-archive me-2"></i>Decrypted Evidence</h4>
    
    <div class="table-responsive">
        <table class="evidence-table">
            <thead>
                <tr>
                    <th style="width: 50px;">Type</th>
                    <th>Details</th>
                    <th>Date Uploaded</th>
                    <th class="text-end pe-4">Access</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($evidences)): ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-5 border-start border-end">No evidence files found in this vault.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($evidences as $ev): ?>
                        <?php 
                            $icon = "fa-file-alt";
                            $ext = strtolower($ev['file_type']);
                            if(in_array($ext, ['jpg','jpeg','png'])) $icon = "fa-image";
                            if(in_array($ext, ['mp4'])) $icon = "fa-video";
                            if(in_array($ext, ['mp3','wav','m4a'])) $icon = "fa-microphone";
                            if(in_array($ext, ['pdf'])) $icon = "fa-file-pdf";
                        ?>
                        <tr>
                            <td class="text-center"><i class="fa <?= $icon ?> evidence-icon"></i></td>
                            <td>
                                <strong class="fs-5"><?= htmlspecialchars($ev['title']) ?></strong><br>
                                <small class="text-muted"><?= htmlspecialchars($ev['description']) ?></small>
                            </td>
                            <td>
                                <?= date('M j, Y - g:i A', strtotime($ev['uploaded_at'])) ?>
                            </td>
                            <td class="text-end pe-4 text-nowrap">
                                <?php if($ev['file_type'] === 'raw_text'): ?>
                                    <button class="btn btn-view-custom shadow-sm" onclick="viewTextNote(<?= htmlspecialchars(json_encode($ev['raw_text_content'])) ?>)">
                                        <i class="fa fa-eye me-1"></i> Read Note
                                    </button>
                                <?php else: ?>
                                    <a href="../uploads/evidence/<?= htmlspecialchars($ev['file_path']) ?>" target="_blank" class="btn btn-view-custom shadow-sm">
                                        <i class="fa fa-download me-1"></i> Open File
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function viewTextNote(content) {
        Swal.fire({
            title: '<i class="fa fa-lock" style="color:#0dcaf0"></i> Secure Note',
            text: content,
            width: 600,
            padding: '2em',
            background: '#1a1033',
            color: '#fff',
            confirmButtonColor: '#bf40ff'
        });
    }
</script>
</body>
</html>
