<?php
require_once '../settings/core.php';
require_once '../controllers/category_controller.php';

// Restrict access to admins only
if (!checkLogin()) {
    header("Location: ../login/login.php");
    exit();
}
if (!isAdmin()) {
    header("Location: ../login/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage GBV Services | GBVAid Admin</title>
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

        .navbar-admin {
            background: rgba(26, 16, 51, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #3c2a61;
            padding: 15px 0;
        }

        .navbar-brand { font-weight: 800; color: #e0aaff !important; font-size: 1.5rem; }
        .nav-link { color: rgba(255, 255, 255, 0.8) !important; font-weight: 500; transition: all 0.3s; }
        .nav-link:hover, .nav-link.active { color: #d980ff !important; }

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
        .btn-logout:hover { background-color: #bf40ff; color: white; }

        .page-title { color: #e0aaff; font-weight: 800; margin-bottom: 10px; }

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

        .card-body { padding: 30px; }

        .form-control {
            background-color: #0f0a1e;
            border: 1px solid #3c2a61;
            color: #fff;
            border-radius: 8px;
        }
        .form-control:focus {
            border-color: #bf40ff;
            box-shadow: 0 0 0 4px rgba(191, 64, 255, 0.2);
            background-color: #150d2b;
            color: #fff;
        }
        .form-control::placeholder { color: #6c4898; opacity: 1; }

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

        .btn-purple {
            background: linear-gradient(135deg, #9d4edd 0%, #bf40ff 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 10px 25px;
            border-radius: 50px;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(191, 64, 255, 0.3);
        }
        .btn-purple:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(191, 64, 255, 0.5); color: white; }

        .footer { margin-top: 50px; text-align: center; font-size: 0.9em; color: #e0aaff; padding-bottom: 30px; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-admin navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php"><i class="bi bi-shield-lock-fill me-2"></i>GBVAid Admin</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item mx-2"><a href="../admin/dashboard.php" class="nav-link">Dashboard</a></li>
                    <li class="nav-item mx-2"><a href="../admin/brand.php" class="nav-link">Brands</a></li>
                    <li class="nav-item mx-2"><a href="../admin/category.php" class="nav-link active">Categories</a></li>
                    <li class="nav-item ms-4">
                        <a href="../login/logout.php" class="btn-logout">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <div class="text-center mb-5">
                    <h2 class="page-title">Manage Service Categories</h2>
                    <p style="color: #c8a8e9; max-width: 700px; margin: 0 auto;">
                        Create and organize support categories like <em>Legal Aid</em>, <em>Counseling</em>, <em>Medical Support</em>, and <em>Safe Shelters</em>.
                    </p>
                </div>

                <div class="content-card">
                    <div class="card-header-custom">
                        <h5 class="fw-bold mb-0" style="color: #e0aaff;"><i class="bi bi-folder-plus me-2"></i>Add New Category</h5>
                    </div>
                    <div class="card-body">
                        <form id="add-category-form">
                            <div class="input-group input-group-lg">
                                <input type="text" id="cat_name" name="cat_name" class="form-control" placeholder="Enter new category name..." required>
                                <button type="submit" class="btn btn-purple">
                                    <i class="bi bi-plus-lg me-2"></i>Add Category
                                </button>
                            </div>
                            <small style="color: #8a68b0;" class="ms-1 mt-2 d-block">Tip: Keep names concise (e.g., "Legal Aid", not "We provide legal help")</small>
                        </form>
                    </div>
                </div>

                <div class="content-card">
                    <div class="card-header-custom d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0" style="color: #e0aaff;"><i class="bi bi-list-task me-2"></i>Existing Categories</h5>
                    </div>
                    <div class="p-0">
                        <table class="table table-striped mb-0" id="category-table">
                            <thead>
                                <tr>
                                    <th width="10%" class="text-center">ID</th>
                                    <th>Service Category Name</th>
                                    <th width="20%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="footer">
        <p>GBVAid © <?= date('Y') ?> — Empowering communities. Protecting lives.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/category.js"></script>
</body>
</html>