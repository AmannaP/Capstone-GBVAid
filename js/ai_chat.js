// js/ai_chat.js
$(document).ready(function() {
    $('#send-btn').on('click', sendMessage);
    $('#user-input').on('keypress', function(e) {
        if(e.which == 13) sendMessage();
    });

    function sendMessage() {
        const msg = $('#user-input').val();
        if(!msg) return;

        // Append user message to UI
        $('#chat-window').append(`<div class="text-end mb-3"><p class="d-inline-block p-2 rounded bg-secondary small">${msg}</p></div>`);
        $('#user-input').val('');
        
        // Show "AI is thinking"
        const loadingId = 'load-' + Date.now();
        $('#chat-window').append(`<div id="${loadingId}" class="mb-3 small opacity-50">AI is typing...</div>`);

        $.ajax({
            url: '../actions/ai_proxy.php',
            type: 'POST',
            data: JSON.stringify({ message: msg }),
            contentType: 'application/json',
            success: function(res) {
                $(`#${loadingId}`).remove();
                const aiResponse = res.choices[0].message.content;
                
                // Append AI response
                $('#chat-window').append(`
                    <div class="ai-msg mb-3">
                        <span class="badge bg-primary">AI Listener</span>
                        <p class="mt-2 small text-white">${aiResponse}</p>
                    </div>
                `);
                
                // Auto scroll to bottom
                $('#chat-window').scrollTop($('#chat-window')[0].scrollHeight);
            }
        });
    }
});