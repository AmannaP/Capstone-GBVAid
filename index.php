<?php
// index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GBVAid | GBV Support Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #0f0a1e; /* Brighter deep purple base */
            color: #ffffff;
            /* Star-like background dots */
            background-image: radial-gradient(#3c2a61 1px, transparent 1px);
            background-size: 30px 30px;
            background-attachment: fixed;
        }

        /* Modern Navbar */
        .navbar {
            background-color: rgba(15, 10, 30, 0.9);
            backdrop-filter: blur(10px);
            padding: 1rem 0;
            border-bottom: 1px solid #bf40ff; /* Bright Neon Border */
        }
        .navbar-brand {
            color: #d980ff !important;
            font-weight: 800;
            letter-spacing: 1px;
        }
        .nav-link {
            color: #f0d9ff !important;
            font-weight: 500;
        }

        /* Hero Section */
        .hero {
            padding: 120px 0;
            /* Semi-transparent gradient to let the stars peek through */
            background: radial-gradient(circle at top right, rgba(90, 24, 154, 0.8), rgba(15, 10, 30, 0.4));
            border-bottom: 2px solid #bf40ff;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            background: linear-gradient(to bottom, #ffffff 20%, #e0aaff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            color: #d980ff;
            font-size: 1.2rem;
            max-width: 700px;
            margin: 20px auto;
        }

        /* Buttons - Electric Purple */
        .btn-custom {
            background-color: #9d4edd;
            color: white;
            border-radius: 50px;
            padding: 12px 35px;
            font-weight: 600;
            box-shadow: 0 4px 20px rgba(157, 78, 221, 0.5);
            transition: 0.3s;
            border: none;
        }
        .btn-custom:hover {
            background-color: #bf40ff;
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 6px 25px rgba(191, 64, 255, 0.6);
        }

        /* Features Section */
        .feature-card {
            background: rgba(26, 16, 51, 0.9); /* Slight transparency for stars */
            border: 1px solid #3c2a61;
            border-radius: 20px;
            padding: 30px;
            height: 100%;
            transition: 0.4s;
            backdrop-filter: blur(5px);
        }
        .feature-card:hover {
            border-color: #bf40ff;
            background: rgba(36, 20, 69, 0.95);
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(191, 64, 255, 0.15);
        }

        .icon-box {
            font-size: 2.5rem;
            margin-bottom: 15px;
            display: block;
        }

        .feature-title {
            color: #e0aaff;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .feature-text {
            color: #cbd5e1;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        footer {
            background: rgba(10, 7, 20, 0.95);
            padding: 60px 0;
            border-top: 1px solid #3c2a61;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">GBVAid</a>
            <div class="ms-auto">
                <a href="login/login.php" class="nav-link d-inline-block me-3">Login</a>
                <a href="login/register.php" class="btn btn-custom btn-sm">Sign Up</a>
            </div>
        </div>
    </nav>

    <header class="hero text-center">
        <div class="container">
            <h1 class="fw-bold mb-4">GBVAid: Your Digital Ally Against Gender-Based Violence</h1>
            <p>A secure digital gateway connecting you to essential medical, legal, and counseling services across Africa.</p>
            <div class="mt-4">
                <a href="login/register.php" class="btn btn-custom btn-lg me-2">Join the Platform</a>
                <a href="login/login.php" class="btn btn-outline-light btn-lg" style="border-radius: 50px;">Login</a>
            </div>
        </div>
    </header>

    <section id="services" class="container py-5">
        <h2 class="text-center fw-bold mb-5" style="color: #e0aaff;">What We Offer</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <span class="icon-box">🩺</span>
                    <h4 class="feature-title">Access to Services</h4>
                    <p class="feature-text">Find verified healthcare, legal, and counseling support near you. We ensure confidentiality and compassion at every step.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <span class="icon-box">🕊️</span>
                    <h4 class="feature-title">Safe Reporting</h4>
                    <p class="feature-text">Report GBV incidents securely and anonymously. Our trusted partners respond promptly with the help you need.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <span class="icon-box">🤝</span>
                    <h4 class="feature-title">Community Empowerment</h4>
                    <p class="feature-text">Join awareness programs, advocacy campaigns, and survivor-led initiatives for lasting social change.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <span class="icon-box">📞</span>
                    <h4 class="feature-title">24/7 Support Lines</h4>
                    <p class="feature-text">Connect instantly with trained responders who provide real-time guidance and care, any time you need it.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <span class="icon-box">💡</span>
                    <h4 class="feature-title">Education & Resources</h4>
                    <p class="feature-text">Access articles, guides, and training materials to understand, prevent, and respond to GBV effectively.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <span class="icon-box">🔐</span>
                    <h4 class="feature-title">Data Privacy</h4>
                    <p class="feature-text">Your safety matters. All interactions and reports are encrypted and stored securely to protect your identity.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="text-center">
        <div class="container">
            <p class="mb-2">© <?= date('Y'); ?> <span style="color: #bf40ff; font-weight: bold;">GBVAid</span> | All Rights Reserved.</p>
            <p class="small text-muted">Built in Ghana to empower GBV survivors, advocates, and communities through technology.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>