<?php
// controllers/evidence_controller.php
require_once '../classes/evidence_class.php';

function add_evidence_ctr($victim_id, $title, $description, $file_path, $file_type, $raw_text_content) {
    $evidence = new Evidence();
    return $evidence->addEvidence($victim_id, $title, $description, $file_path, $file_type, $raw_text_content);
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
?>
