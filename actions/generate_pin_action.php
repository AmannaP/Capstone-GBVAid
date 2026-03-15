<?php
require_once '../settings/core.php';
require_once '../settings/db_class.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new db_conn();
    if (!$db->db_connect()) {
        echo json_encode(["success" => false, "message" => "Database connection failed"]);
        exit();
    }

    $victim_id = $_SESSION['id'];
    
    // Generate a secure 6-digit alphanumerical PIN (uppercase only)
    $chars = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ'; // Removed confusing characters like 0, O, 1, I
    $pin = '';
    for ($i = 0; $i < 6; $i++) {
        $pin .= $chars[random_int(0, strlen($chars) - 1)];
    }

    // PIN is valid for 24 hours
    $expires = date('Y-m-d H:i:s', strtotime('+24 hours'));
    $display_expires = date('M j, g:i A', strtotime($expires));

    $sql = "UPDATE victim SET vault_pin = ?, vault_pin_expires = ? WHERE victim_id = ?";
    $stmt = $db->db->prepare($sql);
    
    if ($stmt->execute([$pin, $expires, $victim_id])) {
        echo json_encode([
            "success" => true,
            "pin" => $pin,
            "expires" => $display_expires
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to save PIN"]);
    }
}
?>
