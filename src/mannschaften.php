<?php 
require_once 'admin/includes/dbh.inc.php';
include('./includes/header.php');

// URL-Parameter sicher auslesen & parsen
$paramId = $_GET['id'] ?? '1herren';
$men = substr($paramId, 1, 6);
$teamNr = (int) substr($paramId, 0, 1);

$addTeam = $teamNr + 1;

// Spieler-Arrays holen
$players = getActivePlayersOfTeam($con, $teamNr);
// Spieler, die vorangig als Ersatz vorgesehen sind
$addPlayers = getActivePlayersOfTeam($con, $addTeam);

/*
 * Liga-Zuordnung
 */
$liga = match($teamNr) {
    1 => "Verbandsliga",
    2 => "1. Bezirksliga",
    3 => "1. Bezirksliga",
    4 => "2. Bezirksliga Gruppe A",
    5 => "1. Kreisklasse",
    default => "Unbekannte Liga"
};
?>

<div class="site-wrap">
    <div class="content-wrap">
        <section class="container team-wrap">
            <div class="galery-header">
                <img src="img/tt-icon.svg" alt="">
                <h1><?= htmlspecialchars($teamNr) ?>. <?= htmlspecialchars(ucfirst($men)) ?> - <?= $liga ?> </h1>
            </div>

            <div class="team-section">
                <div class="team-img-carousel">
                    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3" aria-label="Slide 4"></button>
                        </div>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="img/slider/x_slide1.jpg" class="d-block w-100" alt="...">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>First slide label</h5>
                                    <p>Some representative placeholder content for the first slide.</p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="img/slider/x_slide2.jpg" class="d-block w-100" alt="...">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Second slide label</h5>
                                    <p>Some representative placeholder content for the second slide.</p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="img/slider/x_slide3.jpg" class="d-block w-100" alt="...">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Third slide label</h5>
                                    <p>Some representative placeholder content for the third slide.</p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="img/slider/x_slide4.jpg" class="d-block w-100" alt="...">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Third slide label</h5>
                                    <p>Some representative placeholder content for the forth slide.</p>
                                </div>
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
                <div class="go-to-liveTT">
                    <a href="https://bezirk1.tischtennislive.de/?L1=Ergebnisse&amp;L2=TTStaffeln&amp;L2P=21685" target="_blank" rel="noopener norefferer" class="liveTT-link">Tischtennislive Tabelle ansehen</a>
                </div>
                <div class="row team-table-data justify-content-center">
                    <div class="team-data col-8 col-sm-10 col-md-8">
                        <div class="team-line-up">
                            
                            <div class="tab-header">
                                <ul class="team-group-header">
                                    <li class="team-header">
                                        <div class="team-data-header">
                                            <span class="position">Pos.</span> 
                                            <span>Name</span>
                                        </div>
                                        <div class="team-attr-header">
                                            <span class="ttrPoints">Punkte</span>
                                        </div>   
                                    </li>
                                </ul>
                            </div>

                            <ul class="team-group">
                                <?php foreach ($players as $player) : ?>
                                    <?php if ($player['position'] != 0) : ?>
                                        <li class="player-data">
                                            <div class="player">
                                                <div class="position">
                                                    <div class="pos-nr"><?= htmlspecialchars($player['position']) ?></div>
                                                </div>
                                                <div class="player-name">
                                                    <?= htmlspecialchars($player['Nachname']) ?>, <?= htmlspecialchars($player['Vorname']) ?>
                                                </div>
                                                <div class="player-attributes-group">
                                                    <span class="player-attributes-item">
                                                        <?= htmlspecialchars($player['livePZ']) ?>
                                                    </span>
                                                </div>   
                                            </div>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>

                                <?php if (!empty($addPlayers)) : ?> 
                                    <li class="reserve">Ersatzspieler</li>
                                    <?php foreach ($addPlayers as $addPlayer) : ?>
                                        <?php if ($addPlayer['position'] <= 2 && $addPlayer['position'] != 0) : ?>
                                            <li class="player-data">
                                                <div class="player">
                                                    <div class="position">
                                                        <div class="pos-nr"><?= htmlspecialchars($addPlayer['position']) ?></div>
                                                    </div>
                                                    <div class="player-name">
                                                        <?= htmlspecialchars($addPlayer['Nachname']) ?>, <?= htmlspecialchars($addPlayer['Vorname']) ?>
                                                    </div>
                                                    <div class="player-attributes-group">
                                                        <span class="player-attributes-item">
                                                            <?= htmlspecialchars($addPlayer['livePZ']) ?>
                                                        </span> 
                                                    </div>
                                                </div>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>

                        </div>
                    </div>
                </div>
                
            </div>
        </section>
    </div>
</div>

<?php
include('./includes/footer.php');
?>