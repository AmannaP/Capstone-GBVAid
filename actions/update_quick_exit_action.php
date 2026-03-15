<?php
// actions/update_quick_exit_action.php
require_once '../settings/core.php';
require_once '../controllers/victim_controller.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated.']);
    exit();
}

$user_id = $_SESSION['id'];

// Sanitize – ensure they look like valid URLs or search terms (allow text/URLs)
function sanitize_exit_link($input) {
    $trimmed = trim($input);
    if (empty($trimmed)) return null;
    // If it starts with http/https keep it; otherwise prefix with google search
    if (!preg_match('/^https?:\/\//i', $trimmed)) {
        // treat as a website; auto-prepend https://
        $trimmed = 'https://' . $trimmed;
    }
    return filter_var($trimmed, FILTER_SANITIZE_URL);
}

$url1 = sanitize_exit_link($_POST['quick_exit_url1'] ?? '');
$url2 = sanitize_exit_link($_POST['quick_exit_url2'] ?? '');

if (!$url1 && !$url2) {
    echo json_encode(['status' => 'error', 'message' => 'Please provide at least one quick exit link.']);
    exit();
}

// Store empty string if null so DB doesn't keep old value
$result = update_quick_exit_ctr($user_id, $url1 ?? '', $url2 ?? '');

if ($result) {
    // Store in session for immediate navbar use
    $_SESSION['quick_exit_url1'] = $url1;
    $_SESSION['quick_exit_url2'] = $url2;
    echo json_encode(['status' => 'success', 'message' => 'Quick Exit links updated!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to save links. Please try again.']);
}
?>
