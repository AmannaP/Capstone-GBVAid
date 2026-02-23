<?php
require_once '../settings/core.php';
require_once '../controllers/chat_controller.php';

// Restrict to Admin
requireAdmin();

// Fetch existing groups
$groups = get_chat_groups_ctr();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Chat Groups | GBVAid Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <style>
        body {
            background-color: #0f0a1e;
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            color: #ffffff;
            background-image: radial-gradient(#3c2a61 1px, transparent 1px);
            background-size: 30px 30px;
            background-attachment: fixed;
        }

        .navbar-admin {
            background-color: rgba(196, 83, 234, 0.15);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(191, 64, 255, 0.3);
            padding: 15px 0;
        }

        .content-card {
            border: 1px solid rgba(191, 64, 255, 0.3);
            border-radius: 20px;
            background: rgba(26, 16, 51, 0.95);
            backdrop-filter: blur(8px);
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);
        }

        .welcome-text {
            color: #ffffff;
            font-weight: 800;
            text-shadow: 0 0 10px rgba(191, 64, 255, 0.5);
        }

        /* Visibility Fixes */
        .form-label { color: #e0aaff !important; font-weight: 600; }
        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid #4a307d;
            color: #ffffff !important;
        }
        .form-control:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: #bf40ff;
            color: #ffffff;
        }

        /* Icons */
        .icon-option { display: none; }
        .icon-label {
            cursor: pointer;
            width: 50px; height: 50px;
            border-radius: 12px;
            border: 1px solid #4a307d;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem; color: #ffffff;
            background: rgba(255, 255, 255, 0.05);
        }
        .icon-option:checked + .icon-label {
            border-color: #ffffff;
            background-color: #9d4edd;
        }

        .list-group-item {
            background: transparent;
            border-bottom: 1px solid rgba(191, 64, 255, 0.2);
            color: white;
            padding: 20px 10px;
        }

        .btn-purple { background: #9d4edd; color: #ffffff; border: none; border-radius: 50px; font-weight: 700; }
        .btn-purple:hover { background: #bf40ff; color: #ffffff; }

        /* Modal Visibility */
        .modal-content { background: #1a1033; border: 1px solid #bf40ff; color: #ffffff; border-radius: 20px; }
        .btn-close { filter: invert(1); }
    </style>
</head>
<body>

<nav class="navbar navbar-admin">
    <div class="container">
        <a class="navbar-brand text-white fw-bold" href="dashboard.php">GBVAid Admin</a>
        <a href="dashboard.php" class="btn btn-sm btn-outline-light rounded-pill">Back to Dashboard</a>
    </div>
</nav>

<div class="container mt-5">
    <div class="row g-5">
        <div class="col-md-5">
            <div class="content-card">
                <h4 class="welcome-text mb-4">Create New Group</h4>
                <form action="../actions/add_group_action.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Group Name</label>
                        <input type="text" name="group_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label d-block">Icon</label>
                        <div class="d-flex gap-2 flex-wrap">
                            <?php 
                            $icons = ['bi-people', 'bi-heart-pulse', 'bi-shield-check', 'bi-chat-dots', 'bi-gender-female', 'bi-incognito'];
                            foreach($icons as $k => $icon): ?>
                                <input type="radio" name="icon" id="i<?= $k ?>" value="<?= $icon ?>" class="icon-option" <?= $k==0?'checked':'' ?>>
                                <label for="i<?= $k ?>" class="icon-label"><i class="bi <?= $icon ?>"></i></label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-purple w-100 py-3">Create Group</button>
                </form>
            </div>
        </div>

        <div class="col-md-7">
            <div class="content-card">
                <h4 class="welcome-text mb-4">Active Groups</h4>
                <div class="list-group list-group-flush">
                    <?php if (empty($groups)): ?>
                        <p class="text-muted">No groups found.</p>
                    <?php else: ?>
                        <?php foreach ($groups as $g): ?>
                        <div class="list-group-item d-flex align-items-center justify-content-between border-0">
                            <div class="d-flex align-items-center">
                                <div class="me-3 p-3 rounded bg-purple" style="background: rgba(157, 78, 221, 0.2); border: 1px solid #9d4edd;">
                                    <i class="bi <?= $g['icon'] ?> fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold"><?= htmlspecialchars($g['group_name']) ?></h6>
                                    <small class="opacity-75"><?= htmlspecialchars($g['description']) ?></small>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-info rounded-pill" 
                                        onclick='openEditModal(<?= json_encode($g, JSON_HEX_APOS) ?>)'>
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger rounded-pill" 
                                        onclick="confirmDelete(<?= $g['group_id'] ?>)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editGroupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="../actions/edit_group_action.php" method="POST">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Edit Group</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="group_id" id="edit_id">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="group_name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="edit_desc" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Icon Class</label>
                        <input type="text" name="icon" id="edit_icon" class="form-control">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-purple px-5">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // The Edit Button functionality by correctly parsing the JSON
    function openEditModal(group) {
        document.getElementById('edit_id').value = group.group_id;
        document.getElementById('edit_name').value = group.group_name;
        document.getElementById('edit_desc').value = group.description;
        document.getElementById('edit_icon').value = group.icon;
        
        var myModal = new bootstrap.Modal(document.getElementById('editGroupModal'));
        myModal.show();
    }

    // The Delete Button now uses a beautiful themed popup
    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "All messages in this group will be lost forever!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff4d4d',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            background: '#1a1033',
            color: '#ffffff'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `manage_groups.php?delete=${id}`;
            }
        })
    }

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('msg')) {
        const msg = urlParams.get('msg');
        let title = "";
        let icon = "success";

        if (msg === 'updated') title = "Group updated successfully!";
        if (msg === 'deleted') title = "Group deleted!";
        if (msg === 'error') { title = "Something went wrong"; icon = "error"; }

        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: icon,
            title: title,
            showConfirmButton: false,
            timer: 3000,
            background: '#1a1033',
            color: '#ffffff'
        });
    }
</script>
</body>
</html>