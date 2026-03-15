<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Venting Room | GBVAid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    
    <style>
        /* Force the body to be exactly the height of the screen and not scroll */
        body, html {
            height: 100%;
            margin: 0;
            overflow: hidden;
            background-color: #0f0a1e;
            font-family: 'Poppins', sans-serif;
        }

        /* Main layout container */
        .venting-room {
            display: flex;
            flex-direction: column;
            height: 100vh;
            background: radial-gradient(circle at center, #1a1033 0%, #0f0a1e 100%);
        }

        /* Top Section: Header and Chat Area */
        .chat-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 20px;
            overflow: hidden; /* Important: keeps the container from expanding */
        }

        .header-section {
            flex-shrink: 0;
            padding-bottom: 15px;
        }

        /* The Scrollable Chat Window */
        .chat-container {
            flex: 1;
            min-height: 0; /* Critical fix for flexbox scrolling */
            background: rgba(26, 16, 51, 0.4);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(138, 43, 226, 0.2);
            border-radius: 25px;
            overflow-y: auto;
            padding: 25px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            scrollbar-width: thin;
            scrollbar-color: #3c2a61 transparent;
        }

        /* Custom Scrollbar */
        .chat-container::-webkit-scrollbar {
            width: 6px;
        }
        .chat-container::-webkit-scrollbar-thumb {
            background: #3c2a61;
            border-radius: 10px;
        }

        /* Message Bubbles */
        .msg-wrapper { width: 100%; clear: both; display: block; margin-bottom: 10px; }

        .user-msg-bubble {
            background: linear-gradient(135deg, #9d4edd 0%, #c453ea 100%);
            color: white;
            border-radius: 20px 20px 0 20px;
            padding: 12px 18px;
            float: right;
            max-width: 80%;
            box-shadow: 0 4px 15px rgba(157, 78, 221, 0.3);
            word-wrap: break-word;
        }

        .ai-msg-bubble {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #e0aaff;
            border-radius: 20px 20px 20px 0;
            padding: 12px 18px;
            float: left;
            max-width: 80%;
            backdrop-filter: blur(5px);
            word-wrap: break-word;
        }

        /* Markdown Formatting for AI Bubble */
        .ai-msg-bubble p { margin-bottom: 10px; line-height: 1.5; }
        .ai-msg-bubble p:last-child { margin-bottom: 0; }
        .ai-msg-bubble ul, .ai-msg-bubble ol { 
            padding-left: 20px; 
            margin-bottom: 10px; 
        }
        .ai-msg-bubble ul:last-child, .ai-msg-bubble ol:last-child { margin-bottom: 0; }
        .ai-msg-bubble li { margin-bottom: 5px; }
        .ai-msg-bubble strong { color: #ffffff; font-weight: 600; }

        /* Typing Indicator Animation */
        .typing-glow {
            animation: pulse-glow 2s infinite;
            color: #bf40ff;
            font-style: italic;
            font-size: 0.85rem;
            clear: both;
            margin-top: 10px;
        }

        @keyframes pulse-glow {
            0% { opacity: 0.4; }
            50% { opacity: 1; text-shadow: 0 0 10px #bf40ff; }
            100% { opacity: 0.4; }
        }

        /* Bright Fixed Input Area */
        .input-area-bright {
            background: #ffffff;
            padding: 20px 30px;
            border-top-left-radius: 30px;
            border-top-right-radius: 30px;
            box-shadow: 0 -10px 30px rgba(0,0,0,0.3);
            flex-shrink: 0; /* Prevents input area from squishing */
        }

        #user-input {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            color: #212529;
            border-radius: 50px;
            padding: 12px 20px;
        }

        #user-input:focus {
            box-shadow: none;
            border-color: #9d4edd;
        }

        .form-check-input:checked {
            background-color: #9d4edd;
            border-color: #9d4edd;
        }
    </style>
</head>
<body>

<div class="venting-room">
    <div class="chat-wrapper">
        <div class="container-fluid d-flex flex-column h-100" style="max-width: 900px;">
            
            <div class="header-section d-flex justify-content-between align-items-center">
                <a href="dashboard.php" class="btn btn-outline-light btn-sm me-3 rounded-circle border-0">
                    <i class="bi bi-arrow-left fs-5"></i>
                </a>
                <div>
                    <h3 class="text-white fw-bold m-0">
                        <i class="bi bi-heart-pulse-fill text-danger"></i> Safe Space Venting
                    </h3>
                    <p class="text-white-50 small mb-0">Your words stay here. This is your safe haven.</p>
                </div>
                <div class="form-check form-switch text-white">
                    <input class="form-check-input" type="checkbox" id="silenceToggle">
                    <label class="form-check-label small" for="silenceToggle">Silence AI</label>
                </div>
            </div>
            
            <div id="chat-window" class="chat-container">
                <div class="msg-wrapper">
                    <div class="ai-msg-bubble">
                        Hello. I am your GBVAid listener. This is a judgment-free zone. Whether you want to talk about your day, vent your frustrations, or ask about resources, I am here.
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="input-area-bright">
        <div class="container" style="max-width: 850px;">
            <div class="input-group">
                <input type="text" id="user-input" class="form-control" placeholder="Type your heart out..." autocomplete="off">
                <button class="btn btn-danger px-4 rounded-pill ms-2" id="send-btn" style="background: #9d4edd; border: none;">
                    <i class="bi bi-send-fill"></i>
                </button>
            </div>
            <div class="text-center mt-2">
                <small class="text-muted" style="font-size: 0.7rem;">Your venting data is encrypted and private.</small>
            </div>
        </div>
    </div>
</div>

<script src="../js/ai_chat.js?v=2"></script>

</body>
</html>