<?php
// actions/add_evidence_action.php
require_once '../settings/core.php';
require_once '../controllers/evidence_controller.php';

// Allow json response
header('Content-Type: application/json');

if (!checkLogin()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $victim_id = $_SESSION['id'];
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $type = $_POST['evidence_type'] ?? 'raw_text'; // 'file' or 'raw_text'
    $raw_text_content = $_POST['raw_text_content'] ?? null;
    $folder_id = !empty($_POST['folder_id']) ? $_POST['folder_id'] : null;
    
    $file_path = null;
    $file_type = 'raw_text';

    if (empty($title)) {
        echo json_encode(['status' => 'error', 'message' => 'Evidence title is required.']);
        exit;
    }

    if ($type === 'file') {
        if (isset($_FILES['evidence_file']) && $_FILES['evidence_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/evidence/';
            
            // Create directory if not exists
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $file_tmp_path = $_FILES['evidence_file']['tmp_name'];
            $file_name = $_FILES['evidence_file']['name'];
            $file_size = $_FILES['evidence_file']['size'];
            
            // Securely create unique filename prefix
            $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $uniq_id = uniqid();
            $timestamp = time();
            $secure_file_name = "user_{$victim_id}_{$timestamp}_{$uniq_id}.{$extension}";
            
            $dest_path = $upload_dir . $secure_file_name;

            // Allowed types filter
            $allowed_exts = ['jpg', 'jpeg', 'png', 'pdf', 'mp3', 'mp4', 'docx', 'txt', 'm4a', 'wav'];
            if (!in_array($extension, $allowed_exts)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid file format uploaded.']);
                exit;
            }

            // Max file size (e.g., 20MB)
            if ($file_size > 20000000) {
                 echo json_encode(['status' => 'error', 'message' => 'File size exceeds 20MB limit.']);
                 exit;
            }

            if (move_uploaded_file($file_tmp_path, $dest_path)) {
                $file_path = $secure_file_name;
                $file_type = $extension;
                $raw_text_content = null; // Clear raw text if a file is uploaded
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to securely save the file.']);
                exit;
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No file uploaded or an upload error occurred.']);
            exit;
        }
    } else {
        // Raw text type
        if (empty($raw_text_content)) {
            echo json_encode(['status' => 'error', 'message' => 'Raw text content is required when no file is uploaded.']);
            exit;
        }
        $file_type = 'raw_text';
    }

    $result = add_evidence_ctr($victim_id, $title, $description, $file_path, $file_type, $raw_text_content, $folder_id);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Evidence securely saved to archive.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database failure while saving evidence.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
