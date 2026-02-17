/**
 * admin_sos.js - Optimized for Presentation
 */

let lastIncidentCount = 0;
// Police Siren for the Admin Alert
const sirenSound = new Audio('https://www.soundjay.com/buttons/beep-01a.mp3');
// Ambient Background Noise for the "Live Feed" Simulation
const voiceStreamSim = new Audio('https://www.soundjay.com/ambient/airport-gate-1.mp3');

voiceStreamSim.loop = true;
sirenSound.volume = 0.5;
voiceStreamSim.volume = 0.3;

$(document).ready(function() {
    // Start polling the server every 3 seconds
    setInterval(pollForEmergencies, 3000);
});

function pollForEmergencies() {
    $.getJSON('../actions/get_active_sos_count.php', function(data) {
        const currentCount = parseInt(data.count);
        const incidentId = data.latest_incident_id;
        
        // Update badge
        if ($('#activeCountBadge').length) {
            $('#activeCountBadge').text(currentCount + " Active Alerts");
        }
        
        // If count increases, trigger alert
        if (currentCount > lastIncidentCount) {
            triggerEmergencyUI(incidentId);
        }
        lastIncidentCount = currentCount;
    }).fail(function(e) {
        console.log("Check PHP output for errors: ", e.responseText);
    });
}

function triggerEmergencyUI(incidentId) {
    sirenSound.play().catch(e => console.log("Click page to enable audio"));

    Swal.fire({
        title: '🚨 PRIORITY ALERT',
        text: 'New SOS signal detected!',
        icon: 'warning',
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'OPEN LIVE MONITOR',
        showCancelButton: true,
        background: '#1a1033',
        color: '#fff'
    }).then((result) => {
        sirenSound.pause();
        sirenSound.currentTime = 0;
        if (result.isConfirmed) {
            openEmergency(incidentId);
        }
    });
}

function openEmergency(incidentId) {
    voiceStreamSim.play();

    Swal.fire({
        title: 'LIVE VOICE FEED ACTIVE',
        html: `<p>Listening to Incident <b>#${incidentId}</b></p>
               <div class="spinner-grow text-danger" role="status" style="width: 1rem; height: 1rem;"></div>
               <span class="ms-2">Audio Uplink Established</span>`,
        icon: 'info',
        background: '#1a1033',
        color: '#fff',
        confirmButtonText: 'Mute Feed',
        footer: '<span style="color: #ff4d4d">Automatic shutdown active upon arrival.</span>'
    }).then(() => {
        voiceStreamSim.pause();
    });
}

function simulateArrival(incidentId) {
    $.post('../actions/stop_sos.php', { incident_id: incidentId }, function() {
        voiceStreamSim.pause();
        Swal.fire({
            title: 'ARRIVAL CONFIRMED',
            text: 'Communication link terminated.',
            icon: 'success',
            background: '#1a1033',
            color: '#fff'
        }).then(() => location.reload());
    });
}