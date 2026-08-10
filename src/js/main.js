$(function() {
    // Prüft: Existiert das Element auf der Seite? && Ist owlCarousel geladen?
    if ($('.owl-carousel').length && $.fn.owlCarousel) {
        $("#index .owl-carousel").owlCarousel({
            items: 1,
            loop: true,
            center: true,
            margin: 10,
            autoplay: true,
        });

        $("#galerie .owl-carousel").owlCarousel({
            items: 3,
            loop: true,
            center: true,
            margin: 10,
            autoplay: true,
        });

        $("#ostercup .oc-img-carousel").owlCarousel({
            items: 1,
            loop: true,
            center: true,
            margin: 10,
            autoplay: true,
        });
    }   
});

let galleryModal = null;
let currentImageIndex = 0;
let imageIdList = [];

// Prüfung und Aufruf einer Modalinstanz
function getGalleryModalInstance() {
    if (!galleryModal) {
        const modalEl = document.getElementById("galleryModal");
        if (modalEl && typeof bootstrap !== 'undefined') {
            galleryModal = new bootstrap.Modal(modalEl);
        }
    }
    return galleryModal;
}

// Übergibt Daten zur Darstellung an das Modal weiter
function renderModalContent(id) {
    if (typeof galleryImages === 'undefined' || !galleryImages || !galleryImages[id]) {
        console.error(`Keine Bilddaten für ID ${id} in galleryImages gefunden:`, typeof galleryImages !== 'undefined' ? galleryImages : 'galleryImages ist undefined');
        return false;
    }

    const imageData = galleryImages[id];

    const titleEl = document.getElementById('galleryModalLabel');
    const imgEl = document.getElementById('modal-main-img');
    const descEl = document.getElementById('modal-desc');
    const downloadBtn = document.getElementById('modal-download-btn');

    if (titleEl) titleEl.innerText = imageData.title || '';
    if (imgEl) imgEl.src = imageData.src || '';
    if (descEl) descEl.innerText = imageData.description || '';
    if (downloadBtn) downloadBtn.href = imageData.src || '#';

    return true;
}

// Navogationskontroller
function navigateGallery(direction) {
    if (imageIdList.length === 0) return;

    currentImageIndex = (currentImageIndex + direction + imageIdList.length) % imageIdList.length;
    const nextId = imageIdList[currentImageIndex];
    
    renderModalContent(nextId);
}

// Globale js Aufruffunktion
window.openGallerySlider = function(id) {
    if (typeof galleryImages === 'undefined' || !galleryImages) {
        console.error("Fehler: 'galleryImages' ist auf der Seite nicht definiert.");
        return;
    }

    imageIdList = Object.keys(galleryImages);
    currentImageIndex = imageIdList.indexOf(String(id));

    if (currentImageIndex === -1) currentImageIndex = 0;

    if (renderModalContent(id)) {
        const modal = getGalleryModalInstance();
        if (modal) {
            modal.show();
        } else {
            console.error("Fehler: #galleryModal Element wurde nicht im HTML gefunden.");
        }
    }
};

// Event-Listener nach DOM-Laden
document.addEventListener("DOMContentLoaded", () => {
    getGalleryModalInstance();

    // Klick auf über js-gallery-trigger Auslöser
    $(document).on('click', '.js-gallery-trigger', function(e) {
        e.preventDefault();
        const imgId = $(this).data('img-id');
        window.openGallerySlider(imgId);
    });

    // Klick auf Vor/Zurück Buttons
    $(document).on('click', '#modal-prev-btn', function(e) {
        e.preventDefault();
        navigateGallery(-1);
    });

    $(document).on('click', '#modal-next-btn', function(e) {
        e.preventDefault();
        navigateGallery(1);
    });

    // Tastatur-Navigation (Pfeiltasten)
    document.addEventListener('keydown', (e) => {
        const modalEl = document.getElementById("galleryModal");
        if (modalEl && modalEl.classList.contains('show')) {
            if (e.key === 'ArrowLeft') navigateGallery(-1);
            if (e.key === 'ArrowRight') navigateGallery(1);
        }
    });
});