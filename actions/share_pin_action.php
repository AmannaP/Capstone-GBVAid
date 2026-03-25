<?php
// actions/share_pin_action.php
require_once '../settings/core.php';
require_once '../settings/db_class.php';

header('Content-Type: application/json');

if (!checkLogin()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender_id = $_SESSION['id'];
    $sp_id = $_POST['sp_id'] ?? '';
    $pin = $_POST['pin'] ?? '';

    if (empty($sp_id) || empty($pin)) {
        echo json_encode(['status' => 'error', 'message' => 'Service Provider ID and PIN are required.']);
        exit;
    }

    $db = new db_conn();
    if ($db->db_connect()) {
        try {
            $msg = "Secure Access Granted. My Vault PIN is: " . $pin;
            $sql = "INSERT INTO direct_messages (sender_id, receiver_id, message) VALUES (?, ?, ?)";
            $stmt = $db->db->prepare($sql);
            if ($stmt->execute([$sender_id, $sp_id, $msg])) {
                echo json_encode(['status' => 'success', 'message' => 'Vault PIN sent securely.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Database failure. Please try again.']);
            }
        } catch (PDOException $e) {
            // This catches fatal errors if the direct_messages table doesn't exist yet
            echo json_encode([
                'status' => 'error', 
                'message' => 'Database schema warning: The direct_messages table is missing. Please run db_patch.php to upgrade your live database.'
            ]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
