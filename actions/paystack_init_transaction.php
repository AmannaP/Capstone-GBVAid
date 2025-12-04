<?php
// actions/paystack_init_transaction.php
header('Content-Type: application/json');

// Include core and Paystack configuration
require_once '../settings/core.php';
require_once '../settings/paystack_config.php';
require_once '../controllers/cart_controller.php'; // Required to fetch cart

error_log("=== PAYSTACK INITIALIZE TRANSACTION ===");

// Check if user is logged in
if (!checkLogin()) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Please login to complete payment'
    ]);
    exit();
}

// Get Customer ID
$customer_id = getUserId();
$input = json_decode(file_get_contents('php://input'), true);
$customer_email = isset($input['email']) ? trim($input['email']) : '';

// Validate email
if (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid email address'
    ]);
    exit();
}

// === RECALCULATE AMOUNT SERVER-SIDE (SECURITY) ===
// 1. Fetch items
$cart_items = get_user_cart_ctr($customer_id);

if (!$cart_items || count($cart_items) == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Cart is empty']);
    exit();
}

// 2. Calculate Subtotal
$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += ($item['product_price'] * $item['qty']);
}

// 3. Apply Tax and Service Fee
$tax = 5.00;
$service_fee = $subtotal * 0.015;
$amount_to_charge = $subtotal + $tax + $service_fee;

// Ensure amount is valid
if ($amount_to_charge <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid amount']);
    exit();
}

try {
    // Generate unique reference
    $reference = 'GBVAid-' . $customer_id . '-' . time();
    
    error_log("Initializing transaction - Customer: $customer_id, Amount: $amount_to_charge GHS, Email: $customer_email");
    
    // Initialize Paystack transaction
    $paystack_response = paystack_initialize_transaction($amount_to_charge, $customer_email, $reference);
    
    if (!$paystack_response) {
        throw new Exception("No response from Paystack API");
    }
    
    if (isset($paystack_response['status']) && $paystack_response['status'] === true) {
        // Store transaction reference in session for verification later
        $_SESSION['paystack_ref'] = $reference;
        $_SESSION['paystack_amount'] = $amount_to_charge;
        $_SESSION['paystack_timestamp'] = time();
        
        error_log("Paystack transaction initialized successfully - Reference: $reference");
        
        echo json_encode([
            'status' => 'success',
            'authorization_url' => $paystack_response['data']['authorization_url'],
            'reference' => $reference,
            'access_code' => $paystack_response['data']['access_code'],
            'message' => 'Redirecting to payment gateway...'
        ]);
    } else {
        error_log("Paystack API error: " . json_encode($paystack_response));
        
        $error_message = $paystack_response['message'] ?? 'Payment gateway error';
        throw new Exception($error_message);
    }
    
} catch (Exception $e) {
    error_log("Error initializing Paystack transaction: " . $e->getMessage());
    
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to initialize payment: ' . $e->getMessage()
    ]);
}
?>