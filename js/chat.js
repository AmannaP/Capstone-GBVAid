// js/chat.js

$(document).ready(function() {
    const chatBox = $('#chatBox');
    const groupId = $('input[name="group_id"]').val(); 
    let autoScroll = true;

    if (!groupId) {
        console.error("Chat Error: Group ID is missing from the hidden input field.");
        return;
    }

    function fetchMessages() {
        $.ajax({
            url: '../actions/fetch_chat_action.php',
            type: 'GET',
            data: { group_id: groupId },
            success: function(data) {
                chatBox.html(data);
                if(autoScroll) {
                    scrollToBottom();
                }
            },
            error: function(xhr) {
                console.error("Fetch Error: ", xhr.responseText);
            }
        });
    }

    function scrollToBottom() {
        chatBox.scrollTop(chatBox[0].scrollHeight);
    }

    $('#chatForm').on('submit', function(e) {
        e.preventDefault();
        const msgInput = $('#messageInput');
        const msg = msgInput.val().trim();
        
        if(msg === "") return;

        const btn = $(this).find('button');
        btn.prop('disabled', true);

        $.ajax({
            url: '../actions/send_chat_action.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                // Use .trim() on response to catch hidden PHP spaces/line-breaks
                if(res.trim() === 'success') {
                    msgInput.val('');
                    fetchMessages(); 
                    autoScroll = true; 
                    scrollToBottom();
                } else {
                    console.error("Server returned error: " + res);
                    alert("Message could not be sent. Check console for details.");
                }
            },
            error: function(xhr) {
                console.error("AJAX Send Error: ", xhr.responseText);
            },
            complete: function() {
                btn.prop('disabled', false);
                msgInput.focus();
            }
        });
    });

    // Initial Load & Polling
    fetchMessages();
    setInterval(fetchMessages, 2500); // 2.5s is slightly gentler on the server

    // Smart Scroll Detection
    chatBox.on('scroll', function() {
        if(chatBox.scrollTop() + chatBox.innerHeight() >= chatBox[0].scrollHeight - 50) {
            autoScroll = true;
        } else {
            autoScroll = false;
        }
    });
});