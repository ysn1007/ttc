<?php
/**
 * Bootstrap Gallery Modal Component
 * Expectations: $canDownload (bool)
 */
?>

<div class="modal fade" id="galleryModal" tabindex="-1" aria-labelledby="galleryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            
            <!-- Header: Titel & Close -->
            <div class="modal-header py-2">
                <h5 class="modal-title fs-6" id="galleryModalLabel">Bildtitel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>
            </div>

            <!-- Body: Hauptbild -->
            <div class="modal-body text-center p-0 bg-dark d-flex align-items-center justify-content-center" style="min-height: 400px;">
                <img id="modal-main-img" src="" alt="Galeriebild" class="img-fluid mh-100" style="max-height: 75vh; object-fit: contain;">
            </div>

            <!-- Footer: Beschreibung, Filter, Nav & Download -->
            <div class="modal-footer d-flex justify-content-between align-items-center py-2">
                
                <!-- Beschreibung -->
                <div id="modal-desc" class="text-truncate me-3" style="max-width: 40%;">
                    Bildbeschreibung
                </div>

                <!-- Controls -->
                <div class="d-flex align-items-center gap-3">

                    <!-- Navigation -->
                    <div class="btn-group btn-group-sm" role="group" aria-label="Slider Navigation">
                        <button id="modal-prev-btn" class="btn btn-outline-secondary">  
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0"/>
                            </svg>
                        </button>
                        <button id="modal-next-btn" class="btn btn-outline-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Download Button -->
                    <?php if (!empty($canDownload)) : ?>
                        <a id="modal-download-btn" href="#" class="btn btn-sm btn-outline-primary" title="Bild herunterladen" download>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                        </a>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>
</div>