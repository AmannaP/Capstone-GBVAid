<?php
// actions/submit_ticket_action.php
require_once '../settings/core.php';
require_once '../controllers/help_desk_controller.php';

header('Content-Type: application/json');

if (!checkLogin()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $victim_id = $_SESSION['id'];
    $category = $_POST['category'] ?? '';
    $message = $_POST['message'] ?? '';

    if (empty($category) || empty($message)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit();
    }

    $result = add_ticket_ctr($victim_id, $category, $message);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Ticket submitted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error occurred.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
