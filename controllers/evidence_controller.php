<?php
// controllers/evidence_controller.php
require_once '../classes/evidence_class.php';

function add_evidence_ctr($victim_id, $title, $description, $file_path, $file_type, $raw_text_content, $folder_id = NULL) {
    $evidence = new Evidence();
    return $evidence->addEvidence($victim_id, $title, $description, $file_path, $file_type, $raw_text_content, $folder_id);
}

function get_victim_evidence_ctr($victim_id) {
    $evidence = new Evidence();
    return $evidence->getVictimEvidence($victim_id);
}

function delete_evidence_ctr($evidence_id, $victim_id) {
    $evidence = new Evidence();
    return $evidence->deleteEvidence($evidence_id, $victim_id);
}

function get_single_evidence_ctr($evidence_id, $victim_id) {
    $evidence = new Evidence();
    return $evidence->getEvidenceById($evidence_id, $victim_id);
}

function create_folder_ctr($victim_id, $folder_name) {
    $evidence = new Evidence();
    return $evidence->createFolder($victim_id, $folder_name);
}

function get_folders_ctr($victim_id) {
    $evidence = new Evidence();
    return $evidence->getFolders($victim_id);
}

function delete_folder_ctr($folder_id, $victim_id) {
    $evidence = new Evidence();
    return $evidence->deleteFolder($folder_id, $victim_id);
}
?>
