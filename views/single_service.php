<?php
// views/single_service.php
require_once '../settings/core.php';
require_once '../controllers/service_controller.php';
require_once '../controllers/cart_controller.php';

// Get service ID from URL
$service_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch service details
$service = view_single_service_ctr($service_id);

if (!$service) {
    echo "<h3 class='text-center text-danger mt-5'>service not found.</h3>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($service['service_title']) ?> | GBVAid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
    
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .service-img {
            width: 100%;
            border-radius: 10px;
            max-height: 450px;
            object-fit: cover;
            
        }
        .card {
            border-radius: 10px;
        }
        .btn-primary {
            background-color: #c453eaff;
            border-color: #c453eaff;
        }
        .btn-primary:hover {
            background-color: #e598ffff;
            border-color: #e598ffff;
        }
        .service-label {
            font-weight: bold;
            color: #555;
        }

    </style>
</head>
<body>
    <!-- In single_service.php, right after <body> tag -->
<?php include '../views/navbar.php'; ?>

<!-- Rest of your content -->

<div class="container mt-5 mb-5">
    <a href="../user/service_page.php" class="btn btn-secondary mb-4">&larr; Back</a>

    <div class="card shadow p-4 align-items-center">
        <div class="row">
            <!-- service Image -->
            <div class="col-md-6">
                <img src="../uploads/services/<?= htmlspecialchars($service['service_image'] ?: '../uploads/services/default.jpg') ?>" 
                     alt="<?= htmlspecialchars($service['service_title']) ?>" 
                     class="service-img shadow-sm">
            </div>

            <!-- service Details -->
            <div class="col-md-6">
                <h3 class="fw-bold mb-3"><?= htmlspecialchars($service['service_title']) ?></h3>

                <p><span class="service-label">service ID:</span> <?= htmlspecialchars($service['service_id']) ?></p>
                <p><span class="service-label">Category:</span> <?= htmlspecialchars($service['cat_name'] ?? 'N/A') ?></p>
                <p><span class="service-label">Brand:</span> <?= htmlspecialchars($service['brand_name'] ?? 'N/A') ?></p>
                
                <h4 class="text-success mb-4">Price: GHS <?= number_format($service['service_price'], 2) ?></h4>
                
                <p><span class="service-label">Description:</span><br>
                    <?= nl2br(htmlspecialchars($service['service_desc'])) ?>
                </p>

                <?php if (!empty($service['service_keywords'])): ?>
                    <p><span class="service-label">Keywords:</span> 
                        <?= htmlspecialchars($service['service_keywords']) ?>
                    </p>
                <?php endif; ?>

                <!-- Make the add to cart button to be functional. -->
                <button class="btn btn-primary add-to-cart-btn"
                    data-id="<?php echo $service['service_id']; ?>"
                    data-title="<?php echo htmlspecialchars($service['service_title']); ?>"
                    data-price="<?php echo $service['service_price']; ?>"
                    data-image="<?php echo htmlspecialchars($service['service_image']); ?>"
                    >Book Session
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Floating Cart Button -->
<a href="../views/cart.php" class="btn btn-primary position-fixed bottom-0 end-0 m-4 shadow-lg" 
   style="z-index: 1000; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
    <i class="bi bi-cart-fill fs-4"></i>
    <?php
    $victim_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
    $ip_add = $_SERVER['REMOTE_ADDR'];
    $cart_items = get_user_cart_ctr($victim_id ?? $ip_add);
    $cart_count = count($cart_items);
    
    if ($cart_count > 0):
    ?>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            <?= $cart_count ?>
            <span class="visually-hidden">items in cart</span>
        </span>
    <?php endif; ?>
</a>
<footer class="text-center text-muted mt-4 mb-3">
    <small>© <?= date('Y'); ?> GBVAid — Empowering safety and access.</small>
</footer>
    <script src="../js/cart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
