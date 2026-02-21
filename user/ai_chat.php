<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Venting Room | GBVAid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
        .venting-room {
            background: radial-gradient(circle at center, #1a1033 0%, #0f0a1e 100%);
            min-height: 100vh;
        }

        .chat-container {
            border: 1px solid rgba(138, 43, 226, 0.3);
            box-shadow: 0 0 20px rgba(138, 43, 226, 0.1);
            transition: box-shadow 0.5s ease-in-out;
            background: rgba(26, 16, 51, 0.9);
            border-radius: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        /* Container to handle the floats properly */
        .msg-wrapper {
            width: 100%;
            clear: both;
            display: block;
            margin-bottom: 5px;
        }

        .user-msg-bubble {
            background: #3c2a61;
            color: white;
            border-radius: 15px 15px 0 15px;
            padding: 10px 15px;
            float: right;
            max-width: 80%;
            word-wrap: break-word;
        }

        .ai-msg-bubble {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #e0e0e0;
            border-radius: 15px 15px 15px 0;
            padding: 10px 15px;
            float: left;
            max-width: 80%;
            word-wrap: break-word;
        }

        .typing-glow {
            animation: pulse-glow 2s infinite;
            color: #8a2be2;
            font-style: italic;
            font-size: 0.85rem;
            clear: both;
        }

        @keyframes pulse-glow {
            0% { opacity: 0.4; }
            50% { opacity: 1; text-shadow: 0 0 10px #8a2be2; }
            100% { opacity: 0.4; }
        }

        /* Toggle Switch Styling */
        .form-check-input:checked {
            background-color: #8a2be2;
            border-color: #8a2be2;
        }
        .text-white-custom { color: rgba(255,255,255,0.7); }
    </style>
</head>
<body class="venting-room">
    <div class="container p-4">
        <div class="monitor-container mx-auto" style="max-width: 800px;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <h3 class="text-danger fw-bold m-0"><i class="bi bi-heart-pulse"></i> Safe Space Venting</h3>
                    <p class="text-white-custom small mb-0">This is a judgment-free zone. Your words stay here.</p>
                </div>
                <div class="form-check form-switch text-white">
                    <input class="form-check-input" type="checkbox" id="silenceToggle">
                    <label class="form-check-label small" for="silenceToggle">Silence AI (One-Way Vent)</label>
                </div>
            </div>
            
            <div id="chat-window" class="chat-container p-3 mb-3" style="height: 450px;">
                <div class="msg-wrapper">
                    <div class="ai-msg-bubble">
                        Hello. I am your GBVAid listener. Whether you want to talk about your day, vent your frustrations, or ask about resources like DOVVSU, I am here.
                    </div>
                </div>
            </div>

            <div class="input-group">
                <input type="text" id="user-input" class="form-control bg-transparent text-white border-purple" placeholder="Type your heart out...">
                <button class="btn btn-danger px-4" id="send-btn">
                    <i class="bi bi-send-fill"></i>
                </button>
            </div>
        </div>
    </div>

<script>
$(document).ready(function() {
    const chatWindow = $('#chat-window');

    function scrollToBottom() {
        chatWindow.animate({ scrollTop: chatWindow[0].scrollHeight }, 500);
    }

    function sendMessage() {
        const message = $('#user-input').val().trim();
        const isSilenced = $('#silenceToggle').is(':checked');

        if (message === "") return;

        // Append User Message
        chatWindow.append(`
            <div class="msg-wrapper">
                <div class="user-msg-bubble">${message}</div>
            </div>
        `);
        
        $('#user-input').val('');
        scrollToBottom();

        // Check if we should call the AI or just log it
        if (isSilenced) {
            // Option: You can still send it to proxy just for logging, 
            // but tell the proxy NOT to return a response or just ignore it here.
            $.post('../actions/ai_proxy.php', JSON.stringify({ message: message, silent: true }));
            
            // Show a small quiet indicator
            chatWindow.append(`<div class="msg-wrapper text-center my-2"><small class="text-white-50 italic">Message saved silently...</small></div>`);
            scrollToBottom();
        } else {
            // Normal AI interaction
            const loadingId = 'loading-' + Date.now();
            chatWindow.append(`
                <div class="msg-wrapper" id="${loadingId}">
                    <div class="typing-glow">AI Listener is reflecting...</div>
                </div>
            `);
            scrollToBottom();

            $.ajax({
                url: '../actions/ai_proxy.php',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ message: message }),
                success: function(response) {
                    $(`#${loadingId}`).remove();
                    // Assuming your proxy returns { reply: "text" }
                    const aiReply = response.reply || "I'm listening. Please continue.";
                    
                    chatWindow.append(`
                        <div class="msg-wrapper">
                            <div class="ai-msg-bubble">${aiReply}</div>
                        </div>
                    `);
                    scrollToBottom();
                },
                error: function() {
                    $(`#${loadingId}`).remove();
                    chatWindow.append(`<div class="msg-wrapper"><div class="ai-msg-bubble">I'm having trouble connecting, but I'm still here for you.</div></div>`);
                }
            });
        }
    }

    $('#send-btn').on('click', sendMessage);
    $('#user-input').on('keypress', function(e) {
        if (e.which == 13) sendMessage();
    });
});
</script>
</body>
</html>