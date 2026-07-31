$(function() {
    // Prüft Existiert das Element auf der Seite? && Ist die Funktion owlCarousel überhaupt geladen?
    if ($('.owl-carousel').length && $.fn.owlCarousel) {
        // start owl slider
        // test ob änderungen gespeichert werden
        $("#index .owl-carousel").owlCarousel({
            items:1,
            loop:true,
            center:true,
            margin:10,
            autoplay: true,
        });

        $("#galerie .owl-carousel").owlCarousel({
            items:3,
            loop:true,
            center:true,
            margin:10,
            autoplay: true,
        });

        $("#ostercup .oc-img-carousel").owlCarousel({
            items:1,
            loop:true,
            center:true,
            margin:10,
            autoplay: true,
        });
    }

    // Social Media Links Section
    const platformSelect = document.getElementById('social-platform');
    const urlInput = document.getElementById('social-url');
    const actionBtn = document.getElementById('social-action-btn');
    const btnIcon = document.getElementById('btn-icon');
    const tagsContainer = document.getElementById('social-tags-container');
    const hiddenInputsContainer = document.getElementById('social-hidden-inputs');

    // Key-Value Speicher für die Links: { 'FB': 'http...', 'INS': 'http...' }
    let savedLinks = {};
    // Prüfen, ob die Elemente auf der aktuellen Seite überhaupt existieren (Schutz vor Null-Fehlern)
    if (platformSelect && urlInput && actionBtn) {
        
        // 1. Reagieren auf Eingaben im Textfeld & Plattformwechsel
        urlInput.addEventListener('input', updateUIState);
        platformSelect.addEventListener('change', loadPlatformState);
        actionBtn.addEventListener('click', handleButtonClick);

        function updateUIState() {
            console.log("updateUIState");
            const currentPlatform = platformSelect.value;
            const currentUrl = urlInput.value.trim();
            const isSaved = savedLinks.hasOwnProperty(currentPlatform);

            if (isSaved) {
                // Zustand 3: Bereits gespeichert -> Rotes X / Löschen-Button anzeigen
                actionBtn.className = 'btn btn-danger'; // Entfernt d-none vollständig!
                btnIcon.textContent = '✕';
            } else if (currentUrl.length > 0) {
                // Zustand 2: Text eingegeben -> Plus (+) Button zum Speichern anzeigen
                actionBtn.className = 'btn btn-primary'; // Zeigt den sichtbaren Plus-Button!
                btnIcon.textContent = '+';
            } else {
                // Zustand 1: Feld ist leer -> Button komplett ausblenden
                actionBtn.className = 'btn d-none';
            }
        }

        function loadPlatformState() {
            const selectedPlatform = platformSelect.value;
            
            if (savedLinks.hasOwnProperty(selectedPlatform)) {
                // Wenn für diese Plattform bereits ein Link existiert -> laden
                urlInput.value = savedLinks[selectedPlatform];
            } else {
                // Sonst -> Feld leeren (Zustand 1)
                urlInput.value = '';
            }
            updateUIState();
        }

        function handleButtonClick(e) {
            // Verhindert ungewolltes Submit des Hauptformulars!
            e.preventDefault(); 

            const currentPlatform = platformSelect.value;
            const currentUrl = urlInput.value.trim();

            if (savedLinks.hasOwnProperty(currentPlatform)) {
                // Wenn bereits gespeichert war -> LÖSCHEN
                delete savedLinks[currentPlatform];
                urlInput.value = '';
            } else if (currentUrl.length > 0) {
                // Wenn neu eingegeben -> SPEICHERN
                savedLinks[currentPlatform] = currentUrl;
            }

            renderBadgesAndHiddenInputs();
            updateUIState();
        }

        function renderBadgesAndHiddenInputs() {
            tagsContainer.innerHTML = '';
            hiddenInputsContainer.innerHTML = '';

            Object.keys(savedLinks).forEach((platform) => {
                const url = savedLinks[platform];

                // Badge rendern (unter dem Formular)
                const badge = document.createElement('span');
                badge.className = 'social-badge';
                badge.textContent = platform;
                tagsContainer.appendChild(badge);

                // Versteckte Input-Felder für PHP erzeugen
                hiddenInputsContainer.innerHTML += `
                    <input type="hidden" name="social_platform[]" value="${platform}">
                    <input type="hidden" name="social_url[]" value="${url}">
                `;
            });
        }
    }
   
});