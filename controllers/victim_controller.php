<?php
// Controllers/victim_controller.php
require_once '../classes/victim_class.php';

/**
 * Authenticate Victim
 */
function login_victim_ctr($email, $password) {
    $victimObj = new Victim();
    $victim = $victimObj->verifyPassword($email, $password);
    if ($victim) {
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

/**
 * Update Quick Exit URLs
 */
function update_quick_exit_ctr($id, $url1, $url2) {
    $victim = new Victim();
    return $victim->update_quick_exit($id, $url1, $url2);
}
?>