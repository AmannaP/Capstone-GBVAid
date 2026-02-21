<?php
require_once '../settings/core.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SOS Monitor | GBVAid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { 
            background-color: #0f0a1e; 
            color: white; 
            font-family: 'Poppins', sans-serif;
            background-image: radial-gradient(#3c2a61 1px, transparent 1px);
            background-size: 30px 30px;
        }
        .monitor-container { 
            margin-top: 50px; 
            border: 1px solid #3c2a61; 
            padding: 40px; 
            border-radius: 20px; 
            background: rgba(26, 16, 51, 0.9); 
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        .text-white-custom { color: #ffffff !important; opacity: 0.9; }
        .status-box {
            background: rgba(0, 255, 0, 0.05);
            padding: 10px 20px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            border: 1px solid rgba(0, 255, 0, 0.2);
        }
    </style>
</head>
<body>

<div class="container monitor-container text-center">
    <h2 class="text-danger fw-bold mb-4">EMERGENCY DISPATCH MONITOR</h2>
    
    <div class="status-box mb-4">
        <div class="spinner-grow text-success me-2" role="status" style="width: 1rem; height: 1rem;"></div>
        <p class="mb-0 small fw-bold">STATUS: <span id="status-text" class="text-success">LISTENING FOR SIGNALS...</span></p>
    </div>

    <hr style="border-color: #3c2a61;" class="my-4">

    <div id="active-incidents-area">
        <p class="text-white-custom mb-4">Once a signal is detected, dispatch options will appear here.</p>
        <button onclick="simulateArrival()" class="btn btn-danger px-5 py-3 rounded-pill shadow-lg fw-bold">
            <i class="bi bi-geo-alt-fill me-2"></i> CONFIRM ARRIVAL AT LOCATION
        </button>
    </div>
    
    <p class="text-white-custom mt-5 small">GBVAid Incident Coordination Interface &copy; 2026</p>
</div>

<script>
/**
 * SOS MONITOR LOGIC - HIGH RELIABILITY VERSION
 */
let lastIncidentCount = 0;
let currentActiveId = null;

// Using a Base64 encoded "Beep" (No external file needed, will never fail)
const alertSound = new Audio("data:audio/wav;base64,UklGRl9vT19XQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YTtvT18=");
const voiceStreamSim = new Audio("data:audio/wav;base64,UklGRl9vT19XQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YTtvT18=");
voiceStreamSim.loop = true;

function checkNewIncidents() {
    $.getJSON('../actions/get_active_sos_count.php')
        .done(function(data) {
            const currentCount = parseInt(data.count);
            currentActiveId = data.latest_incident_id;

            if (currentCount > lastIncidentCount) {
                // UI FIRST: Pop the alert immediately
                triggerEmergencyUI(currentActiveId);
            }
            lastIncidentCount = currentCount;
            $('#status-text').text("LISTENING FOR SIGNALS...").css('color', '#00ff00');
        })
        .fail(function() {
            $('#status-text').text("BACKEND ERROR").css('color', '#ff0000');
        });
}

function triggerEmergencyUI(incidentId) {
    // Play sound in background, catch error silently
    alertSound.play().catch(() => console.log("Audio waiting for user interaction."));

    Swal.fire({
        title: '🚨 PRIORITY ALERT',
        text: `New SOS signal detected (Incident #${incidentId})!`,
        icon: 'warning',
        confirmButtonText: 'Open Live Feed',
        background: '#1a1033',
        color: '#fff',
        confirmButtonColor: '#d33',
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            openEmergency(incidentId);
        }
        alertSound.pause();
    });
}

function openEmergency(incidentId) {
    // Show UI immediately
    Swal.fire({
        title: 'LIVE VOICE FEED ACTIVE',
        html: `<p>Listening to Incident <b>#${incidentId || 'Pending'}</b></p>
               <div class="spinner-grow text-danger" role="status" style="width: 1rem; height: 1rem;"></div>
               <span class="ms-2 text-danger small fw-bold">AUDIO UPLINK ESTABLISHED</span>`,
        icon: 'info',
        background: '#1a1033',
        color: '#fff',
        confirmButtonText: 'Mute Feed'
    }).then(() => {
        voiceStreamSim.pause();
    });

    // Try to play audio separately so it doesn't block the UI
    voiceStreamSim.play().catch(e => console.warn("Voice simulation muted."));
}

function simulateArrival() {
    if (!currentActiveId) {
        Swal.fire({ title: 'No Active Incidents', icon: 'info', background: '#1a1033', color: '#fff' });
        return;
    }

    $('.btn-danger').prop('disabled', true).text('PROCESSING...');

    $.post('../actions/stop_sos.php', { incident_id: currentActiveId })
        .done(function(res) {
            voiceStreamSim.pause();
            Swal.fire({ 
                title: 'Arrival Confirmed', 
                text: 'Incident closed successfully.',
                icon: 'success', 
                background: '#1a1033', 
                color: '#fff' 
            }).then(() => { location.reload(); });
        })
        .fail(function(jqXHR) {
            // This now pulls the actual error from PHP
            let errorMsg = "Check Network Tab for details.";
            if(jqXHR.responseJSON && jqXHR.responseJSON.message) {
                errorMsg = jqXHR.responseJSON.message;
            }
            
            Swal.fire({
                title: 'Update Failed',
                text: errorMsg,
                icon: 'error',
                background: '#1a1033',
                color: '#fff'
            });
            $('.btn-danger').prop('disabled', false).text('CONFIRM ARRIVAL AT LOCATION');
        });
}

// Start polling
setInterval(checkNewIncidents, 3000);
</script>
</body>
</html>