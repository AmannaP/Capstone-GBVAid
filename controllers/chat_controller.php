<?php
// controllers/chat_controller.php
require_once '../classes/chat_class.php';

function get_chat_groups_ctr() {
    $chat = new Chat();
    return $chat->get_all_groups();
}

function get_group_details_ctr($id) {
    $chat = new Chat();
    return $chat->get_group_details($id);
}

function add_message_ctr($group_id, $victim_id, $msg) {
    $chat = new Chat();
    return $chat->add_message($group_id, $victim_id, $msg);
}

function get_messages_ctr($group_id) {
    $chat = new Chat();
    return $chat->get_messages($group_id);
}

function create_group_ctr($name, $desc, $icon) {
    $chat = new Chat();
    return $chat->create_group($name, $desc, $icon);
}

function delete_group_ctr($id) {
    $chat = new Chat();
    return $chat->delete_group($id);
}

function suggest_new_group_ctr($victim_id, $name, $reason) {
    $chat = new Chat();
    return $chat->suggest_new_group($victim_id, $name, $reason);
}

function get_pending_requests_ctr() {
    $chat = new Chat();
    return $chat->get_pending_requests();
}

function approve_suggestion_ctr($request_id) {
    $chat = new Chat();
    return $chat->approve_suggestion($request_id);
}

function reject_suggestion_ctr($request_id) {
    $chat = new Chat();
    return $chat->reject_suggestion($request_id);
}

function update_group_ctr($id, $name, $desc, $icon) {
    $chat_instance = new Chat();
    return $chat_instance->update_group($id, $name, $desc, $icon);
}

?>