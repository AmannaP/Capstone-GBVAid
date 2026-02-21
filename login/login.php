<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - GBVAid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        body {
            /* Brighter deep purple base matching index.php */
            background-color: #0f0a1e;
            color: #ffffff;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            /* Subtle grid overlay to match previous styling but in dark mode */
            background-image: radial-gradient(#3c2a61 1px, transparent 1px);
            background-size: 30px 30px;
        }

        .login-container {
            width: 100%;
            max-width: 500px;
            padding: 15px;
        }

        .card {
            background: #1a1033; /* Slightly lighter purple-black */
            border: 1px solid #bf40ff; /* Neon Purple Border */
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(191, 64, 255, 0.2);
        }

        .card-header {
            background-color: #1a1033;
            color: #e0aaff; /* Vivid Lavender */
            font-weight: 800;
            border-bottom: 1px solid #3c2a61;
            padding: 1.5rem;
        }

        .form-label {
            color: #d980ff; /* Bright Purple Labels */
            font-weight: 500;
        }

        .form-label i {
            margin-right: 5px;
            color: #bf40ff;
        }

        /* Styling Form Inputs for Dark Theme */
        .form-control {
            background-color: #0f0a1e;
            border: 1px solid #3c2a61;
            color: #fff;
            padding: 0.75rem;
            border-radius: 10px;
        }

        .form-control:focus {
            background-color: #150d2b;
            border-color: #bf40ff;
            color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(191, 64, 255, 0.25);
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
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(191, 64, 255, 0.6);
        }

        .highlight {
            color: #bf40ff;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
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
            50% { transform: scale(1.02); box-shadow: 0 0 20px rgba(191, 64, 255, 0.6); }
            100% { transform: scale(1); }
        }
    </style>
</head>

<body>
    <div class="container login-container">
        <div class="row justify-content-center">
            <div class="col-12 animate__animated animate__fadeInDown">
                <div class="card animate__animated animate__zoomIn">
                    <div class="card-header text-center">
                        <h4 class="mb-0">GBVAid Login</h4>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="login.js" id="login-form">
                            <div class="mb-3">
                                <label for="email" class="form-label"><i class="fa fa-envelope"></i> Email Address</label>
                                <input type="email" class="form-control animate__animated animate__fadeInUp" id="email" name="email" placeholder="name@example.com" required>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label"><i class="fa fa-lock"></i> Password</label>
                                <input type="password" class="form-control animate__animated animate__fadeInUp" id="password" name="password" placeholder="Enter your password" required>
                            </div>
                            <button type="submit" class="btn btn-custom w-100 animate-pulse-custom">Login to Platform</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        Don't have an account? <a href="register.php" class="highlight">Register here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/login.js"></script>
</body>

</html>