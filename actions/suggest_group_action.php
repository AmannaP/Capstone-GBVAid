<?php
// actions/suggest_group_action.php
require_once '../settings/core.php'; 
require_once '../classes/chat_class.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Please login first.']);
    exit();
}

$uid = $_SESSION['id'];
$name = $_POST['suggested_name'] ?? '';
$reason = $_POST['reason_description'] ?? '';

if (empty($name) || empty($reason)) {
    echo json_encode(['status' => 'error', 'message' => 'Please fill all fields.']);
    exit();
}

$chat = new Chat();
// Assuming you add this method to your chat_class.php
if ($chat->suggest_new_group($uid, $name, $reason)) {
    echo json_encode(['status' => 'success', 'message' => 'Your suggestion has been sent to the admin for approval.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to save suggestion.']);
}
