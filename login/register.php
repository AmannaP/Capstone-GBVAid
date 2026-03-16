<?php
require_once '../settings/db_class.php';
$db = new db_conn();
$categories = $db->db_fetch_all("SELECT * FROM categories ORDER BY cat_name ASC") ?: [];
$brands = $db->db_fetch_all("SELECT * FROM brands ORDER BY brand_name ASC") ?: [];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - GBVAid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        body {
            /* Brighter deep purple base matching the rest of the app */
            background-color: #0f0a1e;
            color: #ffffff;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 20px 0;
            background-image: radial-gradient(#3c2a61 1px, transparent 1px);
            background-size: 30px 30px;
        }

        .register-container {
            margin-top: 30px;
            margin-bottom: 50px;
        }

        .card {
            background: #1a1033;
            border: 1px solid #bf40ff; /* Neon Purple Border */
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(191, 64, 255, 0.2);
        }

        .card-header {
            background-color: #1a1033;
            color: #e0aaff;
            font-weight: 800;
            border-bottom: 1px solid #3c2a61;
            padding: 1.5rem;
        }

        .form-label {
            color: #d980ff;
            font-weight: 500;
        }

        .form-label i {
            margin-right: 5px;
            color: #bf40ff;
        }

        /* Dark Mode Inputs */
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

        /* Custom Radio Buttons */
        .form-check-input {
            background-color: #0f0a1e;
            border-color: #3c2a61;
        }

        .form-check-input:checked {
            background-color: #bf40ff;
            border-color: #bf40ff;
        }

        .form-check-label {
            color: #cbd5e1;
            cursor: pointer;
        }

        .btn-custom {
            background-color: #9d4edd;
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 12px;
            border-radius: 50px;
            transition: all 0.3s;
            box-shadow: 0 4px 20px rgba(157, 78, 221, 0.4);
        }

        .btn-custom:hover {
            background-color: #bf40ff;
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(191, 64, 255, 0.6);
            color: white;
        }

        .highlight {
            color: #bf40ff;
            text-decoration: none;
            font-weight: 600;
        }

        .highlight:hover {
            color: #d980ff;
        }

        .card-footer {
            background-color: #1a1033;
            border-top: 1px solid #3c2a61;
            color: #b2adbe;
            padding: 1.2rem;
        }

        .animate-pulse-custom {
            animation: pulse-glow 2s infinite;
        }

        @keyframes pulse-glow {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); box-shadow: 0 0 20px rgba(191, 64, 255, 0.5); }
            100% { transform: scale(1); }
        }

        /* Placeholder color */
        ::placeholder {
            color: #5a4b81 !important;
            opacity: 1;
        }
    </style>
</head>

<body>
    <div class="container register-container">
        <div class="row justify-content-center animate__animated animate__fadeInDown">
            <div class="col-md-8 col-lg-6">
                <div class="card animate__animated animate__zoomIn">
                    <div class="card-header text-center">
                        <h4 class="mb-0">Create Your GBVAid Account</h4>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="register-form" id="register-form">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="name" class="form-label"><i class="fa fa-user"></i> Full Name</label>
                                    <input type="text" class="form-control animate__animated animate__fadeInUp" id="name" name="name" placeholder="John Doe" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label"><i class="fa fa-envelope"></i> Email Address</label>
                                <input type="email" class="form-control animate__animated animate__fadeInUp" id="email" name="email" placeholder="email@example.com" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label"><i class="fa fa-lock"></i> Password</label>
                                    <input type="password" class="form-control animate__animated animate__fadeInUp" id="password" name="password" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="confirm_password" class="form-label"><i class="fa fa-lock"></i> Confirm</label>
                                    <input type="password" class="form-control animate__animated animate__fadeInUp" id="confirm_password" name="confirm_password" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="country" class="form-label"><i class="fa fa-globe"></i> Country</label>
                                    <select class="form-select animate__animated animate__fadeInUp" id="country" name="country" required>
                                        <option value="" selected disabled>Select Country</option>
                                        <option value="Ghana">Ghana</option>
                                        <option value="Nigeria">Nigeria</option>
                                        <option value="Togo">Togo</option>
                                        <option value="Benin">Benin</option>
                                        <option value="Ivory Coast">Ivory Coast</option>
                                        </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="city" class="form-label"><i class="fa fa-building"></i> City</label>
                                    <input type="text" class="form-control animate__animated animate__fadeInUp" id="city" name="city" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="phone_number" class="form-label"><i class="fa fa-phone"></i> Phone Number</label>
                                <input type="text" class="form-control animate__animated animate__fadeInUp" id="phone_number" name="phone_number" pattern="[0-9]{10,15}" placeholder="024XXXXXXX" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label d-block">Register As</label>
                                <div class="d-flex p-2" style="background: #0f0a1e; border-radius: 10px; border: 1px solid #3c2a61;">
                                    <div class="form-check me-4">
                                        <input class="form-check-input" type="radio" name="role" id="victim" value="1" checked>
                                        <label class="form-check-label" for="victim">Survivor/User</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="role" id="sp" value="3">
                                        <label class="form-check-label" for="sp">Service Provider</label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Hidden Service Provider Details -->
                            <div id="sp-details" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="provider_category" class="form-label"><i class="fa fa-briefcase"></i> Service Category</label>
                                        <select class="form-select" id="provider_category" name="provider_category">
                                            <option value="" selected disabled>Select Category</option>
                                            <?php foreach($categories as $cat): ?>
                                                <option value="<?php echo $cat['cat_id']; ?>"><?php echo htmlspecialchars($cat['cat_name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="provider_brand" class="form-label"><i class="fa fa-hospital"></i> Organization/Brand</label>
                                        <!-- // The brand dropdown will be dynamically populated based on the selected category using JavaScript -->
                                        <select class="form-select" id="provider_brand" name="provider_brand">
                                            <option value="" selected disabled>Select Organization</option> 
                                        </select>

                                    </div>
                                <div class="mb-3" style="color: #d980ff;">
                                    <h4>If brand or category is not found please email admin@gbvaid.org to create it.</h4>
                                </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-custom w-100 animate-pulse-custom">Create Account</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        Already have an account? <a href="login.php" class="highlight">Login here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/register.js"></script>
</body>

</html>