<?php
// Controllers/victim_controller.php
require_once '../classes/victim_class.php';

/**
 * Authenticate Victim
 */
function login_victim_ctr($email, $password) {
    $victimObj = new Victim();
    
    // This calls the method in your victim_class.php
    $victim = $victimObj->verifyPassword($email, $password);

    if ($victim) {
        // Note: It is usually better to set Sessions in the Action file 
        // to keep the Controller "clean", but if you keep them here, 
        // ensure the keys match your new 'victims' table columns.
        return $victim;
    }
    return false;
}

/**
 * Get Single Victim Details
 */
function get_victim_ctr($victim_id) {
    $victim = new Victim();
    return $victim->get_victim($victim_id);
}

/**
 * Update Victim Details
 */
function update_victim_ctr($id, $name, $contact, $city, $country, $image = null) {
    $victim = new Victim();
    return $victim->update_victim($id, $name, $contact, $city, $country, $image);
}
?>