<?php
require_once '../settings/core.php';
require_once '../controllers/appointment_controller.php';

// Restrict to Admin
requireAdmin();

// 1. Fetch all bookings
$all_bookings = get_all_bookings_admin_ctr();

// 2. Filter & Sort Logic
$upcoming = [];
$history = [];
$now = new DateTime();
// Set timezone to match your database/server setting
$now->setTimezone(new DateTimeZone('Africa/Accra'));

foreach ($all_bookings as $b) {
    $apptTime = new DateTime($b['appointment_date'] . ' ' . $b['appointment_time']);
    
    // Sort into buckets
    if ($apptTime >= $now && $b['status'] != 'Cancelled' && $b['status'] != 'Completed') {
        $upcoming[] = $b;
    } else {
        $history[] = $b;
    }
}

// Sort UPCOMING: Earliest date at the top (Ascending)
usort($upcoming, function($a, $b) {
    $t1 = strtotime($a['appointment_date'] . ' ' . $a['appointment_time']);
    $t2 = strtotime($b['appointment_date'] . ' ' . $b['appointment_time']);
    return $t1 - $t2;
});

// Sort HISTORY: Most recent at the top (Descending)
usort($history, function($a, $b) {
    $t1 = strtotime($a['appointment_date'] . ' ' . $a['appointment_time']);
    $t2 = strtotime($b['appointment_date'] . ' ' . $b['appointment_time']);
    return $t2 - $t1;
});

