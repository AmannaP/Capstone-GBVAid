/**
 * SOS Emergency Handler for GBVAid
 */
$(document).ready(function() {
    $('.emergency-btn').on('click', function() {
        const btn = $(this);
        const originalHtml = btn.html();

        Swal.fire({
            title: 'CONFIRM EMERGENCY',
            text: "This will send your location to emergency responders.",
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

                                // UI Updates
                                btn.addClass('pulse-red').css({
                                    'background': '#ff0000',
                                    'box-shadow': '0 0 20px rgba(255, 0, 0, 0.6)'
                                }).html('<i class="bi bi-broadcast"></i> SIGNAL LIVE');
                                
                                $('#stop-sharing-zone').fadeIn();

                                Swal.fire({
                                    icon: 'success',
                                    title: 'SOS SIGNAL SENT',
                                    text: "Help is being dispatched to your location.",
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

    // Fixed stopSharing - No longer requires a 'reason' dropdown
    window.stopSharing = function() {
        Swal.fire({
            title: 'Stop Sharing Location?',
            text: "Are you safe now?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, I am safe',
            background: '#1a1033',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
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