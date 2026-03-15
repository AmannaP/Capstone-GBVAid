<?php
// controllers/user_controller.php

require_once '../classes/user_class.php';


function register_user_ctr($name, $email, $password, $country, $city, $phone_number, $role, $provider_category_id = null, $provider_brand_id = null)
{
    $user = new User();
    $user_id = $user->createUser($name, $email, $password, $country, $city, $phone_number, $role, $provider_category_id, $provider_brand_id);
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

function get_pending_providers_ctr()
{
    $user = new User();
    return $user->getPendingProviders();
}

function approve_provider_ctr($provider_id)
{
    $user = new User();
    return $user->approveProvider($provider_id);
}

function reject_provider_ctr($provider_id)
{
    $user = new User();
    return $user->rejectProvider($provider_id);
}
?>