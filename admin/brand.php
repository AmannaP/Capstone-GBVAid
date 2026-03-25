<?php
require_once '../settings/core.php';
require_once '../controllers/category_controller.php';
require_once '../controllers/brand_controller.php';

// Restrict access to admins
if (!checkLogin()) {
    header("Location: ../login/login.php");
    exit();
}
if (!isAdmin()) {
    header("Location: ../login/login.php");
    exit();
}

// Fetch all categories for the dropdown
$categories = fetch_categories_ctr();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Brands | GBVAid Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #0f0a1e;
            background-image: radial-gradient(#3c2a61 1px, transparent 1px);
            background-size: 30px 30px;
            font-family: 'Poppins', sans-serif;
            color: #ffffff;
        }

        /* Admin Navbar */
        .navbar-admin {
            background: rgba(26, 16, 51, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #3c2a61;
            padding: 15px 0;
        }

        .navbar-brand {
            font-weight: 800;
            color: #e0aaff !important;
            font-size: 1.5rem;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            font-weight: 500;
            transition: all 0.3s;
        }
        .nav-link:hover, .nav-link.active {
            color: #d980ff !important;
        }
        .bg-purple-outline {
            background: rgba(191, 64, 255, 0.1);
            border: 1px solid rgba(191, 64, 255, 0.4);
            color: #e0aaff;
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 0.8rem;
        }

        .btn-logout {
            background-color: transparent;
            color: #e0aaff;
            border: 2px solid #bf40ff;
            border-radius: 50px;
            padding: 5px 20px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
        }
        .btn-logout:hover {
            background-color: #bf40ff;
            color: white;
        }

        /* Cards */
        .content-card {
            border: 1px solid #3c2a61;
            border-radius: 15px;
            background: rgba(26, 16, 51, 0.9);
            box-shadow: 0 4px 20px rgba(191, 64, 255, 0.1);
            margin-bottom: 30px;
            overflow: hidden;
        }

        .card-header-custom {
            background-color: rgba(191, 64, 255, 0.1);
            border-bottom: 1px solid #3c2a61;
            padding: 20px 30px;
            color: #e0aaff;
        }

        .card-body {
            padding: 30px;
        }

        /* Form Controls */
        .form-control, .form-select {
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #3c2a61;
            background-color: #0f0a1e;
            color: #fff;
        }
        .form-control:focus, .form-select:focus {
            border-color: #bf40ff;
            box-shadow: 0 0 0 4px rgba(191, 64, 255, 0.2);
            background-color: #150d2b;
            color: #fff;
        }
        .form-control::placeholder, .form-select::placeholder { color: #6c4898; opacity: 1; }
        .form-label { color: #d980ff; font-weight: 500; }
        select option { background-color: #1a1033; }

        /* Table Styling */
        .table thead th {
            background: rgba(157, 78, 221, 0.3);
            color: #e0aaff;
            font-weight: 600;
            border: none;
            padding: 15px;
        }
        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            color: #ffffff;
            border-color: #3c2a61;
        }
        .table tbody tr { background: transparent; }
        .table tbody tr:hover { background: rgba(191, 64, 255, 0.07); }
        /* Override Bootstrap 5 striped table variable */
        .table-striped > tbody > tr:nth-of-type(odd) > * {
            --bs-table-color: #ffffff;
            --bs-table-bg: rgba(60, 42, 97, 0.2);
            --bs-table-striped-color: #ffffff;
            --bs-table-striped-bg: rgba(60, 42, 97, 0.2);
            background-color: rgba(60, 42, 97, 0.2);
            color: #ffffff;
        }
        .table-striped > tbody > tr:nth-of-type(even) > * {
            --bs-table-color: #ffffff;
            --bs-table-bg: transparent;
            background-color: transparent;
            color: #ffffff;
        }

        /* Buttons */
        .btn-purple {
            background: linear-gradient(135deg, #9d4edd 0%, #bf40ff 100%);
            border: none;
            border-radius: 50px;
            padding: 10px 30px;
            font-weight: 600;
            color: white;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(191, 64, 255, 0.3);
        }
        .btn-purple:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(191, 64, 255, 0.5);
            color: white;
        }

        .btn-action {
            border-radius: 50px;
            padding: 5px 15px;
        }

        .page-title { color: #e0aaff; font-weight: 800; }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-admin navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php"><i class="bi bi-shield-lock-fill me-2"></i>GBVAid Admin</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item mx-2"><a href="../admin/dashboard.php" class="nav-link">Dashboard</a></li>
                    <li class="nav-item mx-2"><a href="../admin/brand.php" class="nav-link active">Brands</a></li>
                    <li class="nav-item mx-2"><a href="../admin/category.php" class="nav-link">Categories</a></li>
                    <li class="nav-item ms-4">
                        <a href="../login/logout.php" class="btn-logout">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <!-- Page Header -->
                <div class="text-center mb-5">
                    <h2 class="fw-bold page-title">Brand Management</h2>
                    <p style="color: #c8a8e9;">Create and manage service providers or sub-categories.</p>
                </div>

                <!-- CREATE FORM -->
                <div class="content-card">
                    <div class="card-header-custom">
                        <h5 class="fw-bold mb-0"><i class="bi bi-plus-circle me-2"></i>Add New Brand</h5>
                    </div>
                    <div class="card-body">
                        <form id="add-brand-form">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="brand_name" class="form-label fw-bold small" style="color: #d980ff;">Brand Name</label>
                                    <input type="text" id="brand_name" name="brand_name" class="form-control" placeholder="e.g., Trauma Therapy, Legal Consult" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="category_id" class="form-label fw-bold small" style="color: #d980ff;">Select Category</label>
                                    <select id="category_id" name="category_id" class="form-select" required>
                                        <option value="">-- Select Category --</option>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= htmlspecialchars($cat['cat_id']); ?>">
                                                <?= htmlspecialchars($cat['cat_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-12 text-end mt-4">
                                    <button type="submit" class="btn btn-purple px-5">
                                        <i class="bi bi-save me-2"></i>Save Brand
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- DATA TABLE -->
                <div class="content-card">
                    <div class="card-header-custom">
                        <h5 class="fw-bold mb-0"><i class="bi bi-list-ul me-2"></i>Existing Brands</h5>
                    </div>
                    <div class="p-0"> <!-- Removed padding for flush table -->
                        <table class="table table-striped mb-0" id="brand-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Brand Name</th>
                                    <th>Category</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Brands will be dynamically loaded by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/brand.js"></script>
</body>
</html>