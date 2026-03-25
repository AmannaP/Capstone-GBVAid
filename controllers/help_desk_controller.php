<?php
// controllers/help_desk_controller.php

require_once __DIR__ . '/../classes/help_desk_class.php';

function add_ticket_ctr($victim_id, $category, $message) {
    $helpDesk = new HelpDesk();
    return $helpDesk->add_ticket($victim_id, $category, $message);
}

function get_all_tickets_ctr() {
    $helpDesk = new HelpDesk();
    return $helpDesk->get_all_tickets();
}

function get_user_tickets_ctr($victim_id) {
    $helpDesk = new HelpDesk();
    return $helpDesk->get_user_tickets($victim_id);
}

function update_ticket_ctr($ticket_id, $status, $admin_reply) {
    $helpDesk = new HelpDesk();
    return $helpDesk->update_ticket($ticket_id, $status, $admin_reply);
}

function get_ticket_by_id_ctr($ticket_id) {
    $helpDesk = new HelpDesk();
    return $helpDesk->get_ticket_by_id($ticket_id);
}
?>
