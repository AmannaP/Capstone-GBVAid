<?php
require_once '../settings/core.php';
require_once '../controllers/chat_controller.php';
requireAdmin();

$requests = get_pending_requests_ctr();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Group Requests | GBVAid Admin</title>
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
        .btn-approve { background: #22c55e; color: white; border-radius: 50px; border: none; padding: 5px 15px; }
        .btn-reject { background: #ef4444; color: white; border-radius: 50px; border: none; padding: 5px 15px; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <a href="dashboard.php" class="btn btn-outline-light mb-4 rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
        </a>

        <div class="text-center mb-5">
            <h2 class="fw-bold" style="color: #e0aaff;">Pending Group Suggestions</h2>
            <p class="text-muted">Review community input for new support spaces.</p>
        </div>

        <div class="request-table shadow-lg">
            <table class="table align-middle">
                <thead>
                    <tr class="text-purple">
                        <th class="ps-4">Victim Name</th>
                        <th>Suggested Name</th>
                        <th>Reason</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($requests)): ?>
                        <tr><td colspan="4" class="text-center py-5 text-muted">No pending requests at the moment.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($requests as $req): ?>
                    <tr>
                        <td class="ps-4"><?= htmlspecialchars($req['victim_name']) ?></td>
                        <td><span class="badge bg-dark border border-primary"><?= htmlspecialchars($req['suggested_name']) ?></span></td>
                        <td class="small"><?= htmlspecialchars($req['reason_description']) ?></td>
                        <td class="text-end pe-4">
                            <button onclick="handleAction(<?= $req['request_id'] ?>, 'approve')" class="btn-approve me-2">Approve</button>
                            <button onclick="handleAction(<?= $req['request_id'] ?>, 'reject')" class="btn-reject">Reject</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function handleAction(id, action) {
        Swal.fire({
            title: `Are you sure you want to ${action}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: action === 'approve' ? '#22c55e' : '#ef4444',
            background: '#1a1033',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `../actions/${action}_group_action.php?id=${id}`;
            }
        });
    }
    </script>
</body>
</html>