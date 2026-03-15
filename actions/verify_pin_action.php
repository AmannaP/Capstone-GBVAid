<?php
require_once '../settings/core.php';
require_once '../settings/db_class.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id']) || $_SESSION['role'] != 3 || $_SESSION['sp_approved'] == 0) {
    echo json_encode(["success" => false, "message" => "Unauthorized access."]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pin = trim($_POST['pin'] ?? '');
    
    if (strlen($pin) !== 6) {
        echo json_encode(["success" => false, "message" => "Invalid PIN format."]);
        exit();
    }

    $db = new db_conn();
    if (!$db->db_connect()) {
        echo json_encode(["success" => false, "message" => "Database connection failed."]);
        exit();
    }

    // Find the victim associated with this active PIN
    $now = date('Y-m-d H:i:s');
    $sql = "SELECT victim_id, victim_name FROM victim WHERE vault_pin = ? AND vault_pin_expires > ?";
    $stmt = $db->db->prepare($sql);
    $stmt->execute([$pin, $now]);
    $matched_victim = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($matched_victim) {
        $victim_id = $matched_victim['victim_id'];
        $sp_id = $_SESSION['id'];

        // Unlock Vault in session
        $_SESSION['unlocked_vault'] = $victim_id;
        
        // **Audit Log**: Record the SP accessing the vault
        $action_desc = "Provider {$_SESSION['name']} unlocked Evidence Vault for Case #" . $victim_id;
        $audit_sql = "INSERT INTO audit_logs (provider_id, action_desc) VALUES (?, ?)";
        $audit_stmt = $db->db->prepare($audit_sql);
        $audit_stmt->execute([$sp_id, $action_desc]);

        echo json_encode(["success" => true, "message" => "Access granted."]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid or expired PIN."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>
