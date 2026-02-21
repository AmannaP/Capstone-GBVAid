<?php
require_once '../settings/core.php';
// We allow guests to access contact support, so no strict checkLogin() here.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Support | GBVAid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* Custom Colors */
        .text-purple { color: #c453eaff !important; }
        .bg-purple-light { background-color: #f3e8ff; }
        
        .contact-card {
            border: none;
            border-radius: 20px;
            background: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        
        .icon-box {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-right: 15px;
            background-color: #f3e8ff;
            color: #c453eaff;
            transition: transform 0.3s;
        }
        
        .contact-item:hover .icon-box {
            transform: scale(1.1);
            background-color: #c453eaff;
            color: white;
        }
        
        .form-control {
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #eee;
            background-color: #fdfdfd;
        }
        .form-control:focus {
            border-color: #c453eaff;
            box-shadow: 0 0 0 4px rgba(196, 83, 234, 0.1);
        }
        
        .btn-purple {
            background-color: #c453eaff;
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            border: none;
            transition: all 0.3s;
        }
        .btn-purple:hover {
            background-color: #a020f0;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(160, 32, 240, 0.3);
        }
        
        .emergency-alert {
            border-left: 5px solid #dc3545;
            background-color: #fff5f5;
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
    <div class="text-center mb-5">
        <h2 class="fw-bold text-purple">How can we help you?</h2>
        <p class="text-muted" style="max-width: 600px; margin: 0 auto;">
            Our support team is here to listen. Whether you need technical assistance, 
            guidance on resources, or just have a question, reach out to us safely.
        </p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card contact-card">
                <div class="row g-0">
                    
                    <div class="col-md-5 bg-purple-light p-5 d-flex flex-column justify-content-between">
                        <div>
                            <h4 class="fw-bold mb-4" style="color: #4a1d5e;">Get in Touch</h4>
                            
                            <div class="contact-item d-flex align-items-center mb-4">
                                <div class="icon-box"><i class="bi bi-geo-alt-fill"></i></div>
                                <div>
                                    <strong class="d-block text-dark">Location</strong>
                                    <small class="text-muted">University of Ghana, Legon</small>
                                </div>
                            </div>

                            <div class="contact-item d-flex align-items-center mb-4">
                                <div class="icon-box"><i class="bi bi-envelope-fill"></i></div>
                                <div>
                                    <strong class="d-block text-dark">Email Us</strong>
                                    <small class="text-muted">support@gbvaid.org</small>
                                </div>
                            </div>

                            <div class="contact-item d-flex align-items-center">
                                <div class="icon-box"><i class="bi bi-telephone-fill"></i></div>
                                <div>
                                    <strong class="d-block text-dark">Helpline</strong>
                                    <small class="text-muted">+233 50 000 0000</small>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 p-3 bg-white rounded emergency-alert shadow-sm">
                            <h6 class="text-danger fw-bold mb-1"><i class="bi bi-exclamation-circle-fill me-2"></i>In Immediate Danger?</h6>
                            <p class="small text-muted mb-2">Please do not use this form. Call the Police or DOVVSU immediately.</p>
                            <a href="tel:18555" class="btn btn-danger btn-sm w-100 rounded-pill fw-bold">Call 18555 (DOVVSU)</a>
                        </div>
                    </div>

                    <div class="col-md-7 p-5 bg-white">
                        <h4 class="fw-bold mb-4">Send a Message</h4>
                        
                        <form action="#" method="POST" onsubmit="return submitDummyForm(event)">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-muted">Your Name (Optional)</label>
                                    <input type="text" class="form-control" placeholder="Enter name" 
                                           value="<?= isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : '' ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-muted">Email Address</label>
                                    <input type="email" class="form-control" placeholder="Enter email" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Subject</label>
                                <select class="form-select form-control">
                                    <option>General Inquiry</option>
                                    <option>Report Technical Issue</option>
                                    <option>Feedback on Resources</option>
                                    <option>Request Counseling Info</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted">Message</label>
                                <textarea class="form-control" rows="5" placeholder="How can we help you?" required></textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-purple">
                                    <i class="bi bi-send-fill me-2"></i> Send Message
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<footer class="text-center text-muted mt-5 mb-3">
    <small>© <?= date('Y'); ?> GBVAid — Empowering safety and support for all.</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function submitDummyForm(e) {
        e.preventDefault();
        // Simulation of a sent message
        Swal.fire({
            title: 'Message Sent!',
            text: 'Our support team has received your message and will get back to you shortly.',
            icon: 'success',
            confirmButtonColor: '#c453eaff'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to dashboard or reload
                window.location.href = 'dashboard.php';
            }
        });
        return false;
    }
</script>

</body>
</html>