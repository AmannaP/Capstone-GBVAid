<?php
require_once '../settings/core.php';
require_once '../controllers/service_controller.php';

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
                <h4 class="fw-bold mb-4 text-center text-purple">Schedule Session</h4>
                
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

                    <button type="submit" class="btn btn-purple w-100 py-3 fw-bold rounded-pill mb-3">
                        Confirm Appointment
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

<script>
    $('#booking-form').on('submit', function(e) {
        e.preventDefault();
        
        const btn = $(this).find('button[type="submit"]');
        const originalText = btn.text();
        btn.html('<span class="spinner-border spinner-border-sm"></span> Securing Slot...').prop('disabled', true);

        $.ajax({
            url: '../actions/add_to_cart_action.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if(res.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Appointment Requested',
                        text: 'Your session has been added to the booking list.',
                        showCancelButton: true,
                        confirmButtonText: 'View My List',
                        cancelButtonText: 'Add More Services',
                        confirmButtonColor: '#9d4edd',
                        background: '#1a1033',
                        color: '#fff'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '../views/cart.php';
                        } else {
                            window.location.href = '../user/service_page.php';
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Booking Failed',
                        text: res.message,
                        background: '#1a1033',
                        color: '#fff'
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'System Error',
                    text: 'Please Login to Access Services.',
                    background: '#1a1033',
                    color: '#fff'
                });
            },
            complete: function() {
                btn.html(originalText).prop('disabled', false);
            }
        });
    });
</script>

</body>
</html>