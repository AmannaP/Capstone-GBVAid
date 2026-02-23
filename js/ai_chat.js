// js/ai_chat.js
$(document).ready(function() {
    const chatWindow = $('#chat-window');

    function scrollToBottom() {
        const chatWindow = document.getElementById('chat-window');
        chatWindow.scrollTo({
            top: chatWindow.scrollHeight,
            behavior: 'smooth'
        });
    }

    function sendMessage() {
        const message = $('#user-input').val().trim();
        const isSilenced = $('#silenceToggle').is(':checked');

        if (message === "") return;

        // 1. Append User Message with the styled bubble
        chatWindow.append(`
            <div class="msg-wrapper">
                <div class="user-msg-bubble">${message}</div>
            </div>
        `);
        
        $('#user-input').val('');
        scrollToBottom();

        // 2. Handle Silence Toggle (One-Way Venting)
        if (isSilenced) {
            $.post('../actions/ai_proxy.php', JSON.stringify({ message: message, silent: true }));
            
            chatWindow.append(`
                <div class="msg-wrapper text-center my-2">
                    <small class="text-white-50 italic">Message held in confidence...</small>
                </div>
            `);
            scrollToBottom();
        } 
        else {
            // 3. Normal AI Interaction
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

                    // Parse the response based on your proxy's JSON structure
                    // Checking for both choices (OpenAI style) or direct reply field
                    let aiReply = "";
                    if (response.choices && response.choices[0].message) {
                        aiReply = response.choices[0].message.content;
                    } else {
                        aiReply = response.reply || "I am listening. Please share more.";
                    }
                    
                    chatWindow.append(`
                        <div class="msg-wrapper">
                            <div class="ai-msg-bubble">${aiReply}</div>
                        </div>
                    `);
                    scrollToBottom();
                },
                error: function() {
                    $(`#${loadingId}`).remove();
                    chatWindow.append(`
                        <div class="msg-wrapper">
                            <div class="ai-msg-bubble">I'm having trouble connecting, but I'm still here for you.</div>
                        </div>
                    `);
                    scrollToBottom();
                }
            });
        }
    }

    // Event Listeners
    $('#send-btn').on('click', sendMessage);
    $('#user-input').on('keypress', function(e) {
        if (e.which == 13) {
            sendMessage();
        }
    });
});