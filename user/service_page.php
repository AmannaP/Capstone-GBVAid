<?php
// user/service_page.php

require_once '../settings/core.php';
require_once '../controllers/service_controller.php';
require_once '../controllers/category_controller.php';
require_once '../controllers/brand_controller.php';

// Fetch filters
$categories = fetch_categories_ctr();
$brands = fetch_brands_ctr();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Services | GBVAid</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
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
        
        /* Service Card Styling - Glassmorphism */
        .service-card {
            border: 1px solid #3c2a61;
            border-radius: 20px;
            background: rgba(26, 16, 51, 0.9);
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s;
            height: 100%;
            overflow: hidden;
        }

        .service-card:hover {
            transform: translateY(-8px);
            border-color: #bf40ff;
            box-shadow: 0 12px 25px rgba(191, 64, 255, 0.3);
        }

        /* BRIGHT SERVICE TITLE */
        .service-title {
            color: #ffff !important; /* Brighter almost-white lavender */
            font-weight: 800;
            font-size: 1.3rem;
            margin-bottom: 0.75rem;
            text-shadow: 0 0 10px rgba(191, 64, 255, 0.5); /* Subtle neon glow */
        }

        .service-card img {
            border-bottom: 1px solid #3c2a61;
            height: 200px;
            object-fit: cover;
        }
        
        h2, h4 {
            color: #e0aaff; /* Vivid Lavender */
            font-weight: 700;
        }

        .text-muted {
            color: #cbd5e1 !important; /* Lighter grey for better readability */
        }
        
        .form-select, .form-control {
            background-color: #1a1033;
            border: 1px solid #3c2a61;
            color: #fff;
            border-radius: 10px;
        }

        .form-select:focus, .form-control:focus {
            background-color: #241445;
            border-color: #bf40ff;
            color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(191, 64, 255, 0.25);
        }

        .form-label {
            color: #d980ff;
        }
        
        .btn-custom {
            background-color: #9d4edd;
            color: white;
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(157, 78, 221, 0.3);
            transition: 0.3s;
        }

        .btn-custom:hover {
            background-color: #bf40ff;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(191, 64, 255, 0.5);
        }
        
        footer {
            margin-top: 60px;
            padding: 40px 0;
            border-top: 1px solid #3c2a61;
            background: rgba(10, 7, 20, 0.8);
        }

        .text-purple {
            color: #bf40ff !important;
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

<div class="container mt-5">
  <h2 class="fw-bold text-center mb-3">Browse Support Services</h2>
  <p class="text-center text-muted mb-5">
      Explore available medical, legal, and counseling services. Filter by category or provider.
  </p>

  <div class="row mb-4 align-items-end g-3">
      <div class="col-md-4">
          <label for="category_filter" class="form-label fw-bold">Filter by Category</label>
          <select id="category_filter" class="form-select">
              <option value="">All Categories</option>
              <?php foreach ($categories as $cat): ?>
                  <option value="<?= $cat['cat_id'] ?>"><?= htmlspecialchars($cat['cat_name']) ?></option>
              <?php endforeach; ?>
          </select>
      </div>

      <div class="col-md-4">
          <label for="brand_filter" class="form-label fw-bold">Filter by Provider</label>
          <select id="brand_filter" class="form-select">
              <option value="">All Providers</option>
              <?php foreach ($brands as $brand): ?>
                  <option value="<?= $brand['brand_id'] ?>"><?= htmlspecialchars($brand['brand_name']) ?></option>
              <?php endforeach; ?>
          </select>
      </div>

      <div class="col-md-4">
          <label for="search_box" class="form-label fw-bold">Search Service</label>
          <div class="input-group">
              <input type="text" id="search_box" class="form-control" placeholder="Search services...">
              <button class="btn btn-custom" id="search_btn">
                  <i class="bi bi-search"></i> Search
              </button>
          </div>
      </div>
  </div>

  <h4 class="mb-4 border-bottom border-secondary pb-2">Available Support</h4>
  
  <div class="row g-4" id="service-list">
      <div class="text-center py-5">
          <div class="spinner-border text-purple" role="status"></div>
          <p class="mt-2 text-muted">Loading available support...</p>
      </div>
  </div>

  <div class="pagination-container text-center mt-5">
      <div id="pagination" class="pagination-buttons d-flex justify-content-center gap-2"></div>
  </div>
</div>

<footer class="text-center">
  <div class="container">
      <p class="mb-1 fw-semibold">© <?= date('Y'); ?> <span style="color: #bf40ff;">GBVAid</span></p>
      <p class="small text-muted">Empowering safety and support for all.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/service.js"></script>
</body>
</html>