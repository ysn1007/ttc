<?php 
require_once 'admin/includes/dbh.inc.php';
include('./includes/header.php'); ?>


<div class="site-wrap">
    <div class="content-wrap">
        <section class="container ostercup-group">
            <div class="galery-header">
                <img src="img/tt-icon.svg" alt="">
                <h2>Ostercup 2026</h2>
            </div>

            <div class="cup-section">
                <div class="oc-img-carousel" id="oc-img-carousel">
                    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3" aria-label="Slide 4"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="4" aria-label="Slide 5"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="5" aria-label="Slide 6"></button>
                        </div>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="img/ostercup/2026/oc-img-1-2026.jpg" class="d-block w-100">
                            </div>
                            <div class="carousel-item">
                                <img src="img/ostercup/2026/oc-img-2-2026.jpg" class="d-block w-100">
                            </div>
                            <div class="carousel-item">
                                <img src="img/ostercup/2026/oc-img-3-2026.jpg" class="d-block w-100">
                            </div>
                            <div class="carousel-item">
                                <img src="img/ostercup/2026/oc-img-4-2026.jpg" class="d-block w-100">
                            </div>
                            <div class="carousel-item">
                                <img src="img/ostercup/2026/oc-img-5-2026.jpg" class="d-block w-100">
                            </div>
                            <div class="carousel-item">
                                <img src="img/ostercup/2026/oc-img-6-2026.jpg" class="d-block w-100">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
                <div class="ergebnisliste">
                    <h5>Alle Ergebnisse</h5>
                    <ul id="list" class="list-group">
                        <li class="list-item">
                            <a href="PDFs/ergebnisse_gesamt_2026.pdf" class="list-link" target="_blank" rel="noopener noreferrer"></a> Ergebnisse Gesamt 2026</a>
                        </li>
                        <li class="list-item">
                            <a href="PDFs/fr_boys_15_team_mit_ergebnissen.pdf" class="list-link" target="_blank" rel="noopener noreferrer"></a>FR Boys 15 Team mit Ergebnissen</a>
                        </li>
                        <li class="list-item">
                            <a href="PDFs/fr_boys_19_team_mit_ergebnissen.pdf" class="list-link" target="_blank" rel="noopener noreferrer"></a>FR Boys 19 Team mit Ergebnissen</a>
                        </li>
                        <li class="list-item">
                            <a href="PDFs/fr_girls_team_mit_ergebnissen.pdf" class="list-link" target="_blank" rel="noopener noreferrer"></a>FR Girls Team mit Ergebnissen</a>
                        </li>
                        <li class="list-item">
                            <a href="PDFs/sa_boys_15_trostrunde_mit_ergebnissen.pdf" class="list-link" target="_blank" rel="noopener noreferrer"></a>SA Boys 15 Trostrunde mit Ergebnissen</a>
                        </li>
                        <li class="list-item">
                            <a href="PDFs/sa_boys_19_trostrunde_mit_ergebnissen.pdf" class="list-link" target="_blank" rel="noopener noreferrer"></a>SA Boys 19 Trostrunde mit Ergebnissen</a>
                        </li>
                        <li class="list-item">
                            <a href="PDFs/sa_einzel_boys_15_mit_ergebnissen.pdf" class="list-link" target="_blank" rel="noopener noreferrer"></a>SA Einzel Boys 15 mit Ergebnissen</a>
                        </li>
                        <li class="list-item">
                            <a href="PDFs/sa_einzel_boys_19_mit_ergebnissen.pdf" class="list-link" target="_blank" rel="noopener noreferrer"></a>SA Einzel Boys 19 mit Ergebnissen</a>
                        </li>
                        <li class="list-item">
                            <a href="PDFs/sa_einzel_girls_mit_ergebnissen.pdf" class="list-link" target="_blank" rel="noopener noreferrer"></a>SA Einzel Girls mit Ergebnissen</a>
                        </li>
                        <li class="list-item">
                            <a href="PDFs/so_boys_15_team_1-9_mit_ergebnissen.pdf" class="list-link" target="_blank" rel="noopener noreferrer"></a>SO Boys 15 Team 1-9 mit Ergebnissen</a>
                        </li>
                        <li class="list-item">
                            <a href="PDFs/so_boys_15_team_10-15_mit_ergebnissen.pdf" class="list-link" target="_blank" rel="noopener noreferrer"></a>SO Boys 15 Team 10-15 mit Ergebnissen</a>
                        </li>
                        <li class="list-item">
                            <a href="PDFs/so_boys_19_team_1-12_mit_ergebnissen.pdf" class="list-link" target="_blank" rel="noopener noreferrer"></a>SO Boys 19 Team 1-12 mit Ergebnissen</a>
                        </li>
                        <li class="list-item">
                            <a href="PDFs/so_boys_19_team_13-18_mit_ergebnissen.pdf" class="list-link" target="_blank" rel="noopener noreferrer"></a>SO Boys 15 Team 13-18 mit Ergebnissen</a>
                        </li>
                        <li class="list-item">
                            <a href="PDFs/so_girls_1-9_mit_ergebnissen.pdf" class="list-link" target="_blank" rel="noopener noreferrer"></a>SO Girls 1-9 mit Ergebnissen</a>
                        </li>
                        <li class="list-item">
                            <a href="PDFs/so_girls_10-18_mit_ergebnissen.pdf" class="list-link" target="_blank" rel="noopener noreferrer"></a>SO Girls 10-18 mit Ergebnissen</a>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
        
    </div>
</div>
<?PHP include('./includes/footer.php'); ?>