<?php
// controllers/user_controller.php

require_once '../classes/user_class.php';


function register_user_ctr($name, $email, $password, $country, $city, $phone_number, $role)
{
    $user = new User();
    $user_id = $user->createUser($name, $email, $password, $country, $city, $phone_number, $role);
    if ($user_id) {
        return $user_id;
    }
    return false;
}

// Get user by email (for login)
function get_user_by_email_ctr($email)
{
    $user = new User();
    return $user->getUserByEmail($email);
}