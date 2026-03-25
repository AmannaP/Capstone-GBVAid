<?php
require_once '../settings/core.php'; // Ensures session is started and validated
require_once '../controllers/chat_controller.php';

$gid = $_GET['group_id'] ?? null;
$current_user_id = $_SESSION['id'] ?? 0;

if (!$gid) exit();

$messages = get_messages_ctr($gid);

// Loop through messages and create HTML for each
foreach ($messages as $msg) {
    if ($msg['user_role'] == 2) {
        $roleInfo = ' <span class="badge bg-danger rounded-pill" style="font-size: 0.6rem; vertical-align:middle; margin-left:4px;">Admin</span>';
    } elseif ($msg['user_role'] == 3) {
        $cat = $msg['cat_name'] ?? 'Professional';
        $roleInfo = ' <span class="badge bg-light text-dark rounded-pill" style="font-size: 0.6rem; vertical-align:middle; margin-left:4px;">' . htmlspecialchars($cat) . '</span>';
    } else {
        $roleInfo = '';
    }

    $is_me = ($msg['victim_id'] == $current_user_id) ? 'sent' : 'received';
    // Add role info if not me
    $name = ($is_me == 'sent') ? 'You' : htmlspecialchars($msg['victim_name']) . $roleInfo;
    $time = date('h:i A', strtotime($msg['created_at']));
    $text = htmlspecialchars($msg['message']);

    echo "
    <div class='message $is_me'>
        <div class='msg-info'>
            <span class='msg-name'>$name</span>
            <span class='msg-time'>$time</span>
        </div>
        <div class='msg-text'>$text</div>
    </div>
    ";
}
?>