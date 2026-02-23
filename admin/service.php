<?php
require_once '../settings/core.php';
require_once '../controllers/category_controller.php';
require_once '../controllers/brand_controller.php';
require_once '../controllers/service_controller.php';

// Only admin access
if (!checkLogin() || !isAdmin()) {
    header("Location: ../login/login.php");
    exit;
}

$categories = fetch_categories_ctr(); 
$brands = fetch_brands_ctr(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Services | GBVAid Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #0f0a1e;
            background: radial-gradient(circle at center, #1a1033 0%, #0f0a1e 100%);
            color: #e0aaff;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
        }

        /* Admin Navbar - Dark Glassmorphism */
        .navbar-admin {
            background: rgba(26, 16, 51, 0.8) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(157, 78, 221, 0.2);
            padding: 15px 0;
        }

        .navbar-brand {
            font-weight: 700;
            color: #fff !important;
            text-shadow: 0 0 10px rgba(157, 78, 221, 0.5);
        }

        /* Content Card - Glassmorphism */
        .content-card {
            border: 1px solid rgba(138, 43, 226, 0.2);
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            margin-bottom: 30px;
            overflow: hidden;
        }

        .card-header-custom {
            background: rgba(157, 78, 221, 0.1);
            border-bottom: 1px solid rgba(157, 78, 221, 0.2);
            padding: 20px 30px;
            color: #fff;
        }

        /* Form Controls - Darkened */
        .form-label {
            color: #b79ced;
        }

        .form-control, .form-select, .input-group-text {
            background-color: rgba(15, 10, 30, 0.5) !important;
            border: 1px solid rgba(157, 78, 221, 0.3);
            color: #fff !important;
            border-radius: 10px;
        }

        .form-control:focus, .form-select:focus {
            background-color: rgba(15, 10, 30, 0.8) !important;
            border-color: #c453ea;
            box-shadow: 0 0 15px rgba(196, 83, 234, 0.3);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        /* Table - Sleek Dark */
        .table {
            color: #e0aaff !important;
        }

        .table thead th {
            background-color: rgba(157, 78, 221, 0.2);
            color: #fff;
            border-bottom: 1px solid rgba(157, 78, 221, 0.3);
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
        }

        .table tbody tr {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            transition: background 0.3s;
        }

        .table tbody tr:hover {
            background: rgba(157, 78, 221, 0.05) !important;
        }

        /* Buttons */
        .btn-purple {
            background: linear-gradient(135deg, #9d4edd 0%, #c453ea 100%);
            border: none;
            color: white;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(157, 78, 221, 0.4);
            transition: all 0.3s;
        }

        .btn-purple:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(196, 83, 234, 0.6);
            color: #fff;
        }

        .btn-logout {
            background: transparent;
            color: #ff4d4d;
            border: 1px solid #ff4d4d;
            border-radius: 50px;
            padding: 5px 20px;
            transition: all 0.3s;
        }

        .btn-logout:hover {
            background: #ff4d4d;
            color: #fff;
        }

        .service-thumb {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid rgba(157, 78, 221, 0.3);
        }

        .text-neon {
            color: #c453ea;
            text-shadow: 0 0 10px rgba(196, 83, 234, 0.5);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-admin navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">
                <i class="bi bi-shield-lock-fill me-2 text-neon"></i>GBVAid Admin
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item mx-2"><a href="../admin/dashboard.php" class="nav-link">Dashboard</a></li>
                    <li class="nav-item mx-2"><a href="../admin/brand.php" class="nav-link">Brands</a></li>
                    <li class="nav-item mx-2"><a href="../admin/category.php" class="nav-link">Categories</a></li>
                    <li class="nav-item ms-4">
                        <a href="../login/logout.php" class="btn-logout">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        
        <div class="text-center mb-5">
            <h2 class="fw-bold text-neon">Service Management</h2>
            <p class="text-white-50">Manage professional support resources and safety toolkits.</p>
        </div>

        <div class="content-card">
            <div class="card-header-custom">
                <h5 class="fw-bold mb-0"><i class="bi bi-plus-square me-2 text-neon"></i>Configure Service</h5>
            </div>
            <div class="card-body">
                <form id="service-form" enctype="multipart/form-data">
                    <input type="hidden" id="service_id" name="service_id" value="">
                    
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label fw-bold small uppercase">Category</label>
                            <select id="cat_id" name="cat_id" class="form-select" required>
                                <option value="">-- Select Category --</option>
                                <?php foreach ($categories as $c): ?>
                                    <option value="<?= htmlspecialchars($c['cat_id']) ?>"><?= htmlspecialchars($c['cat_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Brand</label>
                            <select id="brand_id" name="brand_id" class="form-select" required>
                                <option value="">-- Select Brand --</option>
                                <?php foreach ($brands as $b): ?>
                                    <option value="<?= htmlspecialchars($b['brand_id']) ?>"><?= htmlspecialchars($b['brand_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Service Title</label>
                            <input type="text" id="service_title" name="service_title" class="form-control" placeholder="e.g. Legal Counseling" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Price (GHS)</label>
                            <div class="input-group">
                                <span class="input-group-text border-end-0">GH₵</span>
                                <input type="number" id="service_price" step="0.01" min="0" name="service_price" class="form-control" placeholder="0.00" required>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-bold small">Search Keywords</label>
                            <input type="text" id="service_keywords" name="service_keywords" class="form-control" placeholder="counseling, legal, emergency">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold small">Full Description</label>
                            <textarea id="service_desc" name="service_desc" class="form-control" rows="3" placeholder="Detailed information about the service..."></textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold small">Service Header Image</label>
                            <input type="file" id="service_image" name="service_image" class="form-control" accept="image/*">
                        </div>
                    </div>        
                    
                    <div class="mt-5 text-end border-top border-secondary pt-4">
                        <button type="button" id="reset-form" class="btn btn-outline-secondary px-4 me-2" style="border-radius: 10px;">Clear</button>
                        <button type="submit" id="save-service" class="btn btn-purple px-5">
                            <i class="bi bi-cloud-arrow-up-fill me-2"></i>Publish Service
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="content-card">
            <div class="card-header-custom">
                <h5 class="fw-bold mb-0"><i class="bi bi-list-ul me-2 text-neon"></i>Active Inventory</h5>
            </div>
            <div class="p-0">
                <div class="table-responsive">
                    <table class="table mb-0" id="service-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Preview</th>
                                <th>Service Title</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th>Price</th>
                                <th class="text-center">Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center text-white-50 mt-5 mb-4">
        <small>© <?= date('Y'); ?> GBVAid Platform. Admin Portal.</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/service.js"></script>
</body>
</html>