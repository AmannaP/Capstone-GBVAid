<?php
require_once '../settings/core.php';
require_once '../controllers/user_controller.php';
requireAdmin();

$pending_providers = get_pending_providers_ctr();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Service Providers | GBVAid Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #0f0a1e;
            background-image: radial-gradient(#3c2a61 1px, transparent 1px);
            background-size: 30px 30px;
            color: #ffffff;
            font-family: 'Poppins', sans-serif;
        }
        .request-table {
            background: rgba(26, 16, 51, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid #3c2a61;
            border-radius: 20px;
            overflow: hidden;
        }
        .table { color: #ffffff; margin-bottom: 0; }
        .table thead { background: rgba(157, 78, 221, 0.2); }
        .table thead th { color: #e0aaff; border-bottom: 1px solid #3c2a61; }
        /* Override Bootstrap 5 --bs-table-bg so rows stay dark */
        .table > :not(caption) > * > * {
            --bs-table-bg: transparent;
            --bs-table-border-color: #3c2a61;
            --bs-table-color: #e0aaff;
            color: #e0aaff;
            background-color: transparent;
        }
        .table-hover > tbody > tr:hover > * {
            --bs-table-bg: rgba(191, 64, 255, 0.07);
            background-color: rgba(191, 64, 255, 0.07);
            color: #f0d9ff;
        }
        /* Fix .text-muted visibility on dark background */
        .text-muted { color: #b89fd4 !important; }
        .btn-approve { background: #22c55e; color: white; border-radius: 50px; border: none; padding: 5px 15px; margin-right: 5px; }
        .btn-reject { background: #ef4444; color: white; border-radius: 50px; border: none; padding: 5px 15px; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <a href="dashboard.php" class="btn btn-outline-light mb-4 rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
        </a>

        <div class="text-center mb-5">
            <h2 class="fw-bold" style="color: #e0aaff;">Pending Service Providers</h2>
            <p class="text-muted">Review and verify new Service Provider registrations.</p>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-<?= ($_GET['msg'] == 'error' ? 'danger' : 'success') ?> alert-dismissible fade show" role="alert">
                <strong><?= ($_GET['msg'] == 'error' ? 'Error!' : 'Success!') ?></strong> 
                <?= ($_GET['msg'] == 'approved' ? 'Provider approved successfully.' : ($_GET['msg'] == 'rejected' ? 'Provider request rejected.' : 'An error occurred.')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="request-table shadow-lg">
            <table class="table align-middle">
                <thead>
                    <tr class="text-purple">
                        <th class="ps-4">Provider Name</th>
                        <th>Email & Contact</th>
                        <th>Category</th>
                        <th>Organization/Brand</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pending_providers)): ?>
                        <tr><td colspan="5" class="text-center py-5 text-muted">No pending providers at the moment.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($pending_providers as $sp): ?>
                    <tr>
                        <td class="ps-4 fw-bold text-light"><?= htmlspecialchars($sp['victim_name']) ?></td>
                        <td>
                            <div class="text-light"><?= htmlspecialchars($sp['victim_email']) ?></div>
                            <small class="text-muted"><?= htmlspecialchars($sp['victim_contact']) ?></small>
                        </td>
                        <td><span class="badge bg-primary border border-primary"><?= htmlspecialchars($sp['cat_name'] ?? 'Unassigned') ?></span></td>
                        <td><span class="badge bg-dark border border-secondary"><?= htmlspecialchars($sp['brand_name'] ?? 'Unassigned') ?></span></td>
                        <td class="text-end pe-4">
                            <button onclick="handleAction(<?= $sp['victim_id'] ?>, 'approve')" class="btn-approve"><i class="bi bi-check-circle me-1"></i>Approve</button>
                            <button onclick="handleAction(<?= $sp['victim_id'] ?>, 'reject')" class="btn-reject"><i class="bi bi-x-circle me-1"></i>Reject</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function handleAction(id, action) {
        const isApprove = action === 'approve';
        Swal.fire({
            title: isApprove ? `Approve this Provider?` : `Reject this Provider?`,
            text: isApprove ? "They will instantly gain access to the SP Portal." : "Their registration request will be denied.",
            icon: isApprove ? 'question' : 'warning',
            showCancelButton: true,
            confirmButtonColor: isApprove ? '#22c55e' : '#ef4444',
            background: '#1a1033',
            color: '#fff',
            confirmButtonText: isApprove ? 'Yes, Approve' : 'Yes, Reject'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `../actions/${action}_sp_action.php?id=${id}`;
            }
        });
    }
    </script>
</body>
</html>
