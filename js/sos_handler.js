/**
 * SOS Emergency Handler for GBVAid
 */
$(document).ready(function() {
    let mediaRecorder;

    $('.emergency-btn').on('click', function() {
        const btn = $(this);
        const originalHtml = btn.html();

        Swal.fire({
            title: 'CONFIRM EMERGENCY',
            text: "This will send your location and live background audio to responders.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff4d4d',
            cancelButtonColor: '#6e7881',
            confirmButtonText: 'YES, SEND SOS',
            background: '#1a1033',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                triggerSosProcess(btn, originalHtml);
            }
        });
    });

    function triggerSosProcess(btn, originalHtml) {
        if (navigator.geolocation) {
            btn.html('<span class="spinner-border spinner-border-sm"></span> DISPATCHING...').prop('disabled', true);
            
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;

                    $.ajax({
                        url: '../actions/sos_action.php', 
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            lat: lat,
                            lon: lon,
                            action: 'trigger_sos'
                        },
                        success: function(res) {
                            if(res.status === 'success') {
                                // Save ID globally for the stopSharing function
                                window.currentIncidentId = res.incident_id;

                                // Start recording audio securely
                                startAudioRecording(window.currentIncidentId);

                                // UI Updates
                                btn.addClass('pulse-red').css({
                                    'background': '#ff0000',
                                    'box-shadow': '0 0 20px rgba(255, 0, 0, 0.6)'
                                }).html('<i class="bi bi-broadcast"></i> SIGNAL LIVE & RECORDING');
                                
                                $('#stop-sharing-zone').fadeIn();

                                Swal.fire({
                                    icon: 'success',
                                    title: 'SOS SIGNAL SENT',
                                    text: "Help is dispatched. Background audio is actively being recorded.",
                                    background: '#1a1033',
                                    color: '#fff',
                                    confirmButtonColor: '#ff4d4d'
                                });
                            } else {
                                handleSosError(btn, originalHtml, res.message);
                            }
                        },
                        error: function() {
                            handleSosError(btn, originalHtml, "Could not reach emergency servers.");
                        }
                    });
                },
                (error) => {
                    handleSosError(btn, originalHtml, "Location access denied. Please enable GPS.");
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        } else {
            Swal.fire('Error', 'Geolocation is not supported by your browser.', 'error');
        }
    }

    // Initialize MediaRecorder and transmit 10-second chunks
    function startAudioRecording(incidentId) {
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ audio: true })
            .then(function(stream) {
                // Initialize MediaRecorder
                mediaRecorder = new MediaRecorder(stream);
                
                // Event fires every time a chunk is ready
                mediaRecorder.ondataavailable = function(e) {
                    if (e.data.size > 0) {
                        sendAudioChunk(e.data, incidentId);
                    }
                };
                
                // Start recording, chunking every 10 seconds (10000ms)
                mediaRecorder.start(10000); 
            })
            .catch(function(err) {
                console.error("Microphone access denied or error:", err);
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: 'Mic access blocked. Audio disabled.',
                    showConfirmButton: false,
                    timer: 3000
                });
            });
        } else {
            console.warn("Media devices not supported by this browser.");
        }
    }

    // Ajax call to send the blob to the server
    function sendAudioChunk(blob, incidentId) {
        const formData = new FormData();
        formData.append('audio_data', blob);
        formData.append('incident_id', incidentId);
        
        $.ajax({
            url: '../actions/sos_audio_action.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                console.log("Audio chunk safely transmitted");
            },
            error: function(err) {
                console.error("Failed to transmit audio chunk", err);
            }
        });
    }

    // Stop Sharing / Terminate Incident
    window.stopSharing = function() {
        Swal.fire({
            title: 'Stop Sharing Location & Audio?',
            text: "Are you safe now?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, I am safe',
            background: '#1a1033',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                // Terminate recording safely
                if (mediaRecorder && mediaRecorder.state !== 'inactive') {
                    mediaRecorder.stop();
                    // Terminate all audio tracks to free the mic
                    mediaRecorder.stream.getTracks().forEach(track => track.stop());
                }

                $.post('../actions/stop_sos.php', { incident_id: window.currentIncidentId }, function() {
                    location.reload(); // Reset the dashboard
                });
            }
        });
    };

    function handleSosError(btn, originalHtml, message) {
        btn.html(originalHtml).prop('disabled', false);
        Swal.fire({
            icon: 'error',
            title: 'Transmission Failed',
            text: message,
            background: '#1a1033',
            color: '#fff'
        });
    }
});