<?php
// controllers/appointment_controller.php
require_once '../classes/appointment_class.php';

/**
 * Controller function to book an appointment
 */
function book_appointment_ctr($uid, $sid, $date, $time, $notes) {
    $apptObject = new Appointment();
    
    // 1. Check if the slot is already taken
    if ($apptObject->is_slot_taken($sid, $date, $time)) {
        return "taken";
    }
    
    // 2. Book the appointment
    return $apptObject->book_appointment($uid, $sid, $date, $time, $notes);
}

/**
 * Controller function to fetch appointments for a specific user
 */
function get_user_appointments_ctr($uid) {
    $apptObject = new Appointment();
    return $apptObject->get_user_appointments($uid);
}

/**
 * Fetches and categorizes appointments into 'upcoming' and 'past'
 * @param int $uid User ID
 * @return array ['upcoming' => [], 'past' => []]
 */
function get_categorized_appointments_ctr($uid) {
    $apptObject = new Appointment();
    $appointments = $apptObject->get_user_appointments($uid);
    
    $upcoming = [];
    $past = [];
    $now = new DateTime();

    foreach ($appointments as $appt) {
        $apptTime = new DateTime($appt['appointment_date'] . ' ' . $appt['appointment_time']);
        
        if ($apptTime >= $now && $appt['status'] != 'Cancelled') {
            $upcoming[] = $appt;
        } else {
            $past[] = $appt;
        }
    }

    // Sort UPCOMING: Closest date first
    usort($upcoming, function($a, $b) {
        return strtotime($a['appointment_date'] . ' ' . $a['appointment_time']) - 
               strtotime($b['appointment_date'] . ' ' . $b['appointment_time']);
    });

    // Sort PAST: Most recent history first
    usort($past, function($a, $b) {
        return strtotime($b['appointment_date'] . ' ' . $b['appointment_time']) - 
               strtotime($a['appointment_date'] . ' ' . $a['appointment_time']);
    });

    return ['upcoming' => $upcoming, 'past' => $past];
}

/**
 * Controller function to cancel an appointment
 */
function cancel_appointment_ctr($sid, $uid) {
    $apptObject = new Appointment();
    return $apptObject->cancel_appointment($sid, $uid);
}