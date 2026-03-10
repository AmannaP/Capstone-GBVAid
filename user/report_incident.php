<?php
// user/report_incident.php
require_once '../settings/core.php';
// if (!checkLogin()) header("Location: ../login/login.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Incident | GBVAid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #0f0a1e;
            font-family: 'Poppins', sans-serif;
            color: #ffffff;
            background-image: radial-gradient(#3c2a61 1px, transparent 1px);
            background-size: 30px 30px;
            background-attachment: fixed;
        }

        .report-card {
            border: 1px solid #3c2a61;
            border-radius: 20px;
            background: rgba(26, 16, 51, 0.9);
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            overflow: hidden;
        }

        .card-header-neon {
            background: linear-gradient(135deg, rgba(157, 78, 221, 0.2) 0%, rgba(60, 42, 97, 0.4) 100%);
            border-bottom: 1px solid #bf40ff;
            padding: 30px;
        }

        .text-neon-purple {
            color: #e0aaff;
            text-shadow: 0 0 10px rgba(191, 64, 255, 0.3);
        }

        .form-label {
            color: #d980ff;
            font-weight: 600;
            margin-top: 10px;
        }

        .form-control, .form-select {
            background-color: #1a1033;
            border: 1px solid #3c2a61;
            color: #fff;
            border-radius: 12px;
            padding: 12px;
        }

        .form-control:focus, .form-select:focus {
            background-color: #241445;
            border-color: #bf40ff;
            color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(191, 64, 255, 0.2);
        }

        /* Custom Placeholder Color */
        ::placeholder { color: #6b7280 !important; opacity: 0.8; }

        .btn-neon {
            background-color: #9d4edd;
            color: white;
            border: none;
            font-weight: 700;
            border-radius: 50px;
            padding: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(157, 78, 221, 0.3);
        }

        .btn-neon:hover {
            background-color: #bf40ff;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(191, 64, 255, 0.5);
        }

        .form-check-input:checked {
            background-color: #bf40ff;
            border-color: #bf40ff;
        }

        .anonymous-box {
            background: rgba(191, 64, 255, 0.05);
            border: 1px solid rgba(191, 64, 255, 0.2);
            border-radius: 12px;
            padding: 15px;
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
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="report-card animate__animated animate__fadeInUp">
                <div class="card-header-neon">
                    <h3 class="mb-1 fw-bold text-neon-purple">
                        <i class="bi bi-shield-lock-fill me-2"></i>Secure Incident Report
                    </h3>
                    <p class="text-light opacity-75 mb-0">Your information is encrypted. This is a safe space.</p>
                </div>
                
                <div class="card-body p-4 p-lg-5">
                    <form id="reportForm" method="POST">
                        
                        <div class="mb-4">
                            <label class="form-label">Type of Incident</label>
                            <select name="incident_type" class="form-select" required>
                                <option value="" disabled selected>Choose the category...</option>
                                <option value="Physical Violence">Physical Violence</option>
                                <option value="Emotional Abuse">Emotional Abuse</option>
                                <option value="Sexual Harassment">Sexual Harassment</option>
                                <option value="Cyber Stalking">Cyber Stalking/Bullying</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Date of Occurrence</label>
                                <input type="date" name="incident_date" class="form-control" max="<?= date('Y-m-d') ?>" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label" for="region">Region in Ghana</label>
                                <select id="region" name="region" class="form-select" autocomplete="address-level1" required>
                                    <option value="" disabled selected>-- Select Region --</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label" for="area">Area in Ghana</label>
                                <select id="area" name="area" class="form-select" autocomplete="address-level2" required>
                                    <option value="" disabled selected>-- Select Area --</option>
                                </select>
                            </div>
                        </div>
                        <div id="other-area-container" class="mb-4" style="display: none;">
                            <label class="form-label" for="other_area">Specify Your Location</label>
                            <input type="text" id="other_area" name="other_area" class="form-control" placeholder="Enter your specific town or area" autocomplete="off">
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Detailed Description</label>
                            <textarea name="description" class="form-control" rows="6" placeholder="Please share as much detail as you are comfortable with..." required></textarea>
                        </div>

                        <div class="anonymous-box mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_anonymous" id="anonCheck">
                                <label class="form-check-label text-light fw-bold" for="anonCheck">
                                    Report Anonymously
                                </label>
                                <p class="form-control">Your identity will be hidden from service providers and counselors.</p>
                            </div>
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-neon btn-lg">
                                <i class="bi bi-send-fill me-2"></i>Submit Encrypted Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <p class="text-center mt-4 small opacity-50">
                <i class="bi bi-info-circle me-1"></i> For immediate danger, please use the <strong>SOS Button</strong> on your dashboard.
            </p>
        </div>
    </div>
</div>

<footer class="text-center py-4 mt-5">
    <p class="small opacity-50 mb-0">© <?= date('Y'); ?> GBVAid | Safety Through Technology</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/report_handler.js"></script>

</body>
</html>