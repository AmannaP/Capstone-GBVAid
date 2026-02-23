<?php
require_once '../settings/core.php';
require_once '../classes/report_class.php';

requireAdmin();

$reportObj = new Report();
$reports = $reportObj->get_all_reports();

// Handle Status Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['report_id'], $_POST['status'])) {
    $reportObj->update_status($_POST['report_id'], $_POST['status']);
    header("Location: reports.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Reports | GBVAid Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #0f0a1e;
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            color: #ffffff;
            background-image: radial-gradient(#3c2a61 1px, transparent 1px);
            background-size: 30px 30px;
            background-attachment: fixed;
        }

        /* Navbar Styling */
        .navbar-admin {
            background-color: rgba(196, 83, 234, 0.1);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(191, 64, 255, 0.3);
            padding: 15px 0;
        }

        .navbar-brand {
            font-weight: 800;
            color: #e0aaff !important;
            font-size: 1.5rem;
        }

        /* Content Card / Table Container */
        .content-card {
            border: 1px solid #3c2a61;
            border-radius: 20px;
            background: rgba(26, 16, 51, 0.9);
            backdrop-filter: blur(5px);
            padding: 30px;
            margin-top: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .welcome-text {
            background: linear-gradient(to bottom, #ffffff 20%, #e0aaff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
        }

        /* Table Aesthetics */
        .table {
            color: #ffffff;
            border-color: rgba(60, 42, 97, 0.5);
        }

        .table thead th {
            background-color: rgba(157, 78, 221, 0.1);
            color: #e0aaff;
            border-bottom: 2px solid #3c2a61;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(191, 64, 255, 0.05);
            color: #ffffff;
        }

        /* Status Select Styling */
        .form-select-custom {
            background-color: #1a1033;
            border: 1px solid #3c2a61;
            color: #e0aaff;
            border-radius: 50px;
            padding: 5px 15px;
            font-size: 0.85rem;
            transition: 0.3s;
        }

        .form-select-custom:focus {
            border-color: #bf40ff;
            box-shadow: 0 0 10px rgba(191, 64, 255, 0.3);
            background-color: #1a1033;
            color: #ffffff;
        }

        /* Custom Buttons */
        .btn-purple-outline {
            border: 1px solid #9d4edd;
            color: #e0aaff;
            border-radius: 50px;
            padding: 5px 15px;
            transition: 0.3s;
        }

        .btn-purple-outline:hover {
            background-color: #9d4edd;
            color: white;
            box-shadow: 0 0 15px rgba(157, 78, 221, 0.4);
        }

        /* Modal Customization */
        .modal-content {
            background: #1a1033;
            border: 1px solid #bf40ff;
            color: white;
            border-radius: 20px;
        }
        
        .modal-header { border-bottom: 1px solid #3c2a61; }
        .modal-footer { border-top: 1px solid #3c2a61; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-admin navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php"><i class="bi bi-shield-lock-fill me-2"></i>GBVAid Admin</a>
        <a href="dashboard.php" class="btn btn-sm btn-outline-light rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Dashboard
        </a>
    </div>
</nav>

<div class="container">
    <div class="content-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="welcome-text mb-0">Survivor Incident Reports</h3>
            <span class="badge rounded-pill" style="background: rgba(191, 64, 255, 0.2); color: #e0aaff; border: 1px solid rgba(191, 64, 255, 0.4);">
                Total: <?= count($reports) ?>
            </span>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Location</th>
                        <th>Reporter</th>
                        <th>Details</th>
                        <th>Status</th>
                        <th>Update</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($reports)): ?>
                        <tr><td colspan="7" class="text-center text-muted py-5">No incident reports available.</td></tr>
                    <?php else: ?>
                        <?php foreach ($reports as $r): ?>
                        <tr>
                            <td class="small"><?= date('M d, Y', strtotime($r['incident_date'])) ?></td>
                            <td><span class="badge" style="background: #3c2a61; color: #e0aaff;"><?= htmlspecialchars($r['incident_type']) ?></span></td>
                            <td><i class="bi bi-geo-alt me-1 text-muted"></i><?= htmlspecialchars($r['location']) ?></td>
                            <td>
                                <?php if($r['is_anonymous']): ?>
                                    <span class="text-muted italic small"><i class="bi bi-eye-slash me-1"></i>Anonymous</span>
                                <?php else: ?>
                                    <div class="fw-bold text-light"><?= htmlspecialchars($r['victim_name']) ?></div>
                                    <div class="small text-muted"><?= htmlspecialchars($r['victim_contact']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button type="button" class="btn-purple-outline btn btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#reportModal" 
                                        data-desc="<?= htmlspecialchars($r['description']) ?>"
                                        data-type="<?= htmlspecialchars($r['incident_type']) ?>"
                                        data-reporter="<?= $r['is_anonymous'] ? 'Anonymous' : htmlspecialchars($r['victim_name']) ?>">
                                    <i class="bi bi-eye me-1"></i>View
                                </button>
                            </td>
                            <td>
                                <?php 
                                    $statusColor = 'text-warning';
                                    $dotColor = 'bg-warning';
                                    if($r['status'] == 'Resolved') { $statusColor = 'text-success'; $dotColor = 'bg-success'; }
                                    if($r['status'] == 'Investigating') { $statusColor = 'text-info'; $dotColor = 'bg-info'; }
                                ?>
                                <span class="<?= $statusColor ?> d-flex align-items-center small fw-bold">
                                    <span class="dot <?= $dotColor ?> me-2" style="height: 8px; width: 8px; border-radius: 50%; display: inline-block;"></span>
                                    <?= strtoupper($r['status']) ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="report_id" value="<?= $r['report_id'] ?>">
                                    <select name="status" class="form-select-custom" onchange="this.form.submit()">
                                        <option value="" disabled selected>Update...</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Investigating">Investigate</option>
                                        <option value="Resolved">Resolve</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="reportModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title welcome-text" id="modalTitle">Incident Analysis</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <label class="text-muted small text-uppercase mb-2 d-block">Survivor Statement</label>
        <p id="modalDesc" class="p-3 rounded" style="background: rgba(255,255,255,0.05); white-space: pre-wrap; line-height: 1.6;"></p>
        <div class="row mt-4">
            <div class="col-6">
                <small class="text-muted d-block">Incident Type</small>
                <strong id="modalType" class="text-info"></strong>
            </div>
            <div class="col-6 text-end">
                <small class="text-muted d-block">Filed By</small>
                <strong id="modalReporter" class="text-light"></strong>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Dismiss</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var reportModal = document.getElementById('reportModal');
    reportModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        reportModal.querySelector('#modalDesc').textContent = button.getAttribute('data-desc');
        reportModal.querySelector('#modalType').textContent = button.getAttribute('data-type');
        reportModal.querySelector('#modalReporter').textContent = button.getAttribute('data-reporter');
    });
</script>

</body>
</html>