// Extract unique categories for filter
$categories = [];
foreach ($all_bookings as $b) {
    if (!empty($b['cat_name'])) $categories[$b['cat_name']] = true;
}
$categories = array_keys($categories);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Bookings | GBVAid Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #0f0a1e; background-image: radial-gradient(#3c2a61 1px, transparent 1px); background-size: 30px 30px; font-family: 'Poppins', sans-serif; color: #fff; }
        
        /* Admin Navbar */
        .navbar-admin { background: rgba(26, 16, 51, 0.95); backdrop-filter: blur(10px); border-bottom: 1px solid #3c2a61; padding: 15px 0; }
        .navbar-brand { color: #e0aaff !important; font-weight: 800; }
        
        .navbar-nav .nav-link { 
            color: rgba(255,255,255,0.8) !important; 
            font-weight: 500; 
        }
        .navbar-nav .nav-link:hover, .navbar-nav .nav-link.active { color: #d980ff !important; }
        
        .btn-logout { border: 2px solid #bf40ff; color: #e0aaff; border-radius: 50px; font-weight: 700; text-decoration: none; padding: 5px 20px; }
        .btn-logout:hover { background: #bf40ff; color: white; }

        /* Content */
        .content-card {
            background: rgba(26, 16, 51, 0.9);
            border: 1px solid #3c2a61;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(191, 64, 255, 0.1);
            padding: 30px;
            margin-top: 30px;
            margin-bottom: 50px;
        }

        /* Tabs Styling */
        .nav-tabs { border-bottom: 2px solid #3c2a61; margin-bottom: 20px; }
        
        .nav-tabs .nav-link {
            color: rgba(255, 255, 255, 0.5) !important;
            font-weight: 600;
            border: none;
            background: transparent;
            font-size: 1.1rem;
            padding-bottom: 10px;
        }
        
        .nav-tabs .nav-link.active {
            color: #d980ff !important;
            border-bottom: 3px solid #bf40ff;
            background: transparent;
        }
        
        .nav-tabs .nav-link:hover { color: #e0aaff !important; }

        .table thead th {
            background: rgba(157, 78, 221, 0.3);
            color: #e0aaff;
            border: none;
            padding: 15px;
        }
        .table tbody td { color: #e0aaff; border-color: #3c2a61; padding: 12px 15px; vertical-align: middle; }
        /* Override Bootstrap 5 table-hover CSS variable so rows don't flash white */
        .table-hover > tbody > tr:hover > * { --bs-table-color: #f0d9ff; --bs-table-bg: rgba(191, 64, 255, 0.05); color: #f0d9ff; background-color: rgba(191, 64, 255, 0.05); }
        /* Bootstrap 5 override for row base color */
        .table > :not(caption) > * > * {
            --bs-table-color: #e0aaff;
            --bs-table-bg: transparent;
            --bs-table-border-color: #3c2a61;
            color: #e0aaff;
            background-color: transparent;
        }
        
        .status-badge { font-size: 0.8rem; padding: 5px 12px; border-radius: 50px; }
        .bg-pending { background-color: rgba(255, 243, 205, 0.15); color: #ffc107; border: 1px solid #ffc107; }
        .bg-confirmed { background-color: rgba(25, 135, 84, 0.2); color: #20c997; border: 1px solid #20c997; }
        .bg-cancelled { background-color: rgba(220, 53, 69, 0.2); color: #ff6b6b; border: 1px solid #ff6b6b; }
        .bg-completed { background-color: rgba(13, 202, 240, 0.2); color: #0dcaf0; border: 1px solid #0dcaf0; }
        
        .filter-select {
            border: 2px solid #bf40ff;
            color: #e0aaff;
            background: #0f0a1e;
            font-weight: 600;
            border-radius: 50px;
            padding-left: 20px;
        }
        .filter-select:focus { box-shadow: 0 0 0 3px rgba(191, 64, 255, 0.3); }
        .filter-select option { background: #1a1033; }

        h3 { color: #e0aaff; }
        p.text-muted { color: #c8a8e9 !important; }
        .text-dark { color: #ffffff !important; }
        .text-muted { color: #c8a8e9 !important; }
        .text-secondary { color: #e0aaff !important; }
        .modal-content { background: #1a1033; border: 1px solid #3c2a61; color: #fff; }
        .modal-header { border-bottom-color: #3c2a61; }
        .modal-footer { border-top-color: #3c2a61; }
        .btn-secondary { background: #3c2a61; border: none; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-admin navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">GBVAid Admin</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item mx-2"><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                <li class="nav-item mx-2"><a href="bookings.php" class="nav-link active fw-bold">Bookings</a></li>
                <li class="nav-item mx-2"><a href="service.php" class="nav-link">Services</a></li>
                <li class="nav-item ms-4"><a href="../login/logout.php" class="btn-logout">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <div class="content-card">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1" style="color: #e0aaff;">All Service Bookings</h3>
                <p style="color: #c8a8e9;">Track user appointments, categories, and service providers.</p>
            </div>
            
            <div class="d-flex align-items-center">
                <label class="me-2 fw-bold" style="color: #e0aaff;">Filter by:</label>
                <select id="categoryFilter" class="form-select filter-select" style="width: 250px;">
                    <option value="all">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <ul class="nav nav-tabs" id="bookingTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button">
                    <i class="bi bi-calendar-check me-1"></i>Upcoming
                    <span class="ms-2" style="background: rgba(32,201,151,0.2); border: 1px solid #20c997; color: #20c997; border-radius: 50px; padding: 2px 10px; font-size: 0.8rem; font-weight: 700;"><?= count($upcoming) ?></span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button">
                    <i class="bi bi-clock-history me-1"></i>History / Cancelled
                    <span class="ms-2" style="background: rgba(255,107,107,0.2); border: 1px solid #ff6b6b; color: #ff6b6b; border-radius: 50px; padding: 2px 10px; font-size: 0.8rem; font-weight: 700;"><?= count($history) ?></span>
                </button>
            </li>
        </ul>
        
        <div class="tab-content" id="bookingTabContent">
            
            <div class="tab-pane fade show active" id="upcoming" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Client Name</th>
                                <th>Service / Provider</th>
                                <th>Category</th>
                                <th>Note</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="booking-list">
                            <?php if (empty($upcoming)): ?>
                                <tr><td colspan="7" class="text-center py-5" style="color: #c8a8e9;">No upcoming bookings found.</td></tr>
                            <?php else: ?>
                                <?php foreach ($upcoming as $b): 
                                    $dateObj = new DateTime($b['appointment_date']);
                                    $timeObj = new DateTime($b['appointment_time']);
                                    $statusClass = 'bg-confirmed'; // Default for upcoming paid slots
                                ?>
                                <tr class="booking-row" data-category="<?= htmlspecialchars($b['cat_name']) ?>">
                                    <td>
                                        <div class="fw-bold" style="color: #e0aaff;"><?= $dateObj->format('M d, Y') ?></div>
                                        <small style="color: #c8a8e9;"><?= $timeObj->format('h:i A') ?></small>
                                    </td>
                                    <td>
                                        <div class="fw-bold" style="color: #e0aaff;"><?= htmlspecialchars($b['victim_name']) ?></div>
                                        <small style="color: #c8a8e9;"><?= htmlspecialchars($b['victim_contact']) ?></small>
                                    </td>
                                    <td>
                                        <span style="color: #e0aaff;"><?= htmlspecialchars($b['service_title']) ?></span><br>
                                        <small style="color: #c8a8e9;"><i class="bi bi-building me-1"></i><?= htmlspecialchars($b['brand_name']) ?></small>
                                    </td>
                                    <td>
                                        <span class="badge" style="background: rgba(191,64,255,0.25); color: #e0aaff; border: 1px solid rgba(191,64,255,0.5);">
                                            <?= htmlspecialchars($b['cat_name']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if(!empty($b['notes'])): ?>
                                            <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-3" 
                                                    onclick="showNote('<?= htmlspecialchars(addslashes($b['notes'])) ?>')">
                                                View Note
                                            </button>
                                        <?php else: ?>
                                            <span style="color: #8a68b0;" class="small">None</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="status-badge <?= $statusClass ?>"><?= $b['status'] ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="history" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Client Name</th>
                                <th>Service / Provider</th>
                                <th>Category</th>
                                <th>Note</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="booking-list">
                            <?php if (empty($history)): ?>
                                <tr><td colspan="7" class="text-center py-5" style="color: #c8a8e9;">No past history found.</td></tr>
                            <?php else: ?>
                                <?php foreach ($history as $b): 
                                    $dateObj = new DateTime($b['appointment_date']);
                                    $timeObj = new DateTime($b['appointment_time']);
                                    
                                    $statusClass = 'bg-completed';
                                    if($b['status'] == 'Cancelled') $statusClass = 'bg-cancelled';
                                ?>
                                <tr class="booking-row" data-category="<?= htmlspecialchars($b['cat_name']) ?>">
                                    <td>
                                        <div class="fw-bold" style="color: #c8a8e9;"><?= $dateObj->format('M d, Y') ?></div>
                                        <small style="color: #8a68b0;"><?= $timeObj->format('h:i A') ?></small>
                                    </td>
                                    <td>
                                        <div style="color: #e0aaff;"><?= htmlspecialchars($b['victim_name']) ?></div>
                                    </td>
                                    <td style="color: #c8a8e9;">
                                        <?= htmlspecialchars($b['service_title']) ?>
                                    </td>
                                    <td>
                                        <span class="badge" style="background: rgba(100,80,140,0.3); color: #c8a8e9; border: 1px solid rgba(150,120,200,0.4);">
                                            <?= htmlspecialchars($b['cat_name']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if(!empty($b['notes'])): ?>
                                            <button type="button" class="btn btn-sm rounded-pill px-3" style="background: rgba(255,255,255,0.07); border: 1px solid rgba(200,168,233,0.3); color: #c8a8e9;"
                                                    onclick="showNote('<?= htmlspecialchars(addslashes($b['notes'])) ?>')">
                                                View Note
                                            </button>
                                        <?php else: ?>
                                            <span style="color: #8a68b0;" class="small">None</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="status-badge <?= $statusClass ?>"><?= $b['status'] ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="noteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Victim Note</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p id="noteContent" class="text-secondary" style="font-size: 1.1rem; line-height: 1.6;"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Show Note Modal Logic
    function showNote(note) {
        document.getElementById('noteContent').textContent = note;
        new bootstrap.Modal(document.getElementById('noteModal')).show();
    }

    // Filter Logic (Works on both tabs)
    document.getElementById('categoryFilter').addEventListener('change', function() {
        const selected = this.value;
        const rows = document.querySelectorAll('.booking-row');
        
        rows.forEach(row => {
            if (selected === 'all' || row.getAttribute('data-category') === selected) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

</body>
</html>