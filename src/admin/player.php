<?php
require_once 'dbh.inc.php';
include('./components/header.php');

$content .= '';

if(isset($_SESSION["admin"]) || isset($_SESSION["manager"]) ) {
$content .= '
<div class="accordion" id="accordionExample-2">
    <section class="img-group-header d-flex justify-content-between align-items-center mb-3 p-4">
        <div class="dekades">Anzahl Spieler gespeichert</div>
        <div class="add-img add-item">
            <a class="btn btn-primary" href="addPlayer.php">Spieler hinzufügen</a>
        </div>
    </section>
    <div class="accordion-item">
        <h2 class="accordion-header">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
            Spieler
        </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                
                <form action="editPlayer.php" method="post" enctype="multipart/form-data">
                    
                    <table class="tbl table table-light table-striped"> 
                        <thead>
                            <tr>
                                <th scope="col">Nr.</th>
                                <th scope="col">Nachname,Name</th>
                                <th scope="col">LivePZ</th>
                                <th scope="col">Team</th>
                                <th scope="col">Position</th>
                                <th scope="col">aktiv</th>
                                <th scope="col">Spv</th>
                                <th scope="col">Sbem</th>';
                                if(isset($_SESSION["admin"])) {
                                    $content .= '<th>Aktion</th>';
                                }
                                $content .= '
                            </tr>
                        </thead>

                        <tbody>';
                            $playerData = getPlayers($con);
                            while($players = mysqli_fetch_assoc($playerData)) {
                                $content .= '
                                    <tr>
                                        <th scope="row">'. $players["id"].'</th>
                                        <td><input type="text" name="name" value="'. $players["Vorname"] .", ". $players["Nachname"] .'"></td>
                                        <td><input type="text" name="livepz" value="'. $players["livePZ"] .'"></td>
                                        <td><input type="text" name="team" value="'. $players["team"] .'"></td>
                                        <td><input type="text" name="position" value="'. $players["position"] .'"></td>
                                        <td><input type="text" name="position" value="'. $players["aktiv"] .'"></td>
                                        <td><input type="text" name="spv" value="'. $players["spv"] .'"></td>
                                        <td><input type="text" name="sbem" value="'. $players["sbem"] .'"></td>';
                                        if(isset($_SESSION["admin"])) {
                                            $content .= '<td><a href="editPlayer.php?id='. $players["id"] .'" class="btn btn-success">Bearbeiten</a>';
                                        }
                                        $content .='
                                    </tr>';
                            }
                        $content .= '
                        </tbody>
                    </table>
                </form>
            
            </div>
        </div>
    </div>
</div>';
}

echo $content;
include('./components/footer.php');