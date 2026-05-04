<?php
require_once '../settings/core.php';
require_once '../settings/db_class.php';
require_once '../controllers/victim_controller.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 3 || $_SESSION['sp_approved'] == 0) {
    header("Location: ../login/login.php");
    exit();
}

$sp_id = $_SESSION['id'];
$user  = get_victim_ctr($sp_id);

// Fetch current availability status
$db = new db_conn();
$sp_row = $db->db_fetch_one("SELECT sp_availability, sp_availability_note FROM victim WHERE victim_id = $sp_id");
$current_status = $sp_row['sp_availability'] ?? 'available';
$current_note   = $sp_row['sp_availability_note'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Availability Settings | SP Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #0f0a1e;
            color: #ffffff;
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            min-height: 100vh;
            background-image: radial-gradient(#3c2a61 1px, transparent 1px);
            background-size: 30px 30px;
            background-attachment: fixed;
        }
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
        .form-label { color: #d980ff; font-weight: 500; }
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
        .form-select option { background: #1a1033; }

        /* Availability toggle buttons */
        .avail-btn {
            padding: 18px 12px;
            border-radius: 16px;
            cursor: pointer;
            border: 2px solid transparent;
            background: rgba(255,255,255,0.04);
            text-align: center;
            transition: all 0.25s;
            user-select: none;
        }
        .avail-btn i { font-size: 2rem; display: block; margin-bottom: 8px; }
        .avail-btn .label { font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; }
        .avail-btn .desc  { font-size: 0.72rem; opacity: 0.6; margin-top: 4px; }

        /* States */
        .avail-btn[data-val="available"]  { color: #22c55e; }
        .avail-btn[data-val="busy"]       { color: #ffc107; }
        .avail-btn[data-val="unavailable"]{ color: #ff6b6b; }

        .avail-btn.selected[data-val="available"]   { border-color: #22c55e; background: rgba(34,197,94,0.12); box-shadow: 0 0 20px rgba(34,197,94,0.2); }
        .avail-btn.selected[data-val="busy"]        { border-color: #ffc107; background: rgba(255,193,7,0.12); box-shadow: 0 0 20px rgba(255,193,7,0.2); }
        .avail-btn.selected[data-val="unavailable"] { border-color: #ff6b6b; background: rgba(255,107,107,0.12); box-shadow: 0 0 20px rgba(255,107,107,0.2); }

        .avail-btn:hover { transform: translateY(-3px); }

        /* Live indicator in header */
        .status-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
            animation: pulse-dot 1.8s infinite;
        }
        .dot-available   { background: #22c55e; box-shadow: 0 0 8px rgba(34,197,94,0.6); }
        .dot-busy        { background: #ffc107; box-shadow: 0 0 8px rgba(255,193,7,0.6); }
        .dot-unavailable { background: #ff6b6b; box-shadow: 0 0 8px rgba(255,107,107,0.6); }
        @keyframes pulse-dot {
            0%,100% { opacity:1; transform: scale(1); }
            50%      { opacity:.6; transform: scale(1.25); }
        }

        .btn-custom {
            background-color: #9d4edd; border: none; color: #fff;
            font-weight: 600; padding: 10px 20px; border-radius: 50px; transition: all 0.3s;
            box-shadow: 0 4px 20px rgba(157, 78, 221, 0.4);
        }
        .btn-custom:hover { background-color: #bf40ff; color: white; transform: translateY(-2px); }
        .text-muted { color: #b89fd4 !important; }

        /* Schedule grid */
        .day-row {
            display: flex; align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #2d1f4e;
            gap: 14px;
        }
        .day-row:last-child { border-bottom: none; }
        .day-label { width: 100px; font-weight: 600; color: #c8a8e9; font-size: 0.9rem; }
        .day-toggle input[type="checkbox"] { accent-color: #bf40ff; width: 18px; height: 18px; cursor: pointer; }
        .time-inputs { display: flex; gap: 10px; align-items: center; flex: 1; }
        .time-inputs input[type="time"] {
            background: #0f0a1e; border: 1px solid #3c2a61; color: #e0aaff;
            border-radius: 8px; padding: 6px 12px; font-size: 0.85rem;
        }
        .time-inputs input[type="time"]:focus { border-color: #bf40ff; outline: none; }
        .time-sep { color: #8a68b0; font-size: 0.8rem; }
    </style>
</head>
<body>

<?php include '../views/sp_navbar.php'; ?>

<div class="container my-5" style="max-width: 760px;">

    <!-- Page Header -->
    <div class="mb-4">
        <a href="dashboard.php" class="btn btn-sm btn-outline-secondary rounded-pill mb-3">
            <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
        </a>
        <h2 class="fw-bold" style="color: #e0aaff;">
            <i class="bi bi-gear-fill me-2" style="color:#bf40ff;"></i>Availability Settings
        </h2>
        <p class="text-muted">Control when survivors can book a session with you.</p>
    </div>

    <!-- Current Status Indicator -->
    <div class="card p-4 mb-4" style="border-color:#3c2a61;">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="text-muted small text-uppercase fw-bold mb-1">Your Current Status</div>
                <h4 class="fw-bold mb-0" id="liveStatusLabel">
                    <span class="status-dot dot-<?= $current_status ?>" id="liveDot"></span>
                    <span id="liveStatusText">
                        <?= $current_status === 'available' ? 'Available for Bookings' : ($current_status === 'busy' ? 'Occupied / Limited' : 'Unavailable') ?>
                    </span>
                </h4>
                <small class="text-muted mt-1 d-block" id="liveStatusNote"><?= htmlspecialchars($current_note) ?></small>
            </div>
            <div>
                <span class="badge rounded-pill px-3 py-2 fw-bold" id="liveBadge"
                    style="background:<?= $current_status === 'available' ? 'rgba(34,197,94,0.2); border:1px solid #22c55e; color:#22c55e' : ($current_status === 'busy' ? 'rgba(255,193,7,0.2); border:1px solid #ffc107; color:#ffc107' : 'rgba(255,107,107,0.2); border:1px solid #ff6b6b; color:#ff6b6b') ?>;">
                    <?= strtoupper($current_status) ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Availability Picker -->
    <div class="card p-4">
        <div class="card-header mb-4" style="margin: -24px -24px 24px -24px; border-radius: 20px 20px 0 0;">
            <h5 class="mb-0"><i class="bi bi-toggle-on me-2"></i>Set Your Status</h5>
        </div>

        <form id="availabilityForm">
            <!-- Status Tiles -->
            <div class="row g-3 mb-4">
                <div class="col-4">
                    <div class="avail-btn <?= $current_status === 'available' ? 'selected' : '' ?>" data-val="available" onclick="selectStatus(this)">
                        <i class="bi bi-check-circle-fill"></i>
                        <div class="label">Available</div>
                        <div class="desc">Open for new bookings</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="avail-btn <?= $current_status === 'busy' ? 'selected' : '' ?>" data-val="busy" onclick="selectStatus(this)">
                        <i class="bi bi-hourglass-split"></i>
                        <div class="label">Busy</div>
                        <div class="desc">Limited — at capacity</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="avail-btn <?= $current_status === 'unavailable' ? 'selected' : '' ?>" data-val="unavailable" onclick="selectStatus(this)">
                        <i class="bi bi-slash-circle-fill"></i>
                        <div class="label">Unavailable</div>
                        <div class="desc">Not accepting cases</div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="status" id="statusInput" value="<?= htmlspecialchars($current_status) ?>">

            <!-- Optional Note -->
            <div class="mb-4">
                <label class="form-label"><i class="bi bi-chat-left-text me-1"></i> Status Note <span class="text-muted small">(optional)</span></label>
                <input type="text" name="note" id="noteInput" class="form-control" maxlength="120"
                       value="<?= htmlspecialchars($current_note) ?>"
                       placeholder="e.g. On leave until Friday, Back Monday 9AM...">
                <small class="text-muted">This note will be shown to survivors on your service listing.</small>
            </div>

            <!-- Weekly Schedule -->
            <div class="mb-4">
                <label class="form-label mb-3"><i class="bi bi-calendar-week me-1"></i> Working Hours</label>
                <?php
                $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                $schedule = json_decode($sp_row['sp_schedule'] ?? '{}', true) ?: [];
                foreach ($days as $day):
                    $d = strtolower($day);
                    $on    = !empty($schedule[$d]['on']);
                    $start = $schedule[$d]['start'] ?? '09:00';
                    $end   = $schedule[$d]['end']   ?? '17:00';
                ?>
                <div class="day-row">
                    <div class="day-label"><?= $day ?></div>
                    <div class="day-toggle">
                        <input type="checkbox" name="days[<?= $d ?>][on]" id="day_<?= $d ?>"
                               <?= $on ? 'checked' : '' ?>
                               onchange="toggleDay('<?= $d ?>')">
                    </div>
                    <div class="time-inputs" id="times_<?= $d ?>" style="<?= $on ? '' : 'opacity:0.3; pointer-events:none;' ?>">
                        <input type="time" name="days[<?= $d ?>][start]" value="<?= $start ?>">
                        <span class="time-sep">to</span>
                        <input type="time" name="days[<?= $d ?>][end]"   value="<?= $end ?>">
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <button type="submit" class="btn btn-custom w-100">
                <i class="bi bi-floppy-fill me-2"></i>Save Availability Settings
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function selectStatus(el) {
        document.querySelectorAll('.avail-btn').forEach(b => b.classList.remove('selected'));
        el.classList.add('selected');
        const val = el.dataset.val;
        document.getElementById('statusInput').value = val;

        // Update live indicator
        const labels = {
            available:   'Available for Bookings',
            busy:        'Occupied / Limited',
            unavailable: 'Unavailable'
        };
        const dotClasses = {
            available:   'dot-available',
            busy:        'dot-busy',
            unavailable: 'dot-unavailable'
        };
        const badgeStyles = {
            available:   'background:rgba(34,197,94,0.2); border:1px solid #22c55e; color:#22c55e',
            busy:        'background:rgba(255,193,7,0.2); border:1px solid #ffc107; color:#ffc107',
            unavailable: 'background:rgba(255,107,107,0.2); border:1px solid #ff6b6b; color:#ff6b6b'
        };

        document.getElementById('liveStatusText').textContent = labels[val];
        const dot = document.getElementById('liveDot');
        dot.className = 'status-dot ' + dotClasses[val];
        const badge = document.getElementById('liveBadge');
        badge.style.cssText = badgeStyles[val] + '; padding: 8px 16px; border-radius: 50px; font-weight:700;';
        badge.textContent = val.toUpperCase();
    }

    function toggleDay(d) {
        const cb = document.getElementById('day_' + d);
        const box = document.getElementById('times_' + d);
        box.style.opacity = cb.checked ? '1' : '0.3';
        box.style.pointerEvents = cb.checked ? 'auto' : 'none';
    }

    // Sync note to live display
    document.getElementById('noteInput').addEventListener('input', function() {
        document.getElementById('liveStatusNote').textContent = this.value;
    });

    // Form submit
    $('#availabilityForm').on('submit', function(e) {
        e.preventDefault();
        const btn = $(this).find('button[type="submit"]');
        const orig = btn.html();
        btn.html('<i class="fa fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

        $.post('../actions/update_availability_action.php', $(this).serialize(), function(res) {
            if (res.status === 'success') {
                Swal.fire({
                    icon: 'success', title: 'Saved!', text: res.message,
                    confirmButtonColor: '#bf40ff', background: '#1a1033', color: '#fff'
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: res.message, background: '#1a1033', color: '#fff' });
            }
        }, 'json').always(() => btn.html(orig).prop('disabled', false));
    });
</script>
</body>
</html>
