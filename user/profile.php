<?php
require_once '../settings/core.php';
require_once '../controllers/victim_controller.php';
require_once '../controllers/evidence_controller.php';

if (!checkLogin()) {
    header("Location: ../login/login.php");
    exit();
}

// Fetch fresh user data
$user_id = $_SESSION['id'];
$user = get_victim_ctr($user_id);
$evidences = get_victim_evidence_ctr($user_id) ?? [];
$folders = get_folders_ctr($user_id) ?? [];

// Fetch Connected SPs for PIN Sharing
$db = new db_conn();
$connected_sps = [];
if ($db->db_connect()) {
    $stmt = $db->db->prepare("SELECT DISTINCT v.victim_id, v.victim_name FROM victim v JOIN services s ON s.service_cat = v.provider_category_id JOIN appointments a ON a.service_id = s.service_id WHERE a.victim_id = ? AND v.user_role = 3");
    $stmt->execute([$user_id]);
    $connected_sps = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Determine Profile Image
$profile_pic = !empty($user['victim_image']) 
    ? "../uploads/users/" . $user['victim_image'] 
    : "https://ui-avatars.com/api/?name=" . urlencode($user['victim_name']) . "&background=c453ea&color=fff";

// Role Label
$roleLabel = "Survivor/User";
if ($user['user_role'] == 2) {
    $roleLabel = "Admin";
} elseif ($user['user_role'] == 3) {
    $roleLabel = "Service Provider";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile & Evidence Archive | GBVAid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #0f0a1e;
            color: #ffffff;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            margin: 0;
            background-image: radial-gradient(#3c2a61 1px, transparent 1px);
            background-size: 30px 30px;
        }
        
        /* Navbar styling tweak to fit dark theme if included navbar isn't dark */
        .navbar { border-bottom: 1px solid #3c2a61 !important; }

        .card {
            background: #1a1033;
            border: 1px solid #bf40ff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(191, 64, 255, 0.2);
            margin-bottom: 30px;
        }

        .card-header {
            background-color: #1a1033;
            color: #e0aaff;
            font-weight: 800;
            border-bottom: 1px solid #3c2a61;
            padding: 1.5rem;
            text-align: center;
        }

        .form-label {
            color: #d980ff;
            font-weight: 500;
        }
        .form-label i { margin-right: 5px; color: #bf40ff; }

        .form-control, .form-select {
            background-color: #0f0a1e;
            border: 1px solid #3c2a61;
            color: #fff;
            border-radius: 10px;
        }
        .form-control:focus, .form-select:focus {
            background-color: #150d2b;
            border-color: #bf40ff;
            color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(191, 64, 255, 0.25);
        }
        .form-control:disabled, .form-control[readonly] {
            background-color: #150d2b !important;
            opacity: 0.8;
        }

        .btn-custom {
            background-color: #9d4edd;
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 50px;
            transition: all 0.3s;
            box-shadow: 0 4px 20px rgba(157, 78, 221, 0.4);
        }
        .btn-custom:hover {
            background-color: #bf40ff;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(191, 64, 255, 0.6);
        }
        .btn-danger-custom {
            background-color: rgba(220, 53, 69, 0.2);
            color: #ff6b6b;
            border: 1px solid #dc3545;
            padding: 5px 15px;
            border-radius: 50px;
            transition: 0.3s;
        }
        .btn-danger-custom:hover {
            background-color: #dc3545;
            color: white;
        }
        .btn-view-custom {
            background-color: rgba(64, 255, 230, 0.2);
            color: #40ffe6;
            border: 1px solid #40ffe6;
            padding: 5px 15px;
            border-radius: 50px;
            transition: 0.3s;
            text-decoration: none;
            font-size: 0.9em;
        }
        .btn-view-custom:hover {
            background-color: #40ffe6;
            color: #0f0a1e;
        }

        .profile-img-box {
            position: relative;
            width: 140px;
            height: 140px;
            margin: 0 auto;
        }
        .profile-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #bf40ff;
            box-shadow: 0 0 20px rgba(191, 64, 255, 0.5);
        }
        .camera-icon {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: #9d4edd;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 3px solid #0f0a1e;
            transition: 0.3s;
        }
        .camera-icon:hover { background-color: #bf40ff; transform: scale(1.1); }
        #fileInput { display: none; }
        
        .role-badge {
            background: rgba(191, 64, 255, 0.2);
            color: #e0aaff;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9em;
            border: 1px solid #bf40ff;
            display: inline-block;
            margin-top: 10px;
        }

        /* Evidence Table Styling */
        .evidence-table { color: #fff; width: 100%; border-collapse: separate; border-spacing: 0 10px; }
        .evidence-table th { color: #d980ff; font-weight: 500; padding: 10px; border-bottom: 1px solid #3c2a61; }
        .evidence-table td { background: #150d2b; padding: 15px; vertical-align: middle; }
        .evidence-table tr td:first-child { border-top-left-radius: 10px; border-bottom-left-radius: 10px; }
        .evidence-table tr td:last-child { border-top-right-radius: 10px; border-bottom-right-radius: 10px; }
        
        .evidence-icon { font-size: 1.5rem; color: #bf40ff; }
    </style>
</head>
<body>

<?php include '../views/navbar.php'; ?>

<div class="container my-5">
    <div class="row">
        <!-- Profile Settings Column -->
        <div class="col-lg-5 animate__animated animate__fadeInLeft">
            <div class="card p-3">
                <div class="card-header">
                    <h4 class="mb-0">Profile Settings</h4>
                </div>
                <div class="card-body">
                    <form id="profile-form" enctype="multipart/form-data">
                        <div class="text-center mb-4">
                            <div class="profile-img-box">
                                <img src="<?= $profile_pic ?>" id="previewImg" class="profile-img" alt="Profile">
                                <label for="fileInput" class="camera-icon">
                                    <i class="fa fa-camera"></i>
                                </label>
                                <input type="file" id="fileInput" name="profile_image" accept="image/*" onchange="previewFile(this)">
                            </div>
                            <div class="role-badge"><i class="fa fa-user-shield"></i> <?= $roleLabel ?></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><i class="fa fa-user"></i> Full Name</label>
                            <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user['victim_name']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><i class="fa fa-envelope"></i> Email Address</label>
                            <input type="email" class="form-control" value="<?= htmlspecialchars($user['victim_email']) ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><i class="fa fa-phone"></i> Phone Number</label>
                            <input type="text" name="phone_number" class="form-control" value="<?= htmlspecialchars($user['victim_contact']) ?>" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="fa fa-building"></i> City</label>
                                <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($user['victim_city']) ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="fa fa-globe"></i> Country</label>
                                <input type="text" name="country" class="form-control" value="<?= htmlspecialchars($user['victim_country']) ?>">
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-custom w-100 mt-3">
                            <i class="fa fa-save"></i> Save Changes
                        </button>
                    </form>
                </div>
            </div>

            <!-- Safe Space Handshake Card -->
            <div class="card p-3 mb-4" style="border-color: #0dcaf0;">
                <div class="card-header" style="border-bottom-color: #0dcaf0;">
                    <h4 class="mb-0 text-info"><i class="bi bi-shield-lock-fill me-2"></i> Safe Space Handshake</h4>
                </div>
                <div class="card-body text-center">
                    <p class="text-muted small mb-4">You have complete control over who sees your Evidence Vault. Generate a Temporary Access PIN and share it directly with your assigned Service Provider outside the app.</p>
                    
                    <div id="pinDisplayArea" class="mb-3" style="display: <?= !empty($user['vault_pin']) && strtotime($user['vault_pin_expires']) > time() ? 'block' : 'none' ?>;">
                        <div class="p-3 rounded" style="background: rgba(13, 202, 240, 0.1); border: 1px dashed #0dcaf0;">
                            <span class="text-uppercase text-muted small fw-bold mb-1 d-block">Active Vault PIN</span>
                            <h2 class="fw-bold text-info letter-spacing-2 mb-0" id="activePinDisplay"><?= htmlspecialchars($user['vault_pin']) ?></h2>
                            <small class="text-danger mt-2 d-block" id="pinExpiryDisplay">Expires: <?= !empty($user['vault_pin_expires']) ? date('M j, g:i A', strtotime($user['vault_pin_expires'])) : '' ?></small>
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-12">
                            <button class="btn btn-info w-100 fw-bold" onclick="generateVaultPin()">
                                <i class="bi bi-key-fill me-1"></i> Generate New PIN
                            </button>
                        </div>
                    </div>
                    <?php if (!empty($user['vault_pin']) && strtotime($user['vault_pin_expires']) > time()): ?>
                    <div class="mt-3 text-start">
                        <label class="form-label small text-muted"><i class="bi bi-person-check-fill"></i> Share with Provider</label>
                        <div class="input-group mb-2">
                            <select class="form-select" id="spSelect" style="background:#0f0a1e; color:#fff; border-color:#0dcaf0;">
                                <option value="">Select Connected Provider...</option>
                                <?php foreach($connected_sps as $sp): ?>
                                    <option value="<?= $sp['victim_id'] ?>"><?= htmlspecialchars($sp['victim_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button class="btn btn-outline-info" onclick="sendPinToSP()">
                                <i class="bi bi-send-fill"></i> Send
                            </button>
                        </div>
                        <button class="btn btn-sm btn-outline-info w-100 mt-1" onclick="copyPinDetails()">
                            <i class="bi bi-clipboard"></i> Copy Access Details
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ⚡ Quick Exit Settings Card -->
            <div class="card p-3 mb-4" style="border-color: #ff4d4d;">
                <div class="card-header" style="border-bottom-color: #ff4d4d;">
                    <h4 class="mb-0" style="color: #ff6b6b;"><i class="bi bi-lightning-fill me-2"></i>Quick Exit Settings</h4>
                </div>
                <div class="card-body">
                    <p style="color: #c8a8e9;" class="small mb-3">
                        Set two links that the <strong style="color:#ff6b6b;">⚡ QUICK EXIT</strong> button will randomly redirect to. This lets you instantly navigate away to a safe page if someone approaches. The button replaces your history so they won't see GBVAid.
                    </p>
                    <form id="quick-exit-form">
                        <div class="mb-3">
                            <label class="form-label"><i class="bi bi-link-45deg me-1"></i>Exit Link 1</label>
                            <input type="text" name="quick_exit_url1" class="form-control" 
                                   value="<?= htmlspecialchars($user['quick_exit_url1'] ?? '') ?>"
                                   placeholder="e.g., https://weather.com or google.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><i class="bi bi-link-45deg me-1"></i>Exit Link 2</label>
                            <input type="text" name="quick_exit_url2" class="form-control"
                                   value="<?= htmlspecialchars($user['quick_exit_url2'] ?? '') ?>"
                                   placeholder="e.g., https://www.bbc.com/news">
                        </div>
                        <small style="color:#8a68b0;" class="d-block mb-3"><i class="bi bi-info-circle me-1"></i>Enter full URLs (https://...) or just a domain like <em>news.bbc.co.uk</em></small>
                        <button type="submit" class="btn w-100 fw-bold" style="background: rgba(255,77,77,0.15); border: 1px solid #ff4d4d; color: #ff6b6b; border-radius: 50px;">
                            <i class="bi bi-floppy-fill me-2"></i>Save Quick Exit Links
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Evidence Archive Column -->
        <div class="col-lg-7 animate__animated animate__fadeInRight">
            <div class="card p-3 mb-4">
                <div class="card-header">
                    <h4 class="mb-0">Secure Evidence Archive</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-4">Safely upload and store evidence (files or secure notes) related to incidents. Files are securely renamed and stored privately.</p>
                    
                    <form id="evidence-form" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label"><i class="fa fa-folder"></i> Target Folder</label>
                            <div class="input-group">
                                <select name="folder_id" class="form-select">
                                    <option value="">No Folder (Root Archive)</option>
                                    <?php foreach($folders as $folder): ?>
                                        <option value="<?= $folder['folder_id'] ?>"><?= htmlspecialchars($folder['folder_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="button" class="btn btn-outline-light" onclick="createNewFolder()">
                                    <i class="fa fa-plus"></i> New Folder
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><i class="fa fa-tag"></i> Evidence Title</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g., Harassment WhatsApp Screenshots" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><i class="fa fa-align-left"></i> Description (Optional)</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Brief context about this evidence..."></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label d-block"><i class="fa fa-hdd"></i> Evidence Type</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="evidence_type" id="type_file" value="file" checked autocomplete="off">
                                <label class="btn btn-outline-light" for="type_file">Upload File</label>

                                <input type="radio" class="btn-check" name="evidence_type" id="type_text" value="raw_text" autocomplete="off">
                                <label class="btn btn-outline-light" for="type_text">Raw Text Note</label>
                            </div>
                        </div>

                        <!-- File Input Section -->
                        <div id="fileUploadSection" class="mb-4">
                            <label class="form-label"><i class="fa fa-upload"></i> Attach File</label>
                            <input type="file" class="form-control" name="evidence_file" accept=".jpg,.jpeg,.png,.pdf,.mp3,.mp4,.docx,.txt,.wav,.m4a">
                            <small class="text-muted d-block mt-1">Supports images, documents, audio, and video (Max 20MB).</small>
                        </div>

                        <!-- Text Input Section -->
                        <div id="textNoteSection" class="mb-4" style="display: none;">
                            <label class="form-label"><i class="fa fa-keyboard"></i> Secure Note</label>
                            <textarea name="raw_text_content" class="form-control" rows="5" placeholder="Type your evidence note here..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-custom w-100">
                            <i class="fa fa-lock"></i> Save to Archive
                        </button>
                    </form>
                </div>
            </div>

            <!-- Evidence List -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="form-label mb-0"><i class="fa fa-archive"></i> My Saved Evidence</h5>
                <button type="button" class="btn btn-sm btn-outline-info" onclick="createZip()">
                    <i class="fa fa-file-archive"></i> ZIP Selected
                </button>
            </div>
            
            <form id="zip-form" action="../actions/create_zip_action.php" method="POST">
            <div class="table-responsive">
                <table class="evidence-table">
                    <thead>
                        <tr>
                            <th style="width: 40px;"><input type="checkbox" id="selectAllEvidence" class="form-check-input"></th>
                            <th>Folder</th>
                            <th>Details</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($evidences)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">No evidence securely stored yet.</td>
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
                                    if(in_array($ext, ['zip'])) $icon = "fa-file-archive";

                                    $folderName = "Root";
                                    if ($ev['folder_id']) {
                                        foreach($folders as $f) {
                                            if ($f['folder_id'] == $ev['folder_id']) {
                                                $folderName = $f['folder_name']; break;
                                            }
                                        }
                                    }
                                ?>
                                <tr>
                                    <td><input type="checkbox" name="evidence_ids[]" value="<?= $ev['evidence_id'] ?>" class="form-check-input evidence-checkbox"></td>
                                    <td><span class="badge bg-secondary opacity-75"><i class="fa fa-folder"></i> <?= htmlspecialchars($folderName) ?></span></td>
                                    <td>
                                        <strong><?= htmlspecialchars($ev['title']) ?></strong><br>
                                        <small class="text-muted"><?= htmlspecialchars($ev['description']) ?></small>
                                    </td>
                                    <td class="text-end text-nowrap">
                                        <?php if($ev['file_type'] === 'raw_text'): ?>
                                            <button type="button" class="btn btn-view-custom btn-sm" onclick="viewTextNote(<?= htmlspecialchars(json_encode($ev['raw_text_content'])) ?>)">
                                                <i class="fa fa-eye"></i> View
                                            </button>
                                        <?php else: ?>
                                            <a href="../uploads/evidence/<?= htmlspecialchars($ev['file_path']) ?>" target="_blank" class="btn btn-view-custom btn-sm">
                                                <i class="fa fa-download"></i> File
                                            </a>
                                        <?php endif; ?>
                                        
                                        <button type="button" class="btn btn-danger-custom btn-sm ms-1" onclick="deleteEvidence(<?= $ev['evidence_id'] ?>)">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Toggle Evidence Upload Types
    $('input[name="evidence_type"]').change(function() {
        if ($(this).val() === 'file') {
            $('#fileUploadSection').slideDown();
            $('#textNoteSection').slideUp();
        } else {
            $('#fileUploadSection').slideUp();
            $('#textNoteSection').slideDown();
        }
    });

    // Profile Image Preview
    function previewFile(input) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function() {
                $("#previewImg").attr("src", reader.result);
            }
            reader.readAsDataURL(file);
        }
    }

    // Profile Form Submission
    $('#profile-form').on('submit', function(e) {
        e.preventDefault();
        const btn = $(this).find('button[type="submit"]');
        const originalText = btn.html();
        btn.html('<i class="fa fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

        const formData = new FormData(this);
        $.ajax({
            url: '../actions/update_profile_action.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false, contentType: false,
            success: function(res) {
                if(res.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Updated!', text: res.message, confirmButtonColor: '#bf40ff' }).then(() => location.reload());
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            },
            complete: function() { btn.html(originalText).prop('disabled', false); }
        });
    });

    // Evidence Form Submission
    $('#evidence-form').on('submit', function(e) {
        e.preventDefault();
        const btn = $(this).find('button[type="submit"]');
        const originalText = btn.html();
        btn.html('<i class="fa fa-spinner fa-spin"></i> Encrypting & Saving...').prop('disabled', true);

        const formData = new FormData(this);
        $.ajax({
            url: '../actions/add_evidence_action.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false, contentType: false,
            success: function(res) {
                if(res.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Secured!', text: res.message, confirmButtonColor: '#bf40ff' }).then(() => location.reload());
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            },
            complete: function() { btn.html(originalText).prop('disabled', false); }
        });
    });

    // View Text Note
    function viewTextNote(content) {
        Swal.fire({
            title: '<i class="fa fa-lock" style="color:#bf40ff"></i> Secure Note',
            text: content,
            width: 600,
            padding: '2em',
            background: '#1a1033',
            color: '#fff',
            confirmButtonColor: '#bf40ff'
        });
    }

    // Delete Evidence
    function deleteEvidence(evidenceId) {
        Swal.fire({
            title: 'Delete this evidence?',
            text: "This action cannot be undone. Files will be permanently deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#3c2a61',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('../actions/delete_evidence_action.php', { evidence_id: evidenceId }, function(res) {
                    if(res.status === 'success') {
                        Swal.fire({ icon: 'success', title: 'Deleted', text: res.message, confirmButtonColor: '#bf40ff' }).then(()=>location.reload());
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                }, 'json');
            }
        });
    }

    function generateVaultPin() {
        const btn = event.currentTarget;
        const oText = btn.innerHTML;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Generating...';
        btn.disabled = true;

        $.post('../actions/generate_pin_action.php', function(res) {
            if (res.success) {
                $('#activePinDisplay').text(res.pin);
                $('#pinExpiryDisplay').text('Expires: ' + res.expires);
                $('#pinDisplayArea').slideDown();
                Swal.fire({
                    icon: 'success',
                    title: 'PIN Generated',
                    text: 'Your Temporary Access PIN is ready. Share it directly with your Service Provider.',
                    confirmButtonColor: '#0dcaf0',
                    background: '#1a1033',
                    color: '#fff'
                }).then(() => location.reload()); // Reload to show new buttons
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        }, 'json').always(() => {
            btn.innerHTML = oText;
            btn.disabled = false;
        });
    }

    // Passcode Sharing Features
    function sendPinToSP() {
        const sp_id = $('#spSelect').val();
        if (!sp_id) {
            Swal.fire('Notice', 'Please select a provider first.', 'info');
            return;
        }
        
        const pin = $('#activePinDisplay').text();
        $.post('../actions/share_pin_action.php', { sp_id: sp_id, pin: pin }, function(res) {
            if (res.status === 'success') {
                Swal.fire({ icon: 'success', title: 'Sent!', text: 'Your Vault PIN was sent securely.', confirmButtonColor: '#0dcaf0', background: '#1a1033', color: '#fff'});
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        }, 'json');
    }

    function copyPinDetails() {
        const pin = $('#activePinDisplay').text();
        const msg = `Hello, my secure Vault PIN is: ${pin}. You can enter it on your portal to view my evidence archive.`;
        navigator.clipboard.writeText(msg).then(() => {
            Swal.fire({ icon: 'success', title: 'Copied!', text: 'PIN details copied to clipboard.', timer: 1500, showConfirmButton: false, background: '#1a1033', color: '#fff'});
        });
    }

    // Folder Creation
    function createNewFolder() {
        Swal.fire({
            title: 'New Folder',
            input: 'text',
            inputPlaceholder: 'Enter folder name (e.g., Harassment Logs)',
            showCancelButton: true,
            confirmButtonColor: '#bf40ff',
            background: '#1a1033',
            color: '#fff',
            inputValidator: (value) => {
                if (!value) return 'Folder name is required!'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('../actions/create_folder_action.php', { folder_name: result.value }, function(res) {
                    if (res.status === 'success') location.reload();
                    else Swal.fire('Error', res.message, 'error');
                }, 'json');
            }
        });
    }

    // Select All Checkboxes
    $('#selectAllEvidence').change(function() {
        $('.evidence-checkbox').prop('checked', $(this).prop('checked'));
    });

    // Create Zip
    function createZip() {
        if ($('.evidence-checkbox:checked').length === 0) {
            Swal.fire('Notice', 'Please select at least one evidence file to ZIP.', 'info');
            return;
        }
        $('#zip-form').submit();
    }

    // Quick Exit URL Form
    $('#quick-exit-form').on('submit', function(e) {
        e.preventDefault();
        const btn = $(this).find('button[type="submit"]');
        const orig = btn.html();
        btn.html('<span class="spinner-border spinner-border-sm"></span> Saving...').prop('disabled', true);

        $.post('../actions/update_quick_exit_action.php', $(this).serialize(), function(res) {
            if (res.status === 'success') {
                Swal.fire({ icon: 'success', title: 'Saved!', text: res.message, confirmButtonColor: '#ff4d4d', background: '#1a1033', color: '#fff' });
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: res.message, background: '#1a1033', color: '#fff' });
            }
        }, 'json').always(() => { btn.html(orig).prop('disabled', false); });
    });
</script>
</body>
</html>