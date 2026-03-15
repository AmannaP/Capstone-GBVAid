<?php
// actions/delete_evidence_action.php
require_once '../settings/core.php';
require_once '../controllers/evidence_controller.php';

header('Content-Type: application/json');

if (!checkLogin()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic verification
    $victim_id = $_SESSION['id'];
    $evidence_id = $_POST['evidence_id'] ?? null;

    if (!$evidence_id) {
        echo json_encode(['status' => 'error', 'message' => 'Evidence ID is missing']);
        exit();
    }

    // Step 1: Fetch the existing evidence record to delete the file
    $evidence = get_single_evidence_ctr($evidence_id, $victim_id);
    
    if (!$evidence) {
        echo json_encode(['status' => 'error', 'message' => 'Evidence not found or you do not have permission.']);
        exit();
    }

    // Step 2: Delete from Database
    $deleteResult = delete_evidence_ctr($evidence_id, $victim_id);

    if ($deleteResult) {
        // Step 3: Delete File from directory if it was a file upload
        if ($evidence['file_type'] !== 'raw_text' && !empty($evidence['file_path'])) {
            $filePath = '../uploads/evidence/' . $evidence['file_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        echo json_encode(['status' => 'success', 'message' => 'Evidence deleted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database failure while deleting evidence.']);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Request Method']);
}
?>
