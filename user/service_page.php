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
            background: rgba(26, 16, 51, 0.95); /* Slightly darker for contrast */
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s;
            height: 100%;
            overflow: hidden;
        }
        .service-card .card-body {
            padding: 1.5rem;
        }

        .service-card:hover {
            transform: translateY(-8px);
            border-color: #bf40ff;
            box-shadow: 0 12px 30px rgba(191, 64, 255, 0.4);
        }

        /* BRIGHT SERVICE TITLE - UPDATED TO PURE WHITE */
        .service-title {
            color: #ffffff !important; /* Pure white for maximum visibility */
            font-weight: 800;
            font-size: 1.25rem;
            margin-bottom: 0.75rem;
            line-height: 1.3;
            /* Allow the title to wrap to 2 lines, then truncate if even longer */
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            /* Stronger text shadow for visibility against any background */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7); 
        }

        .service-card img {
            border-bottom: 1px solid #3c2a61;
            height: 200px;
            width: 100%;
            object-fit: cover;
        }
        
        h2, h4 {
            color: #ffffff; /* Set section headers to white */
            font-weight: 700;
            text-shadow: 0 0 10px rgba(191, 64, 255, 0.3);
        }

        /* Secondary text within the card */
        .service-card .text-muted {
            color: #e2e8f0 !important; /* Brighter secondary text */
        }
        
        .form-select, .form-control {
            background-color: #1a1033;
            border: 1px solid #3c2a61;
            color: #fff;
            border-radius: 12px;
        }

        .form-control::placeholder {
            color: #6b7280 !important; /* Brighter placeholder */
            opacity: 0.8;
        }

        .form-select:focus, .form-control:focus {
            background-color: #241445;
            border-color: #bf40ff;
            color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(191, 64, 255, 0.25);
        }

        .form-label {
            color: #e0aaff; /* Lavender for labels */
            font-weight: 600;
        }
        
        .btn-custom {
            background-color: #9d4edd;
            color: white;
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 700;
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
            background: rgba(10, 7, 20, 0.9);
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
  <p class="text-center text-muted mb-5" style="color: #cbd5e1 !important;">
      Explore available medical, legal, and counseling services. Filter by category or provider.
  </p>

  <div class="row mb-5 align-items-end g-3">
      <div class="col-md-4">
          <label for="category_filter" class="form-label">Filter by Category</label>
          <select id="category_filter" class="form-select">
              <option value="">All Categories</option>
              <?php foreach ($categories as $cat): ?>
                  <option value="<?= $cat['cat_id'] ?>"><?= htmlspecialchars($cat['cat_name']) ?></option>
              <?php endforeach; ?>
          </select>
      </div>

      <div class="col-md-4">
          <label for="brand_filter" class="form-label">Filter by Provider</label>
          <select id="brand_filter" class="form-select">
              <option value="">All Providers</option>
              <?php foreach ($brands as $brand): ?>
                  <option value="<?= $brand['brand_id'] ?>"><?= htmlspecialchars($brand['brand_name']) ?></option>
              <?php endforeach; ?>
          </select>
      </div>

      <div class="col-md-4">
          <label for="search_box" class="form-label">Search Service</label>
          <div class="input-group">
              <input type="text" id="search_box" class="form-control" placeholder="Search services...">
              <button class="btn btn-custom" id="search_btn">
                  <i class="bi bi-search"></i>
              </button>
          </div>
      </div>
  </div>

  <h4 class="mb-4 border-bottom border-secondary pb-3">Available Support</h4>
  
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
      <p class="small text-muted" style="color: #cbd5e1 !important;">Empowering safety and support for all.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/service.js"></script>
</body>
</html>