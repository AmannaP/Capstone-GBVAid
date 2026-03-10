// js/report_handler.js
document.addEventListener('DOMContentLoaded', function () {
    console.log("JS Loaded: Checking for form elements...");
    const reportForm = document.getElementById('reportForm');
    const regionSelect = document.getElementById('region');
    const areaSelect = document.getElementById('area');
    const otherContainer = document.getElementById('other-area-container');
    const otherInput = document.getElementById('other_area');

    if (!regionSelect || !areaSelect) {
        console.error("Critical Error: HTML IDs 'region' or 'area' not found!");
        return; 
    }

    // 1. Data Object: Regions and Areas with Police Presence
    const ghanaData = {
        "Ahafo": ["Goaso", "Duayaw Nkwanta", "Bechem", "Kenyasi", "Hwidiem", "Mim", "Kukuom", "Tepa", "Tanoso", "Nkasaim"],
        "Ashanti": ["Kumasi Central", "Obuasi", "Ejisu", "Mampong", "Konongo", "Bekwai", "Offinso", "Tafo", "Asokwa", "Suame", "Nkawie"],
        "Bono": ["Sunyani", "Berekum", "Dormaa Ahenkro", "Sampa", "Wenchi", "Drobo", "Nsuatre", "Chiraa", "Wamfie", "Bechem"],
        "Bono East": ["Techiman", "Kintampo", "Nkoranza", "Atebubu", "Yeji", "Prang", "Tuobodom", "Kajaji", "Kwame Danso", "Amantin"],
        "Central": ["Cape Coast", "Kasoa", "Winneba", "Mfantseman", "Agona Swedru", "Dunkwa-on-Offin", "Elmina", "Apam", "Buduburam", "Assin Fosu"],
        "Eastern": ["Koforidua", "Nkawkaw", "Suhum", "Akropong", "Oda", "Asamankese", "Somanya", "Anyinam", "Mpraeso", "Nsawam"],
        "Greater Accra": ["Accra Central", "Adabraka", "Nima", "Madina", "Tema", "Dansoman", "Teshie", "Legon", "Airport", "Kaneshie", "Ashaiman"],
        "North East": ["Nalerigu", "Gambaga", "Walewale", "Bunkpurugu", "Chereponi", "Yagaba", "Langbinsi", "Yunyoo", "Wulugu", "Nakpanduri"],
        "Northern": ["Tamale", "Yendi", "Savelugu", "Bimbilla", "Gushiegu", "Kumbungu", "Tolon", "Karaga", "Zabzugu", "Saboba"],
        "Oti": ["Dambai", "Jasikan", "Kadjebi", "Nkwanta", "Kete Krachi", "Worawora", "Likpe", "Chinderi", "Borae", "Ahenkro"],
        "Savannah": ["Damongo", "Bole", "Salaga", "Buipe", "Sawla", "Tuna", "Mpaha", "Bamboi", "Laribanga", "Debibi"],
        "Upper East": ["Bolgatanga", "Bawku", "Navrongo", "Paga", "Sandema", "Tumu", "Zebilla", "Garu", "Bongo", "Fumbisi"],
        "Upper West": ["Wa", "Jirapa", "Lambussie", "Lawra", "Nandom", "Tumu", "Gwollu", "Funsi", "Wechiau", "Kaleo"],
        "Volta": ["Ho", "Hohoe", "Kpando", "Aflao", "Anloga", "Sogakope", "Keta", "Adidome", "Peki", "Have"],
        "Western": ["Takoradi", "Sekondi", "Tarkwa", "Axim", "Elubo", "Kwesimintsim", "Agona Nkwanta", "Shama", "Prestea", "Dixcove"],
        "Western North": ["Wiawso", "Bibiani", "Enchi", "Juaboso", "Dadieso", "Bodi", "Debiso", "Akontombra", "Asawinso", "Sefwi Bekwai"]
    };
    console.log("Populating regions ...")

    // 2. Initialize Regions
    Object.keys(ghanaData).sort().forEach(region => {
        let opt = document.createElement('option');
        opt.value = region;
        opt.innerHTML = region;
        regionSelect.appendChild(opt);
    });

    regionSelect.addEventListener('change', function () {
        const selectedRegion = this.value;
        areaSelect.innerHTML = '<option value="" disabled selected>-- Select Area --</option>';
        
        if (ghanaData[selectedRegion]) {
            ghanaData[selectedRegion].sort().forEach(area => {
                let opt = document.createElement('option');
                opt.value = area;
                opt.innerHTML = area;
                areaSelect.appendChild(opt);
            });
            // Add 'Other'
            let otherOpt = document.createElement('option');
            otherOpt.value = "Other";
            otherOpt.innerHTML = "Other (Not listed)";
            areaSelect.appendChild(otherOpt);
        }
    });
    console.log("Regions populated. Waiting for user interaction...");

    // 3. Handle 'Other' Area Logic
    if (areaSelect) {
        areaSelect.addEventListener('change', function () {
            if (this.value === "Other") {
                otherContainer.style.display = 'block';
                otherInput.required = true;
                otherInput.focus();
            } else {
                otherContainer.style.display = 'none';
                otherInput.required = false;
            }
        });
    }

    // 4. Form Submission Logic (Existing)
    if (reportForm) {
        reportForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('../actions/submit_report_action.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        title: 'Report Received',
                        text: data.message,
                        icon: 'success',
                        background: '#1a1033', 
                        color: '#ffffff',
                        backdrop: 'rgba(15, 10, 30, 0.8)', 
                        confirmButtonColor: '#9d4edd', 
                        customClass: { popup: 'neon-border' }
                    }).then(() => {
                        window.location.href = '../user/dashboard.php';
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message,
                        icon: 'error',
                        background: '#1a1033',
                        color: '#ffffff',
                        confirmButtonColor: '#d33'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Could not connect to the server.', 'error');
            });
        });
    }
});

// Injecting CSS for SweetAlert
const style = document.createElement('style');
style.innerHTML = `
    .neon-border {
        border: 1px solid #bf40ff !important;
        box-shadow: 0 0 20px rgba(191, 64, 255, 0.4) !important;
        border-radius: 20px !important;
    }
`;
document.head.appendChild(style);