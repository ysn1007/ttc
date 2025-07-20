<?php
require_once 'dbh.inc.php';

$content = '';
//var_dump($_SESSION['useruid']);exit;

if(!isset($_SESSION["useruid"])) {
    echo "Verbindungsfehler: 500";
} else {
    include('./components/header.php');
        if(isset($_SESSION["admin"])) {
            $content .= '
            <div class="col-12" id="individual-cards">
                <div class="row">
                    <div class="col col-sm-12 col-md-6 col-lg-4" id="player-panel">
                        <div class="card">
                            <div class="card-header">
                                <h4>Alle Spieler</h4>
                            </div>

                            <div class="card-body">
                                <ul class="list-group list-group-flush">';
                                $allPlayerData = getAllPlayers($con);

                                if ($allPlayerData) { //Überprüfen ob Daten vorhanden sind
                                    foreach ($allPlayerData as $player) {
                                        $content .= '
                                        <a href="player.php?#' . $player["id"] . '" class="list-group-item">
                                            ' . $player['Nachname'] . ' ' . $player['Vorname'] . '
                                        </a>';
                                    }
                                } else {
                                    $content = "<p>Fehler beim Laden der Spielerdaten.</p>"; //Fehlermeldung anzeigen
                                }

                            $content .= '
                                </ul>
                            </div>
                            <div class="card-footer">
                                <a class="btn btn-primary" href="addPlayer.php">Spieler hinzufügen</a>
                            </div>
                        </div>
                    </div>


                    <div class="col col-sm-12 col-md-6 col-lg-4" id="article-panel">
                        <div class="card">
                            <div class="card-header">
                                <h4>Alle Artikel</h4>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">';
                                    $result = getArticle($con);
                                    while($article = mysqli_fetch_assoc($result)) {
                                        $content .= '
                                        <a href="#" class="list-group-item">
                                            <!--img src="../img/article/'.$article["imgPath"].'" width="50" height="50"/--> 
                                            <span>'. $article["headline"] .'</span> 
                                        </a>';

                                    }
                                $content .= '
                                </ul>
                            </div>
                            <div class="card-footer">
                                <a class="btn btn-primary" href="addArticle.php">Artikel hinzufügen</a> 
                            </div>
                        </div>
                    </div>
                    <div class="col col-sm-12 col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>Letzte hochgeladene Bilder</h4>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">';
                                    $res = getLastImages($con);
                                    while($row = $res->fetch_assoc()){
                                        $content .='
                                        <a href="#" class="list-group-item">
                                            <img src="'.$row['imagePath'].' " width="50px">
                                            <span>'.((strlen($row['title']) > 1) ? $row['title'] : "Ohne Titel").'</span>
                                        </a>';
                                    }
                                $content .= '
                                </ul>
                            </div>
                            <div class="card-footer">
                                <a class="btn btn-primary" href="addImg.php">Bild hinzufügen</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        }   
    echo $content;
    include('./components/footer.php');
}

?>


