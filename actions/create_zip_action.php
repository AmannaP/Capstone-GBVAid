<?php
// actions/create_zip_action.php
require_once '../settings/core.php';
require_once '../controllers/evidence_controller.php';

if (!checkLogin()) {
    die("Unauthorized access.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $victim_id = $_SESSION['id'];
    $evidence_ids = $_POST['evidence_ids'] ?? [];

    if (empty($evidence_ids)) {
        echo "<script>alert('No evidence selected.'); window.history.back();</script>";
        exit;
    }

    $zip = new ZipArchive();
    $zipFileName = 'GBVAid_Evidence_Vault_' . time() . '.zip';
    $zipFilePath = sys_get_temp_dir() . '/' . $zipFileName;

    if ($zip->open($zipFilePath, ZipArchive::CREATE) !== TRUE) {
        die("Cannot create zip archive.");
    }

    $upload_dir = '../uploads/evidence/';

    foreach ($evidence_ids as $eid) {
        $ev = get_single_evidence_ctr($eid, $victim_id);
        if ($ev) {
            if ($ev['file_type'] === 'raw_text') {
                // Create a text file in the zip for raw text evidence
                $safeTitle = preg_replace('/[^a-zA-Z0-9_-]/', '_', $ev['title']);
                $zip->addFromString($safeTitle . '.txt', $ev['raw_text_content']);
            } else {
                $filePath = $upload_dir . $ev['file_path'];
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $ev['file_path']);
                }
            }
        }
    }

    $zip->close();

    // Stream the file to the browser
    header('Content-Type: application/zip');
    header('Content-disposition: attachment; filename=' . $zipFileName);
    header('Content-Length: ' . filesize($zipFilePath));
    readfile($zipFilePath);

    // Remove the temp file
    unlink($zipFilePath);
    exit;
} else {
    die("Invalid request mode.");
}
?>
