<?php
require_once '../settings/core.php';
require_once '../controllers/chat_controller.php';

if (!checkLogin()) header("Location: ../login/login.php");

$group_id = $_GET['id'] ?? null;
$group = get_group_details_ctr($group_id);

if (!$group) {
    header("Location: chat.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($group['group_name']) ?> | GBVAid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { 
            background-color: #0f0a1e; 
            height: 100vh; 
            display: flex; 
            flex-direction: column; 
            font-family: 'Poppins', sans-serif;
            background-image: radial-gradient(#3c2a61 1px, transparent 1px);
            background-size: 30px 30px;
        }
        
        /* Glassmorphism Header */
        .chat-header {
            background: rgba(26, 16, 51, 0.9);
            backdrop-filter: blur(15px);
            padding: 15px 20px;
            border-bottom: 1px solid rgba(191, 64, 255, 0.3);
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .chat-container {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            /* Scrollbar styling for dark theme */
            scrollbar-width: thin;
            scrollbar-color: #3c2a61 transparent;
        }

        .chat-container::-webkit-scrollbar { width: 6px; }
        .chat-container::-webkit-scrollbar-thumb { background: #3c2a61; border-radius: 10px; }
        
        /* Message Bubbles */
        .message {
            max-width: 75%;
            padding: 12px 18px;
            border-radius: 20px;
            position: relative;
            font-size: 0.95rem;
            line-height: 1.5;
        }
        
        .message.received {
            align-self: flex-start;
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-bottom-left-radius: 4px;
            backdrop-filter: blur(5px);
        }
        
        .message.sent {
            align-self: flex-end;
            background: linear-gradient(135deg, #9d4edd 0%, #c453ea 100%);
            color: white;
            border-bottom-right-radius: 4px;
            box-shadow: 0 4px 15px rgba(196, 83, 234, 0.3);
        }
        
        .msg-info {
            display: flex;
            gap: 10px;
            font-size: 0.7rem;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .message.sent .msg-info { color: rgba(255, 255, 255, 0.7); }
        .message.received .msg-info { color: #e0aaff; }
        
        /* BRIGHT Input Area */
        .chat-input-area {
            background-color: #ffffff; /* Bright Background */
            padding: 20px;
            border-top-left-radius: 25px;
            border-top-right-radius: 25px;
            box-shadow: 0 -5px 25px rgba(0,0,0,0.2);
        }
        
        #messageInput {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            color: #212529;
            transition: all 0.3s;
        }

        #messageInput:focus {
            background-color: #fff;
            border-color: #c453ea;
            box-shadow: 0 0 0 0.25rem rgba(196, 83, 234, 0.1);
        }
        
        .send-btn {
            background: #9d4edd;
            color: white;
            border-radius: 50%;
            width: 48px; height: 48px;
            display: flex; align-items: center; justify-content: center;
            border: none;
            transition: all 0.3s;
            box-shadow: 0 4px 10px rgba(157, 78, 221, 0.4);
        }
        .send-btn:hover { 
            background-color: #bf40ff; 
            transform: scale(1.05); 
        }
        
        .welcome-badge {
            background: rgba(191, 64, 255, 0.1);
            color: #e0aaff;
            border: 1px solid rgba(191, 64, 255, 0.2);
            padding: 5px 20px;
            border-radius: 50px;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

<div class="chat-header">
    <div class="d-flex align-items-center">
        <a href="chat.php" class="btn btn-outline-light btn-sm me-3 rounded-circle border-0">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h5 class="fw-bold mb-0 text-white"><?= htmlspecialchars($group['group_name']) ?></h5>
            <small style="color: #e0aaff;"><i class="bi bi-circle-fill text-success me-1" style="font-size: 8px;"></i> Online Support</small>
        </div>
    </div>
    <a href="report_incident.php" class="btn btn-outline-danger btn-sm rounded-pill px-3">
        <i class="bi bi-exclamation-triangle-fill me-1"></i> Report
    </a>
</div>

<div class="chat-container" id="chatBox">
    <div class="text-center my-4">
        <span class="welcome-badge">Welcome to the safe space. Be kind. 💜</span>
    </div>
    
    <div class="text-center mt-5 loader-parent">
        <div class="spinner-border text-info spinner-border-sm" role="status"></div>
    </div>
</div>

<div class="chat-input-area">
    <form id="chatForm" class="d-flex align-items-center gap-2">
        <input type="hidden" name="group_id" value="<?= $group['group_id'] ?>">
        
        <input type="text" id="messageInput" name="message" 
               class="form-control rounded-pill py-2 px-4" 
               placeholder="Type a safe message..." autocomplete="off">
               
        <button type="submit" class="send-btn" id="sendBtn">
            <i class="bi bi-send-fill"></i>
        </button>
    </form>
</div>

<script src="../js/chat.js"></script>

</body>
</html>