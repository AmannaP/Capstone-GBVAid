<?php
require_once '../settings/core.php';
require_once '../controllers/awareness_controller.php';

requireAdmin();

// Handle Form Submit (Add)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_content'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    
    if(add_awareness_ctr($title, $content)) {
        header("Location: awareness.php?msg=added");
    } else {
        header("Location: awareness.php?msg=error");
    }
    exit();
}

// Handle Delete (Logic moved to SweetAlert-friendly GET)
if (isset($_GET['delete'])) {
    if(delete_awareness_ctr($_GET['delete'])) {
        header("Location: awareness.php?msg=deleted");
    }
    exit();
}

// Fetch Data
$contents = get_all_awareness_ctr();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Awareness Content | GBVAid Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <style>
        body {
            background-color: #0f0a1e;
            font-family: 'Poppins', sans-serif;
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
            box-shadow: 0 0 15px rgba(191, 64, 255, 0.3);
        }

        .list-group-item {
            background: transparent;
            border-bottom: 1px solid rgba(191, 64, 255, 0.2);
            color: white;
            padding: 25px 0;
        }

        .btn-purple { background: #9d4edd; color: #ffffff; border: none; border-radius: 50px; font-weight: 700; transition: 0.3s; }
        .btn-purple:hover { background: #bf40ff; transform: translateY(-2px); }

        .modal-content { background: #1a1033; border: 1px solid #bf40ff; color: #ffffff; border-radius: 20px; }
        .btn-close { filter: invert(1); }
    </style>
</head>
<body>

<nav class="navbar navbar-admin">
    <div class="container">
        <a class="navbar-brand text-white fw-bold" href="dashboard.php">GBVAid Admin</a>
        <a href="dashboard.php" class="btn btn-sm btn-outline-light rounded-pill px-4">Back to Dashboard</a>
    </div>
</nav>

<div class="container mt-5">
    <div class="row g-5">
        <div class="col-md-4">
            <div class="content-card">
                <h4 class="welcome-text mb-4">Add New Content</h4>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Signs of Abuse" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content / Tips</label>
                        <textarea name="content" class="form-control" rows="8" placeholder="Enter educational text here..." required></textarea>
                    </div>
                    <button type="submit" name="add_content" class="btn btn-purple w-100 py-3 mt-2">Post Content</button>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="content-card">
                <h4 class="welcome-text mb-4">Existing Resources</h4>
                
                <?php if(empty($contents)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-journal-text text-muted fs-1 mb-3"></i>
                        <p class="text-muted">No awareness content added yet.</p>
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach($contents as $c): ?>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between align-items-center mb-2">
                                <h5 class="mb-0 fw-bold text-white"><?= htmlspecialchars($c['title']) ?></h5>
                                <small class="opacity-50"><?= date('M d, Y', strtotime($c['created_at'])) ?></small>
                            </div>
                            <p class="mb-4 opacity-75"><?= nl2br(htmlspecialchars($c['content'])) ?></p>
                            
                            <div class="d-flex justify-content-end gap-2">
                                <button class="btn btn-sm btn-outline-info rounded-pill px-3" 
                                        onclick='openEditModal(<?= json_encode($c, JSON_HEX_APOS) ?>)'>
                                    <i class="bi bi-pencil me-1"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-outline-danger rounded-pill px-3" 
                                        onclick="confirmDelete(<?= $c['awareness_id'] ?>)">
                                    <i class="bi bi-trash me-1"></i> Delete
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editAwarenessModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="../actions/edit_awareness_action.php" method="POST">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Update Resource</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="awareness_id" id="edit_id">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" id="edit_title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <textarea name="content" id="edit_content" class="form-control" rows="10" required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-link text-white text-decoration-none" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-purple px-5">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Handle URL success/error messages
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('msg')) {
        const msg = urlParams.get('msg');
        let icon = msg === 'error' ? 'error' : 'success';
        let title = msg === 'added' ? 'Content Posted!' : 
                    msg === 'updated' ? 'Resource Updated!' : 
                    msg === 'deleted' ? 'Post Removed' : 'Done!';

        Swal.fire({
            toast: true, position: 'top-end', icon: icon, title: title,
            showConfirmButton: false, timer: 3000, background: '#1a1033', color: '#ffffff'
        });
    }

    function openEditModal(data) {
        document.getElementById('edit_id').value = data.awareness_id;
        document.getElementById('edit_title').value = data.title;
        document.getElementById('edit_content').value = data.content;
        
        var myModal = new bootstrap.Modal(document.getElementById('editAwarenessModal'));
        myModal.show();
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Delete this post?',
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff4d4d',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it',
            background: '#1a1033',
            color: '#ffffff'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `awareness.php?delete=${id}`;
            }
        })
    }
</script>
</body>
</html>