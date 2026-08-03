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

    /***
     * Admin: Social Media Manager (Create & Edit Mode)
     * Verwendung bei der Erstellung/Bearbeitung von Artikel mit Social Media Beiträgen
     *  
    */
    
    function initSocialMediaLogic() {
        const wrapper       = document.getElementById('social-wrapper');
        const select        = document.getElementById('social-platform');
        const input         = document.getElementById('social-url');
        const actionBtn     = document.getElementById('social-action-btn');
        const btnIcon       = document.getElementById('btn-icon');
        const tagsContainer = document.getElementById('social-tags-container');

        // 1. Daten initial auslesen (Data-Attribute oder Hidden-Inputs)
        try {
            const parsed = JSON.parse(wrapper.dataset.social || '{}');

            const getVal = (id) => {
                const el = document.getElementById(id);
                return el ? el.value : '';
            };

            savedLinks = {
                FB:  parsed.FB  || getVal('hidden-FB'),
                INS: parsed.INS || getVal('hidden-INS'),
                TT:  parsed.TT  || getVal('hidden-TT'),
                YT:  parsed.YT  || getVal('hidden-YT')
            };
            console.log(savedLinks);
        } catch(e) {
            console.error("Fehler beim Laden der Social-Media-Daten:", e);
        }
        
        // Entwurfs-Puffer für flüssiges Hin- und Herschalten im Select
        const draftInputs = { ...savedLinks };

        // 2. UI Status des Buttons aktualisieren (Zustände: Neu/Geändert -> '+', Gespeichert -> '✕', Leer -> ausblenden)
        function updateButtonState() {
            const currentPlatform = select.value;
            const typedValue = input.value.trim();
            const storedValue = (savedLinks[currentPlatform] || '').trim();

            if (storedValue !== '' && typedValue === storedValue) {
                // Zustand: Exakt so gespeichert -> Rotes X (Löschen anbieten)
                actionBtn.className = 'btn btn-danger';
                if (btnIcon) btnIcon.textContent = '✕';
            } else if (typedValue !== '') {
                // Zustand: Neu eingegeben oder verändert -> Blauer Plus-Button (Speichern anbieten)
                actionBtn.className = 'btn btn-primary';
                if (btnIcon) btnIcon.textContent = '+';
            } else {
                // Zustand: Feld leer und nix gespeichert -> Ausblenden
                actionBtn.className = 'btn d-none';
            }
        }

        // 3. Feldinhalt beim Plattformwechsel rendern
        function renderField() {
            const currentPlatform = select.value;
            input.value = draftInputs[currentPlatform] || '';
            updateButtonState();
        }

        // 4. Badges rendern & Hidden-Inputs synchronisieren
        function renderBadgesAndHiddenInputs() {
            if (tagsContainer) tagsContainer.innerHTML = '';

            Object.keys(savedLinks).forEach(p => {
                const val = savedLinks[p] ? savedLinks[p].trim() : '';

                // Hidden Input für PHP POST synchronisieren
                const hiddenInput = document.getElementById('hidden-' + p);
                if (hiddenInput) {
                    hiddenInput.value = val;
                }

                // Badge zeichnen, wenn ein Link vorhanden ist
                if (val !== '' && tagsContainer) {
                    const tag = document.createElement('span');
                    tag.className = 'badge social-tag-item p-2';
                    tag.innerHTML = `<span>${p}</span>`;
                    tagsContainer.appendChild(tag);
                }
            });
        }

        // --- EVENT LISTENERS ---

        // Plattformwechsel im Dropdown
        select.addEventListener('change', () => {
            renderField();
        });

        // Tippen im Input-Feld
        input.addEventListener('input', () => {
            const currentPlatform = select.value;
            draftInputs[currentPlatform] = input.value;
            updateButtonState();
        });

        // Klick auf den Action-Button (+ / ✕)
        actionBtn.addEventListener('click', (e) => {
            e.preventDefault(); // Verhindert ungewolltes Formular-Submit!

            const currentPlatform = select.value;
            const typedValue = input.value.trim();
            const storedValue = (savedLinks[currentPlatform] || '').trim();

            if (storedValue !== '' && typedValue === storedValue) {
                // Löschen-Aktion (wenn rotes X geklickt wird)
                savedLinks[currentPlatform] = '';
                draftInputs[currentPlatform] = '';
                input.value = '';
            } else if (typedValue !== '') {
                // Speichern/Update-Aktion (wenn Plus geklickt wird)
                savedLinks[currentPlatform] = typedValue;
                draftInputs[currentPlatform] = typedValue;
            }

            renderBadgesAndHiddenInputs();
            updateButtonState();
        });

        // Initiales Setzen beim Aufruf der Seite
        renderBadgesAndHiddenInputs();
        renderField();
    }

    // Ausführen mit 100ms Delay (schützt vor Überschreiben durch main.min.js)
   
      setTimeout(initSocialMediaLogic, 100);
   
   
});