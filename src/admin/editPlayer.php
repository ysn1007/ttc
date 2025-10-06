<?php
ob_start(); // Am Anfang deiner Datei
require_once 'dbh.inc.php';
//session_start();
$content = '';

if($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST["updatePlayer"])) {
        
        $playerId = intval($_POST["player_id"]);
        $name = $_POST["name"];
        $lastname = $_POST["lastname"];
        $livePZ = intval($_POST["livePZ"]);
        $team = intval($_POST["team"]);
        $position = intval($_POST["position"]);

        if($_POST['active'] == "on") {
            $active = 1;
        } else {
            $active = 0;
        }

        updatePlayer($con, $playerId, $name, $lastname, $livePZ, $team, $position, $active, $spv, $sbem );
    }

    if(isset($_POST["deletePlayer"])) {
        $pid = $_POST['player_id'];
        deletePlayer($con, $pid );
    }
}

include('./components/header.php');

if(isset($_SESSION["admin"]) || isset($_SESSION["manager"]) ){
    $playerId = isset($_GET['id']) ? (int)$_GET['id'] : 0; 

    if($playerId <= 0){
        die("Ungültige Spieler ID"); //Fehlerbehandlung bei ungültiger ID
    }

    $playerData = getPlayersId($con, $playerId);
    $player = $playerData; // Zugriff auf das erste (und einzige) Element des Arrays

    $content .= '
    <div class="col edit-player-section">
        <div class="card">
            <div class="card-header">
                <h4>Spieler bearbeiten <a href="javascript:history.go(-1)" class="btn btn-danger float-end">Zurück</a></h4>
            </div>';
           
            if($player[0]["aktiv"] == 1) {
                $active = "checked";
            } else {
                $active = " ";
            }
            
            $content .= '
            <div class="card-body">
                <form action="'.basename($_SERVER['PHP_SELF']).'" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="player_id" value="'.$playerId.'" >
                    
                    
                    <div class="col-12 mb-3">
                        <div class="row">
                            <div class="col-4">
                                <label>Name</label><br>
                                <input class="form-control" type="text" name="name" placeholder="Vorname" value="'.$player[0]["Vorname"].'">
                            </div>

                            <div class="col-4">
                                <label>Name</label><br>
                                <input class="form-control" type="text" name="lastname" placeholder="Nachname" value="'.$player[0]["Nachname"].'">
                            </div>
                            
                            <div class="col-4">
                                <label>LivePZ</label><br>
                                <input class="form-control" type="text" name="livePZ" placeholder=" TT live Punkte" value="'.$player[0]["livePZ"].'">
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <div class="row">
                            <div class="col-4">
                                <label>Team</label><br>
                                <input class="form-control" type="text" name="team" placeholder="Mannschaft" value="'.$player[0]["team"].'">
                            </div>
                            <div class="col-4">
                                <label>Position</label><br>
                                <input class="form-control" type="text" name="position" placeholder="Position" value="'.$player[0]["position"].'">
                            </div>
                            
                            
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <div class="row">
                            <div class="col-12">
                                <label>Status</label><br>    
                                <div>Spieler ist '.(($active == "checked")? "Aktiv":"inaktiv").'</div><br>    
                            </div>
                        </div>
                    </div>
                        
                    <div class="col-12 mb-3">
                        <div class="row">
                            <div class="col-2">
                                <input type="checkbox" name="active" '. $active .' >
                                <label class="mr-1" for="publish">'.(($active == "checked")? "Inaktiv setzen" : "Aktiv setzen").'</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 edit-actions">
                        <div class="row">
                            <div class="col">
                                <button class="btn btn-primary" type="updatePlayer" name="updatePlayer">Aktualisieren</button>    
                            </div>
                            <div class="col">
                                <button class="btn btn-danger" type="deletePlayer" name="deletePlayer">Spieler löschen </button>
                            </div>
                        </ div>
                    </div>
                </form> 
            </div>';
            
        $content .= '
        </div>
    </div>';
}


echo $content;
include('./components/footer.php');