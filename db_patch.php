<?php
require_once __DIR__ . '/settings/db_class.php';

$db = new db_conn();
if ($db->db_connect()) {
    echo "Connected to DB.\n";
    $conn = $db->db;
    
    // 1. Create evidence_folders table
    $sql1 = "CREATE TABLE IF NOT EXISTS evidence_folders (
        folder_id INT(11) AUTO_INCREMENT PRIMARY KEY,
        victim_id INT(11) NOT NULL,
        folder_name VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (victim_id) REFERENCES victim(victim_id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
    
    // 2. Add folder_id to evidence table (ignore if exists)
    $sql2 = "ALTER TABLE evidence ADD COLUMN folder_id INT(11) NULL DEFAULT NULL AFTER victim_id;";
    
    // Foreign key for folder_id
    $sql3 = "ALTER TABLE evidence ADD CONSTRAINT fk_evidence_folder FOREIGN KEY (folder_id) REFERENCES evidence_folders(folder_id) ON DELETE SET NULL;";
    
    // 3. Create direct_messages table
    $sql4 = "CREATE TABLE IF NOT EXISTS direct_messages (
        msg_id INT(11) AUTO_INCREMENT PRIMARY KEY,
        sender_id INT(11) NOT NULL,
        receiver_id INT(11) NOT NULL,
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        is_read TINYINT(1) DEFAULT 0,
        FOREIGN KEY (sender_id) REFERENCES victim(victim_id) ON DELETE CASCADE,
        FOREIGN KEY (receiver_id) REFERENCES victim(victim_id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
    
    try {
        $conn->exec($sql1);
        echo "Table evidence_folders created or already exists.\n";
    } catch (Exception $e) { echo "Error \$sql1: " . $e->getMessage() . "\n"; }

    try {
        $conn->exec($sql2);
        echo "Column folder_id added to evidence.\n";
    } catch (Exception $e) { echo "Notice \$sql2: " . $e->getMessage() . "\n"; }

    try {
        $conn->exec($sql3);
        echo "Constraint fk_evidence_folder added.\n";
    } catch (Exception $e) { echo "Notice \$sql3: " . $e->getMessage() . "\n"; }
    
    try {
        $conn->exec($sql4);
        echo "Table direct_messages created or already exists.\n";
    } catch (Exception $e) { echo "Error \$sql4: " . $e->getMessage() . "\n"; }

    echo "Database upgrade complete.\n";
} else {
    echo "Failed to connect to DB.\n";
}
?>
