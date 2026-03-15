<?php
require_once '../settings/core.php';
require_once '../controllers/service_controller.php';
require_once '../settings/db_class.php';

if (!isset($_GET['id'])) {
    header("Location: service_page.php");
    exit();
}

$service_id = $_GET['id'];
$service = get_one_service_ctr($service_id);

if (!$service) {
    echo "Service not found.";
    exit();
}

$sp_status = $service['sp_availability'] ?? 'available';
$sp_note   = $service['sp_availability_note'] ?? '';
$is_unavailable = ($sp_status === 'unavailable');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($service['service_title']) ?> | GBVAid</title>
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

        /* Detail Image Styling */
        .service-img { 
            border-radius: 20px; 
            width: 100%; 
            height: 450px; 
            object-fit: cover; 
            border: 1px solid #3c2a61;
            box-shadow: 0 10px 30px rgba(191, 64, 255, 0.2); 
        }

        /* Glassmorphism Card for Booking */
        .booking-card { 
            border: 1px solid #3c2a61; 
            border-radius: 20px; 
            background: rgba(26, 16, 51, 0.9); 
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }

        .text-purple { color: #e0aaff; }
        
        .service-title-detail {
            color: #f3e8ff;
            font-weight: 800;
            text-shadow: 0 0 10px rgba(191, 64, 255, 0.5);
        }

        .btn-purple { 
            background-color: #9d4edd; 
            color: white; 
            border: none; 
            transition: 0.3s;
            box-shadow: 0 4px 15px rgba(157, 78, 221, 0.3);
        }
        .btn-purple:hover { 
            background-color: #bf40ff; 
            color: white; 
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(191, 64, 255, 0.5);
        }

        .form-label { color: #d980ff; font-weight: 600; }
        
        .form-control, .form-select {
            background-color: #1a1033;
            border: 1px solid #3c2a61;
            color: #fff;
            border-radius: 10px;
        }
        .form-control:focus, .form-select:focus {
            background-color: #241445;
            border-color: #bf40ff;
            color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(191, 64, 255, 0.25);
        }

        .price-tag {
            background: rgba(191, 64, 255, 0.1);
            padding: 10px 20px;
            border-radius: 10px;
            display: inline-block;
            border: 1px solid rgba(191, 64, 255, 0.3);
        }
    </style>
</head>
<body>

<?php 
if (file_exists('../includes/navbar.php')) {
    include '../includes/navbar.php';
} else {
    include '../views/navbar.php';
}
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-7 mb-4 animate__animated animate__fadeInLeft">
            <img src="../uploads/services/<?= htmlspecialchars($service['service_image']) ?>" class="service-img mb-4" alt="Service">
            <h1 class="service-title-detail mb-3"><?= htmlspecialchars($service['service_title']) ?></h1>
            
            <div class="price-tag mb-4">
                <h3 class="text-purple fw-bold mb-0">
                    GH₵ <?= number_format($service['service_price'], 2) ?> 
                    <small class="text-light opacity-50 fs-6">/ Session</small>
                </h3>
            </div>
            
            <div class="description-box">
                <h5 class="fw-bold text-purple mb-3">About this Service</h5>
                <p class="lead opacity-75" style="line-height: 1.8;">
                    <?= nl2br(htmlspecialchars($service['service_desc'])) ?>
                </p>
            </div>
        </div>

        <div class="col-md-5 animate__animated animate__fadeInRight">
            <div class="booking-card card p-4">
                <h4 class="fw-bold mb-3 text-center text-purple">Schedule Session</h4>

                <!-- Provider Availability Badge -->
                <?php
                    $badge_style = $sp_status === 'available'
                        ? 'background:rgba(34,197,94,0.15); border:1px solid #22c55e; color:#22c55e;'
                        : ($sp_status === 'busy'
                            ? 'background:rgba(255,193,7,0.15); border:1px solid #ffc107; color:#ffc107;'
                            : 'background:rgba(255,107,107,0.15); border:1px solid #ff6b6b; color:#ff6b6b;');
                    $badge_icon = $sp_status === 'available' ? 'bi-check-circle-fill' : ($sp_status === 'busy' ? 'bi-hourglass-split' : 'bi-slash-circle-fill');
                    $badge_text = $sp_status === 'available' ? 'Provider Available' : ($sp_status === 'busy' ? 'Provider Busy — Limited Slots' : 'Provider Currently Unavailable');
                ?>
                <div class="text-center mb-4">
                    <span class="badge rounded-pill px-3 py-2 fw-bold" style="<?= $badge_style ?> font-size:0.82rem;">
                        <i class="bi <?= $badge_icon ?> me-1"></i><?= $badge_text ?>
                    </span>
                    <?php if (!empty($sp_note)): ?>
                        <div class="small mt-2" style="color:#b89fd4;">
                            <i class="bi bi-info-circle me-1"></i><?= htmlspecialchars($sp_note) ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <form id="booking-form">
                    <input type="hidden" name="service_id" value="<?= $service['service_id'] ?>">
                    <input type="hidden" name="qty" value="1">
                    
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-calendar-event me-2"></i>Preferred Date</label>
                        <input type="date" name="date" class="form-control" min="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-clock me-2"></i>Preferred Time</label>
                        <select name="time" class="form-select" required>
                            <option value="">-- Choose a Slot --</option>
                            <option value="09:00">09:00 AM</option>
                            <option value="10:00">10:00 AM</option>
                            <option value="11:00">11:00 AM</option>
                            <option value="13:00">01:00 PM</option>
                            <option value="14:00">02:00 PM</option>
                            <option value="15:00">03:00 PM</option>
                            <option value="16:00">04:00 PM</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label"><i class="bi bi-pencil-square me-2"></i>Notes / Requirements</label>
                        <textarea name="notes" class="form-control" rows="4" placeholder="Briefly describe your situation so we can prepare..."></textarea>
                    </div>

                    <?php if ($is_unavailable): ?>
                        <div class="alert mb-3" style="background:rgba(255,107,107,0.1); border:1px solid rgba(255,107,107,0.4); border-radius:12px; color:#ff6b6b;">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Bookings Paused</strong> — This provider is currently unavailable. You may still submit your request and they will contact you when available.
                        </div>
                    <?php endif; ?>

                    <button type="submit" class="btn btn-purple w-100 py-3 fw-bold rounded-pill mb-3"
                            <?= $is_unavailable ? 'style="opacity:0.55; cursor:not-allowed;" title="Provider unavailable"' : '' ?>
                            id="bookBtn">
                        <?= $is_unavailable ? '<i class="bi bi-clock me-2"></i>Request (Provider Unavailable)' : 'Confirm Appointment' ?>
                    </button>
                    
                    <p class="text-center small opacity-50 mb-0">
                        <i class="bi bi-shield-lock me-1"></i> Your data is encrypted and confidential.
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

<footer class="text-center py-5 mt-5 border-top border-secondary">
    <div class="container">
        <p class="mb-1 fw-semibold">© <?= date('Y'); ?> <span style="color: #bf40ff;">GBVAid</span></p>
        <p class="small opacity-50">Providing a safe passage to recovery and justice.</p>
    </div>
</footer>
<script src="../js/booking_handler.js"></script>

</body>
</html>