<?php
// actions/create_folder_action.php
require_once '../settings/core.php';
require_once '../controllers/evidence_controller.php';

header('Content-Type: application/json');

if (!checkLogin()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $victim_id = $_SESSION['id'];
    $folder_name = $_POST['folder_name'] ?? '';

    if (empty(trim($folder_name))) {
        echo json_encode(['status' => 'error', 'message' => 'Folder name is required.']);
        exit;
    }

    $result = create_folder_ctr($victim_id, trim($folder_name));

    if ($result) {
        // Fetch the newly created folder (last one inserted by the user or just general success)
        echo json_encode(['status' => 'success', 'message' => 'Folder created successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database failure while creating folder.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
