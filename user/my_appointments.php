<?php
require_once '../settings/core.php';
require_once '../controllers/appointment_controller.php';

date_default_timezone_set('Africa/Accra');
requireLogin(); 

$data = get_categorized_appointments_ctr(getUserId());
$upcoming = $data['upcoming'];
$past = $data['past'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Appointments | GBVAid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #0f0a1e;
            font-family: 'Poppins', sans-serif;
            color: #ffffff;
            background-image: radial-gradient(#3c2a61 1px, transparent 1px);
            background-size: 30px 30px;
            background-attachment: fixed;
        }

        .page-container {
            background: rgba(26, 16, 51, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid #3c2a61;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            margin-top: 40px;
            margin-bottom: 40px;
        }

        .text-neon-purple {
            color: #e0aaff;
            text-shadow: 0 0 10px rgba(191, 64, 255, 0.3);
        }

        /* TABS STYLING */
        .nav-tabs { border-bottom: 1px solid #3c2a61; }
        .nav-tabs .nav-link {
            color: #a49db5 !important;
            font-weight: 600;
            border: none;
            transition: 0.3s;
        }
        .nav-tabs .nav-link.active {
            color: #bf40ff !important;
            background: transparent !important;
            border-bottom: 3px solid #bf40ff;
        }

        /* APPOINTMENT CARD STYLING */
        .appt-card {
            border: 1px solid #3c2a61;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.03);
            margin-bottom: 15px;
            transition: 0.3s;
        }
        .appt-card:hover {
            border-color: #bf40ff;
            background: rgba(191, 64, 255, 0.05);
            transform: translateY(-3px);
        }
        
        .date-box {
            text-align: center;
            background: rgba(157, 78, 221, 0.1);
            border: 1px solid rgba(191, 64, 255, 0.2);
            border-radius: 12px;
            padding: 10px;
            min-width: 85px;
        }
        .date-day { font-size: 1.5rem; font-weight: 800; color: #fff; }
        .date-month { font-size: 0.8rem; color: #e0aaff; text-transform: uppercase; }
        
        .btn-neon {
            background-color: #9d4edd;
            color: white;
            border-radius: 50px;
            border: none;
            transition: 0.3s;
        }
        .btn-neon:hover {
            background-color: #bf40ff;
            box-shadow: 0 0 15px rgba(191, 64, 255, 0.4);
        }

        .badge-confirmed {
            background: rgba(0, 255, 204, 0.1);
            color: #00ffcc;
            border: 1px solid rgba(0, 255, 204, 0.3);
        }
    </style>
</head>
<body>

<?php include '../views/navbar.php'; ?>

<div class="container">
    <div class="page-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0 text-neon-purple">My Scheduled Sessions</h2>
            <a href="service_page.php" class="btn btn-neon px-4 py-2 fw-bold">
                <i class="bi bi-plus-lg me-1"></i> Book New
            </a>
        </div>

        <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button">
                    Upcoming (<?= count($upcoming) ?>)
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button">
                    History
                </button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            
            <div class="tab-pane fade show active" id="upcoming" role="tabpanel">
                <?php if (empty($upcoming)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x text-muted opacity-25 display-1"></i>
                        <p class="mt-3 text-muted">No upcoming sessions. You're all clear.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($upcoming as $appt): 
                        $dateObj = new DateTime($appt['appointment_date']);
                        $timeObj = new DateTime($appt['appointment_time']);
                    ?>
                    <div class="appt-card p-3 d-flex align-items-center">
                        <div class="date-box me-3">
                            <div class="date-day"><?= $dateObj->format('d') ?></div>
                            <div class="date-month"><?= $dateObj->format('M') ?></div>
                        </div>
                        
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1" style="color: #f3e8ff;"><?= htmlspecialchars($appt['service_title']) ?></h5>
                            <div class="small opacity-75">
                                <i class="bi bi-clock me-1"></i> <?= $timeObj->format('h:i A') ?> 
                                <span class="mx-2">•</span> 
                                <span class="badge badge-confirmed">Confirmed</span>
                            </div>
                        </div>
                        
                        <div>
                            <button class="btn btn-outline-danger btn-sm rounded-pill cancel-btn px-3" data-id="<?= $appt['appointment_id'] ?>">
                                Cancel
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="tab-pane fade" id="history" role="tabpanel">
                <?php if (empty($past)): ?>
                    <div class="text-center py-5 text-muted">
                        <p>No past appointment history.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($past as $appt): 
                        $dateObj = new DateTime($appt['appointment_date']);
                        $isCancelled = ($appt['status'] == 'Cancelled');
                    ?>
                    <div class="appt-card p-3 d-flex align-items-center opacity-50">
                        <div class="date-box me-3" style="background: rgba(255,255,255,0.05);">
                            <div class="date-day"><?= $dateObj->format('d') ?></div>
                            <div class="date-month"><?= $dateObj->format('M') ?></div>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1 text-light"><?= htmlspecialchars($appt['service_title']) ?></h6>
                            <small class="badge <?= $isCancelled ? 'bg-danger' : 'bg-secondary' ?> bg-opacity-10 text-uppercase" style="font-size: 0.65rem;">
                                <?= $appt['status'] ?>
                            </small>
                            <small class="ms-2 opacity-50"><?= $dateObj->format('F d, Y') ?></small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/appointment_handler.js"></script>

</body>
</html>