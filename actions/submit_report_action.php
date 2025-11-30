<?php
// actions/submit_report_action.php
session_start();
require_once '../settings/db_class.php';

// Quick class for reporting since we don't have a dedicated controller yet
class Report extends db_conn {
    public function create($uid, $type, $date, $loc, $desc, $anon) {
        $this->db_connect();
        $sql = "INSERT INTO reports (customer_id, incident_type, incident_date, location, description, is_anonymous) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$uid, $type, $date, $loc, $desc, $anon]);
    }
}

$uid = $_SESSION['id'];
$type = $_POST['incident_type'];
$date = $_POST['incident_date'];
$loc = $_POST['location'];
$desc = $_POST['description'];
$anon = isset($_POST['is_anonymous']) ? 1 : 0;

$report = new Report();
if ($report->create($uid, $type, $date, $loc, $desc, $anon)) {
    // Redirect with success
    echo "<script>alert('Report submitted successfully.'); window.location.href='../user/dashboard.php';</script>";
} else {
    echo "<script>alert('Failed to submit report.'); window.history.back();</script>";
}
?>