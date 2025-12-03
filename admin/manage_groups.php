<?php
require_once '../settings/core.php';
require_once '../controllers/chat_controller.php';

// Restrict to Admin
requireAdmin();

// Handle Delete Request
if (isset($_GET['delete'])) {
    delete_group_ctr($_GET['delete']);
    header("Location: manage_groups.php?msg=deleted");
    exit();
}

// Fetch existing groups
$groups = get_chat_groups_ctr();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Chat Groups | GBVAid Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        
        /* Admin Navbar */
        .navbar-admin { background-color: #c453eaff; padding: 15px 0; }
        .navbar-brand { color: white !important; font-weight: 800; }
        .btn-logout { border: 2px solid white; color: white; border-radius: 50px; text-decoration: none; padding: 5px 20px; font-weight: 700; }
        .btn-logout:hover { background: white; color: #c453eaff; }

        /* Content Cards */
        .content-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            padding: 30px;
            margin-bottom: 30px;
        }

        /* Icon Selection Styling */
        .icon-option { display: none; } /* Hide default radio */
        .icon-label {
            cursor: pointer;
            width: 50px; height: 50px;
            border-radius: 10px;
            border: 2px solid #eee;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; color: #666;
            transition: all 0.2s;
        }
        .icon-option:checked + .icon-label {
            border-color: #c453eaff;
            background-color: #f3e8ff;
            color: #c453eaff;
        }
        .icon-label:hover { border-color: #c453eaff; }

        .btn-purple { background-color: #c453eaff; color: white; border: none; }
        .btn-purple:hover { background-color: #a020f0; color: white; }
    </style>
</head>
<body>

<nav class="navbar navbar-admin">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">GBVAid Admin</a>
        <a href="dashboard.php" class="btn-logout">Back to Dashboard</a>
    </div>
</nav>

<div class="container mt-5">
    <div class="row">
        
        <div class="col-md-5">
            <div class="content-card">
                <h4 class="fw-bold mb-4" style="color: #c453eaff;">Create New Group</h4>
                
                <form action="../actions/add_group_action.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Group Name</label>
                        <input type="text" name="group_name" class="form-control" placeholder="e.g. Teen Support" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="What is this group for?" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Select Icon</label>
                        <div class="d-flex gap-2 flex-wrap">
                            <input type="radio" name="icon" id="i1" value="bi-people" class="icon-option" checked>
                            <label for="i1" class="icon-label"><i class="bi bi-people"></i></label>

                            <input type="radio" name="icon" id="i2" value="bi-heart-pulse" class="icon-option">
                            <label for="i2" class="icon-label"><i class="bi bi-heart-pulse"></i></label>

                            <input type="radio" name="icon" id="i3" value="bi-shield-check" class="icon-option">
                            <label for="i3" class="icon-label"><i class="bi bi-shield-check"></i></label>

                            <input type="radio" name="icon" id="i4" value="bi-chat-dots" class="icon-option">
                            <label for="i4" class="icon-label"><i class="bi bi-chat-dots"></i></label>
                            
                            <input type="radio" name="icon" id="i5" value="bi-gender-female" class="icon-option">
                            <label for="i5" class="icon-label"><i class="bi bi-gender-female"></i></label>

                            <input type="radio" name="icon" id="i6" value="bi-incognito" class="icon-option">
                            <label for="i6" class="icon-label"><i class="bi bi-incognito"></i></label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-purple w-100 py-2 fw-bold">Create Group</button>
                </form>
            </div>
        </div>

        <div class="col-md-7">
            <div class="content-card">
                <h4 class="fw-bold mb-4" style="color: #333;">Active Groups</h4>
                
                <?php if (empty($groups)): ?>
                    <div class="text-center text-muted py-4">No groups created yet.</div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($groups as $g): ?>
                        <div class="list-group-item p-3 border-bottom d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div style="width: 45px; height: 45px; background: #f3e8ff; color: #c453eaff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; font-size: 1.2rem;">
                                    <i class="bi <?= htmlspecialchars($g['icon']) ?>"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark"><?= htmlspecialchars($g['group_name']) ?></h6>
                                    <small class="text-muted"><?= htmlspecialchars($g['description']) ?></small>
                                </div>
                            </div>
                            
                            <a href="?delete=<?= $g['group_id'] ?>" 
                               class="btn btn-sm btn-outline-danger rounded-pill"
                               onclick="return confirm('Are you sure? This will delete all messages in this group.')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

</body>
</html>