<?php
require_once '../settings/core.php';
require_once '../controllers/appointment_controller.php';
require_once '../settings/db_class.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 3 || $_SESSION['sp_approved'] == 0) {
    header("Location: ../login/login.php");
    exit();
}

$sp_id = $_SESSION['id'];
$db = new db_conn();
$sp_info = $db->db_fetch_one("SELECT provider_category_id FROM victim WHERE victim_id = $sp_id");
$cat_id = $sp_info['provider_category_id'];

$bookings = get_bookings_by_category_ctr($cat_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Case Triage | SP Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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
            text-align: center;
        }
        .welcome-text {
            background: linear-gradient(to bottom, #ffffff 20%, #e0aaff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
        }
        .case-card {
            background: #1a1033;
            border: 1px solid #bf40ff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(191, 64, 255, 0.2);
            margin-bottom: 20px;
            transition: 0.3s ease-in-out;
        }
        .case-card:hover { 
            border-color: #bf40ff; 
            transform: translateY(-5px); 
            box-shadow: 0 12px 25px rgba(191, 64, 255, 0.4); 
            background: rgba(36, 20, 69, 0.95);
        }
        .status-badge { font-weight: 600; padding: 5px 12px; border-radius: 50px; }
        .select-status { background-color: #1a1033; color: white; border: 1px solid #bf40ff; border-radius: 5px; padding: 5px; }
        .text-muted { color: #b89fd4 !important; }
        .btn-purple {
            background-color: #9d4edd;
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 600;
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
    </style>
</head>
<body>

<?php include '../views/sp_navbar.php'; ?>

<div class="container dashboard-header">
    <h2 class="welcome-text display-5"><i class="bi bi-inboxes me-2"></i> Case Triage</h2>
    <p class="text-muted-custom fs-5 mx-auto" style="max-width: 600px; color: #cbd5e1;">Review new bookings, update case statuses, and connect with survivors.</p>
</div>

<div class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Active Cases</h4>
        <a href="dashboard.php" class="btn btn-outline-light rounded-pill px-4"><i class="bi bi-arrow-left me-2"></i>Back to Dashboard</a>
    </div>

    <?php if (empty($bookings)): ?>
        <div class="text-center py-5">
            <i class="bi bi-folder-x display-1 text-muted"></i>
            <h4 class="mt-3 text-muted">No cases assigned to your division yet.</h4>
        </div>
    <?php endif; ?>

    <div class="row">
        <?php foreach ($bookings as $b): ?>
        <div class="col-12">
            <div class="case-card p-4">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <small class="text-muted text-uppercase fw-bold">Survivor / Client</small>
                        <h5 class="fw-bold text-light mb-0"><?= htmlspecialchars($b['victim_name']) ?></h5>
                        <small class="text-warning"><i class="bi bi-shield-check me-1"></i>Identity Protected</small>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted text-uppercase fw-bold">Service Requested</small>
                        <div class="text-light"><?= htmlspecialchars($b['service_title']) ?></div>
                        <small class="text-opacity-50"><?= date('F j, Y', strtotime($b['appointment_date'])) ?> at <?= date('g:i A', strtotime($b['appointment_time'])) ?></small>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted text-uppercase fw-bold">Current Status</small><br>
                        <?php 
                            $bg = 'bg-secondary';
                            if ($b['status'] == 'Pending') $bg = 'bg-warning text-dark';
                            if ($b['status'] == 'Assigned') $bg = 'bg-info text-dark';
                            if ($b['status'] == 'Investigating') $bg = 'bg-primary';
                            if ($b['status'] == 'Resolved' || $b['status'] == 'Completed') $bg = 'bg-success';
                        ?>
                        <span class="badge <?= $bg ?> status-badge mt-1"><?= htmlspecialchars($b['status']) ?></span>
                    </div>
                    <div class="col-md-3 text-end">
                        <!-- Action Dropdown -->
                        <div class="btn-group w-100 mb-2">
                            <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Update Status
                            </button>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <li><a class="dropdown-item" href="#" onclick="updateStatus(<?= $b['appointment_id'] ?>, 'Assigned')">Accept (Assign)</a></li>
                                <li><a class="dropdown-item" href="#" onclick="updateStatus(<?= $b['appointment_id'] ?>, 'Investigating')">Mark Investigating</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-success" href="#" onclick="updateStatus(<?= $b['appointment_id'] ?>, 'Resolved')">Mark Resolved</a></li>
                            </ul>
                        </div>
                        
                        <!-- Contact Protocol -->
                        <button class="btn btn-sm btn-info w-100 fw-bold" onclick="showContactModal('<?= htmlspecialchars($b['victim_name']) ?>', '<?= htmlspecialchars($b['victim_contact']) ?>')">
                            <i class="bi bi-telephone-outbound me-2"></i>Contact Survivor
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal for Off-Platform Dialer -->
<div class="modal fade" id="contactModal" tabindex="-1" data-bs-theme="dark">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-info" style="background-color: #1a1033; color: white;">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold text-info"><i class="bi bi-shield-lock-fill me-2"></i>Secure Contact Protocol</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center py-4">
        <p class="opacity-75">Connect with the survivor via an encrypted or off-platform dialer to maintain anonimity.</p>
        <p class="opacity-75">If you need them to share their Evidence Vault, you can call them to request the <strong>Temporary Access Token (PIN)</strong>.</p>
        
        <div class="my-4 p-3 rounded" style="background: rgba(13, 202, 240, 0.1); border: 1px dashed #0dcaf0;">
            <p class="mb-1 text-muted text-uppercase fw-bold small">Survivor Contact</p>
            <h3 class="fw-bold text-info" id="survivorPhone"></h3>
        </div>
        
        <a id="dialerBtn" href="#" class="btn btn-info btn-lg fw-bold rounded-pill px-5">
            <i class="bi bi-telephone-fill me-2"></i>Call Now
        </a>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function updateStatus(apptId, newStatus) {
        Swal.fire({
            title: `Update to ${newStatus}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#bf40ff',
            background: '#1a1033',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                // AJAX logic to update status
                fetch('../actions/update_booking_status_action.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `appointment_id=${apptId}&status=${newStatus}`
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        location.reload();
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                });
            }
        });
    }

    function showContactModal(name, phone) {
        document.getElementById('survivorPhone').textContent = phone;
        document.getElementById('dialerBtn').href = 'tel:' + phone.replace(/[^0-9]/g, '');
        new bootstrap.Modal(document.getElementById('contactModal')).show();
    }
</script>
</body>
</html>
