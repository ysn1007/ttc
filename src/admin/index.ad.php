<?php
require_once 'includes/dbh.inc.php';

// 1. Sicherheitscheck mit sofortigem Abbruch
if (!isset($_SESSION["useruid"])) {
    echo "Verbindungsfehler: 500";
    exit(); // Verhindert, dass der Rest der Seite geladen wird
}

include('./components/header.php');
?>

<?php if (isset($_SESSION["admin"])) : ?>
           
    <div class="index-header">
        <h4>Dein Überblick</h4>
    </div>

    <div class="col-12" id="individual-cards">
        <div class="row">

            <!-- SPIELER PANEL -->
            <div class="col col-sm-12 col-md-6 col-lg-4" id="player-panel">
                <div class="card">
                    <div class="card-header">
                        <h4>Alle Spieler</h4>
                    </div>

                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <?php 
                            // Wir nutzen getPlayers statt getAllPlayers (mit flexiblem Limit, z. B. 10)
                            $players = getPlayers($con, 10); 
                            ?>
                            <?php if (!empty($players)) : ?> 
                                <?php foreach ($players as $player) : ?>
                                    <a href="player.php?#<?= htmlspecialchars($player["id"]) ?>" class="list-group-item">
                                        <?= htmlspecialchars($player['Nachname']) ?>, <?= htmlspecialchars($player['Vorname']) ?>
                                    </a>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <p class="p-3">Keine Spieler vorhanden.</p>
                            <?php endif; ?>
                        </ul>
                    </div>
                    
                    <div class="card-footer">
                        <a class="btn btn-primary" href="addPlayer.php">Spieler hinzufügen</a>
                    </div>
                </div>
            </div>


            <!-- ARTIKEL PANEL -->
            <div class="col col-sm-12 col-md-6 col-lg-4" id="article-panel">
                <div class="card">
                    <div class="card-header">
                        <h4>Alle Artikel</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <?php $articles = getArticle($con, 10); ?>
                            <?php if (!empty($articles)) : ?>
                                <?php foreach ($articles as $article) : ?>
                                    <a href="#" class="list-group-item">
                                        <span><?= htmlspecialchars($article["headline"]) ?></span> 
                                    </a>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <p class="p-3">Keine Artikel vorhanden.</p>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <div class="card-footer">
                        <a class="btn btn-primary" href="addArticle.php">Artikel hinzufügen</a> 
                    </div>
                </div>
            </div>


            <!-- BILDER PANEL -->
            <div class="col col-sm-12 col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Letzte hochgeladene Bilder</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <?php $images = getImages($con, 10, true); ?>
                            <?php if (!empty($images)) : ?>
                                <?php foreach ($images as $image) : ?>
                                    <a href="#" class="list-group-item">
                                        <img src="<?= htmlspecialchars($image['imagePath']) ?>" width="50px">
                                        <span><?= (strlen($image['title']) > 1) ? htmlspecialchars($image['title']) : "Ohne Titel" ?></span>
                                    </a>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <p class="p-3">Keine Bilder vorhanden.</p>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <div class="card-footer">
                        <a class="btn btn-primary" href="addImg.php">Bild hinzufügen</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php include('./components/footer.php'); ?>

<?php endif; ?>