<?php
// controllers/incident_controller.php
require_once '../classes/incident_class.php';

function save_sos_ctr($victim_id, $lat, $lon) {
    $incident_obj = new Incident();
    return $incident_obj->saveSOSLocation($victim_id, $lat, $lon);
}

function stop_sos_ctr($incident_id) {
    $incident_obj = new Incident();
    return $incident_obj->closeIncident($incident_id);
}

function get_active_sos_count_ctr() {
    $incident_obj = new Incident();
    // Force the result to be an integer
    return (int)$incident_obj->getActiveCount();
}

function get_all_active_incidents_ctr() {
    $incident_obj = new Incident();
    return $incident_obj->getActiveIncidents();
}