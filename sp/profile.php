<?php
require_once '../settings/core.php';
require_once '../controllers/victim_controller.php';
require_once '../settings/db_class.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 3 || $_SESSION['sp_approved'] == 0) {
    header("Location: ../login/login.php");
    exit();
}

$sp_id = $_SESSION['id'];
$user  = get_victim_ctr($sp_id);

// Fetch SP-specific info (category, brand)
$db = new db_conn();
$sp_info = $db->db_fetch_one("
    SELECT v.provider_category_id, c.cat_name, b.brand_name
    FROM victim v
    LEFT JOIN categories c ON v.provider_category_id = c.cat_id
    LEFT JOIN brands b ON v.provider_brand_id = b.brand_id
    WHERE v.victim_id = $sp_id
");

// Fetch case counts for this SP's category
$cat_id = $sp_info['provider_category_id'] ?? 0;
$bookings = $db->db_fetch_all("
    SELECT a.status
    FROM appointments a
    INNER JOIN services s ON a.service_id = s.service_id
    WHERE s.service_cat = $cat_id
");
$pending_count  = 0;
$active_count   = 0;
$resolved_count = 0;
if ($bookings) {
    foreach ($bookings as $b) {
        if ($b['status'] == 'Pending') $pending_count++;
        if (in_array($b['status'], ['Assigned', 'Investigating'])) $active_count++;
        if (in_array($b['status'], ['Resolved', 'Completed'])) $resolved_count++;
    }
}

// Profile image
$profile_pic = !empty($user['victim_image'])
    ? "../uploads/users/" . $user['victim_image']
    : "https://ui-avatars.com/api/?name=" . urlencode($user['victim_name']) . "&background=9d4edd&color=fff";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile | GBVAid SP Portal</title>
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
            background-image: radial-gradient(#3c2a61 1px, transparent 1px);
            background-size: 30px 30px;
        }

        /* ---- Card ---- */
        .card {
            background: #1a1033;
            border: 1px solid #bf40ff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(191, 64, 255, 0.15);
            margin-bottom: 30px;
        }
        .card-header {
            background-color: rgba(157, 78, 221, 0.15);
            color: #e0aaff;
            font-weight: 800;
            border-bottom: 1px solid #3c2a61;
            padding: 1.25rem 1.5rem;
        }

        /* ---- Form ---- */
        .form-label { color: #d980ff; font-weight: 500; }
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

        /* ---- Buttons ---- */
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

        /* ---- Profile Image ---- */
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
            box-shadow: 0 0 25px rgba(191, 64, 255, 0.5);
        }
        .camera-icon {
            position: absolute;
            bottom: 5px; right: 5px;
            background: #9d4edd;
            color: white;
            width: 40px; height: 40px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            border: 3px solid #0f0a1e;
            transition: 0.3s;
        }
        .camera-icon:hover { background-color: #bf40ff; transform: scale(1.1); }
        #fileInput { display: none; }

        /* ---- Role Badge ---- */
        .role-badge {
            background: rgba(191, 64, 255, 0.2);
            color: #e0aaff;
            padding: 5px 18px;
            border-radius: 20px;
            font-size: 0.85em;
            border: 1px solid #bf40ff;
            display: inline-block;
            margin-top: 10px;
        }

        /* ---- Stat cards (case summary) ---- */
        .stat-mini {
            background: rgba(157, 78, 221, 0.12);
            border: 1px solid #3c2a61;
            border-radius: 15px;
            padding: 18px 12px;
            text-align: center;
            transition: 0.3s;
        }
        .stat-mini:hover { border-color: #bf40ff; transform: translateY(-3px); }
        .stat-mini .stat-num  { font-size: 2rem; font-weight: 800; color: #e0aaff; }
        .stat-mini .stat-lbl  { font-size: 0.78rem; color: #b89fd4; text-transform: uppercase; letter-spacing: 0.05em; }

        /* ---- Info rows in Provider Info card ---- */
        .info-row {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #2d1f4e;
            padding: 12px 0;
            gap: 14px;
        }
        .info-row:last-child { border-bottom: none; }
        .info-row .info-icon { color: #bf40ff; font-size: 1.2rem; width: 30px; text-align: center; }
        .info-row .info-lbl  { font-size: 0.75rem; color: #b89fd4; text-transform: uppercase; letter-spacing: 0.05em; }
        .info-row .info-val  { color: #e0aaff; font-weight: 600; }

        .text-muted { color: #b89fd4 !important; }
    </style>
</head>
<body>

<?php include '../views/sp_navbar.php'; ?>

<div class="container my-5">
    <div class="row g-4">

        <!-- LEFT: Profile Settings -->
        <div class="col-lg-5 animate__animated animate__fadeInLeft">

            <!-- Profile Photo & Edit Form -->
            <div class="card p-3">
                <div class="card-header"><h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>Profile Settings</h5></div>
                <div class="card-body">
                    <form id="profile-form" enctype="multipart/form-data">
                        <div class="text-center mb-4">
                            <div class="profile-img-box">
                                <img src="<?= $profile_pic ?>" id="previewImg" class="profile-img" alt="Profile Photo">
                                <label for="fileInput" class="camera-icon"><i class="fa fa-camera"></i></label>
                                <input type="file" id="fileInput" name="profile_image" accept="image/*" onchange="previewFile(this)">
                            </div>
                            <div class="role-badge"><i class="bi bi-briefcase-medical me-1"></i> Service Provider</div>
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
                                <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($user['victim_city'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="fa fa-globe"></i> Country</label>
                                <input type="text" name="country" class="form-control" value="<?= htmlspecialchars($user['victim_country'] ?? '') ?>">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-custom w-100 mt-2">
                            <i class="fa fa-save me-2"></i>Save Changes
                        </button>
                    </form>
                </div>
            </div>

            <!-- Provider Info (read-only) -->
            <div class="card p-3">
                <div class="card-header"><h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Provider Information</h5></div>
                <div class="card-body">
                    <div class="info-row">
                        <div class="info-icon"><i class="bi bi-tag-fill"></i></div>
                        <div>
                            <div class="info-lbl">Assigned Category</div>
                            <div class="info-val"><?= htmlspecialchars($sp_info['cat_name'] ?? 'Unassigned') ?></div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon"><i class="bi bi-building"></i></div>
                        <div>
                            <div class="info-lbl">Organisation / Brand</div>
                            <div class="info-val"><?= htmlspecialchars($sp_info['brand_name'] ?? 'Unassigned') ?></div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon"><i class="bi bi-shield-check"></i></div>
                        <div>
                            <div class="info-lbl">Account Status</div>
                            <div class="info-val">
                                <span class="badge" style="background:rgba(34,197,94,0.2); border:1px solid #22c55e; color:#22c55e; border-radius:50px; padding:4px 14px;">
                                    <i class="bi bi-patch-check-fill me-1"></i>Approved
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- RIGHT: Case Summary + Quick Actions -->
        <div class="col-lg-7 animate__animated animate__fadeInRight">

            <!-- Case Stats -->
            <div class="card p-3 mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="bi bi-bar-chart-line me-2"></i>My Case Summary</h5></div>
                <div class="card-body">
                    <p class="text-muted small mb-4">Live statistics for cases assigned to the <strong style="color:#e0aaff;"><?= htmlspecialchars($sp_info['cat_name'] ?? 'your') ?></strong> division.</p>
                    <div class="row g-3">
                        <div class="col-4">
                            <div class="stat-mini">
                                <i class="bi bi-hourglass-split mb-2 d-block" style="font-size:1.8rem; color:#ffc107;"></i>
                                <div class="stat-num"><?= $pending_count ?></div>
                                <div class="stat-lbl">New Requests</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-mini">
                                <i class="bi bi-activity mb-2 d-block" style="font-size:1.8rem; color:#0dcaf0;"></i>
                                <div class="stat-num"><?= $active_count ?></div>
                                <div class="stat-lbl">Active Cases</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-mini">
                                <i class="bi bi-check-circle-fill mb-2 d-block" style="font-size:1.8rem; color:#22c55e;"></i>
                                <div class="stat-num"><?= $resolved_count ?></div>
                                <div class="stat-lbl">Resolved</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card p-3">
                <div class="card-header"><h5 class="mb-0"><i class="bi bi-lightning-charge me-2"></i>Quick Actions</h5></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="triage.php" class="d-block text-decoration-none p-4 rounded-4 text-center"
                               style="background: linear-gradient(135deg, #9d4edd, #bf40ff); transition: 0.3s; box-shadow: 0 4px 20px rgba(191,64,255,0.3);"
                               onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform=''">
                                <i class="bi bi-inboxes" style="font-size: 2.5rem; color:#fff;"></i>
                                <h6 class="fw-bold mt-2 mb-1 text-white">Case Triage</h6>
                                <small class="text-white opacity-75">View &amp; update case statuses</small>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="evidence_handshake.php" class="d-block text-decoration-none p-4 rounded-4 text-center"
                               style="background: linear-gradient(135deg, #1e1b4b, #4c1d95); border: 1px solid #bf40ff; transition: 0.3s; box-shadow: 0 4px 20px rgba(76,29,149,0.4);"
                               onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform=''">
                                <i class="bi bi-shield-lock-fill" style="font-size: 2.5rem; color:#e0aaff;"></i>
                                <h6 class="fw-bold mt-2 mb-1 text-white">Evidence Vault</h6>
                                <small class="text-white opacity-75">Enter survivor PIN to unlock</small>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="dashboard.php" class="d-block text-decoration-none p-4 rounded-4 text-center"
                               style="background: rgba(157,78,221,0.12); border:1px solid #3c2a61; transition:0.3s;"
                               onmouseover="this.style.borderColor='#bf40ff'; this.style.transform='translateY(-4px)'" onmouseout="this.style.borderColor='#3c2a61'; this.style.transform=''">
                                <i class="bi bi-speedometer2" style="font-size:2.5rem; color:#d980ff;"></i>
                                <h6 class="fw-bold mt-2 mb-1" style="color:#e0aaff;">Dashboard</h6>
                                <small class="text-muted">Back to main overview</small>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="../login/logout.php" class="d-block text-decoration-none p-4 rounded-4 text-center"
                               style="background: rgba(220,53,69,0.1); border:1px solid rgba(220,53,69,0.4); transition:0.3s;"
                               onmouseover="this.style.borderColor='#dc3545'; this.style.transform='translateY(-4px)'" onmouseout="this.style.borderColor='rgba(220,53,69,0.4)'; this.style.transform=''">
                                <i class="bi bi-box-arrow-right" style="font-size:2.5rem; color:#ff6b6b;"></i>
                                <h6 class="fw-bold mt-2 mb-1" style="color:#ff6b6b;">Logout</h6>
                                <small class="text-muted">Sign out of SP Portal</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function previewFile(input) {
        if (input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => $('#previewImg').attr('src', e.target.result);
            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#profile-form').on('submit', function(e) {
        e.preventDefault();
        const btn = $(this).find('button[type="submit"]');
        const orig = btn.html();
        btn.html('<i class="fa fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

        $.ajax({
            url: '../actions/update_profile_action.php',
            type: 'POST',
            data: new FormData(this),
            dataType: 'json',
            processData: false,
            contentType: false,
            success: res => {
                if (res.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Updated!', text: res.message, confirmButtonColor: '#bf40ff', background: '#1a1033', color: '#fff' })
                        .then(() => location.reload());
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            },
            complete: () => btn.html(orig).prop('disabled', false)
        });
    });
</script>
</body>
</html>