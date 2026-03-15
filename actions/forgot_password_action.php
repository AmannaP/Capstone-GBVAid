<?php
require_once '../settings/core.php';
require_once '../settings/db_class.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
        exit;
    }

    $db = new db_conn();
    if (!$db->db_connect()) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
        exit;
    }

    // Check if email exists
    $sql = "SELECT victim_id FROM victim WHERE victim_email = ?";
    $stmt = $db->db->prepare($sql);
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Generate Token
        $token = bin2hex(random_bytes(32)); // 64-char hex string
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Save to DB
        $update_sql = "UPDATE victim SET reset_token = ?, token_expires_at = ? WHERE victim_id = ?";
        $update_stmt = $db->db->prepare($update_sql);
        
        if ($update_stmt->execute([$token, $expires, $user['victim_id']])) {
            // In a real production app, we would use PHPMailer to send this link.
            // For capstone demonstration purposes, we return it in the JSON to simulate.
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
            $domain = $_SERVER['HTTP_HOST'];
            // Adjust the base path if needed based on the server setup
            $reset_link = "$protocol://$domain/Capstone-GBVAid/login/reset_password.php?token=$token";

            echo json_encode([
                'success' => true,
                'reset_link' => $reset_link 
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to generate reset token.']);
        }
    } else {
        // Obfuscation: To prevent email enumeration, return success even if email not found
        // But for development/demo, we'll return an error so the tester knows.
        echo json_encode(['success' => false, 'message' => 'Email address not found.']);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request array.']);
}
?>
