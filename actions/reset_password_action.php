<?php
require_once '../settings/core.php';
require_once '../settings/db_class.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = trim($_POST['token'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($token) || strlen($password) < 6) {
        echo json_encode(['success' => false, 'message' => 'Invalid data provided.']);
        exit;
    }

    $db = new db_conn();
    if (!$db->db_connect()) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
        exit;
    }

    $now = date('Y-m-d H:i:s');
    $sql = "SELECT victim_id FROM victim WHERE reset_token = ? AND token_expires_at > ?";
    $stmt = $db->db->prepare($sql);
    $stmt->execute([$token, $now]);
    
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $hashed_pwd = password_hash($password, PASSWORD_DEFAULT);

        // Update password and clear token
        $update_sql = "UPDATE victim SET victim_pass = ?, reset_token = NULL, token_expires_at = NULL WHERE victim_id = ?";
        $update_stmt = $db->db->prepare($update_sql);
        
        if ($update_stmt->execute([$hashed_pwd, $user['victim_id']])) {
            echo json_encode(['success' => true, 'message' => 'Password reset successful.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to reset password.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid or expired token.']);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request array.']);
}
?>
