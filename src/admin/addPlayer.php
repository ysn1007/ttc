<?php


session_start();
include('./components/header.php');

$content = '';
if(isset($_POST["submit"])) {
    //var_dump($_POST);exit();
    require_once 'dbh.inc.php';
    
    $name = $_POST["name"];
    $lastname = $_POST["lastname"];
    $livePZ = intval($_POST["livePZ"]);
    $team = intval($_POST["team"]);
    $position = intval($_POST["position"]);

    if(empty($_POST["active"])){
        $aactive = 0;
    } else {
        $aactive = 1;
    }
    
    if(empty($_POST["spv"])){
        $sspv = 0;
    } else {
        $sspv = 1;
    }
    
    if(empty($_POST["sbem"])){
        $ssbem = 0;
    } else {
        $ssbem = 1;
    }
    
    $active = $aactive;
    $spv = $sspv;
    $sbem = $ssbem;

    
    
    //var_dump($active, $spv, $sbem);exit();
    #, $lastname, $livePZ, $team, $position, $active, $spv, $sbem
    addPlayer($con, $name, $lastname);
    
}

if(isset($_SESSION["admin"]) || isset($_SESSION["manager"]) || isset($_SESSION["author"]) ){
    $content .= '
    <div class="col edit-player-section">
        <div class="card">
            <div class="card-header">
                <h4>Spieler bearbeiten <a href="index.ad.php" class="btn btn-danger float-end">Zurück</a></h4>
            </div>

            <div class="card-body">
                <form action="'.basename($_SERVER['PHP_SELF']).'" method="post" enctype="multipart/form-data">
                    
                    <div class="col-12 mb-3">
                        <div class="row">
                            <div class="col-4">
                                <label>Name</label><br>
                                <input class="form-control" type="text" name="name" placeholder="Vorname" >
                            </div>

                            <div class="col-4">
                                <label>Name</label><br>
                                <input class="form-control" type="text" name="lastname" placeholder="Nachname" >
                            </div>
                            
                            <div class="col-4">
                                <label>LivePZ</label><br>
                                <input class="form-control" type="text" name="livePZ" placeholder=" TT live Punkte" >
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <div class="row">
                            <div class="col-4">
                                <label>Team</label><br>
                                <input class="form-control" type="text" name="team" placeholder="Mannschaft" >
                            </div>
                            <div class="col-4">
                                <label>Position</label><br>
                                <input class="form-control" type="text" name="position" placeholder="Position">
                            </div>
                            <!--div class="col-4">
                                <label>E-Mail</label><br>
                                <input class="form-control" type="text" name="eMail" placeholder="eMail" >
                            </div-->
                            
                        </div>
                    </div>

                    <div class="col-12 mb-4">
                        <div class="row">
                            <div class="col-2">
                                <input type="checkbox" name="active"  checked>
                                <label class="mr-1" for="publish">Aktiv setzen</label>
                            </div>
                        
                            <div class="col-2">
                                <input type="checkbox" name="spv">
                                <label class="mr-1" for="publish">Sperrvermerk setzen</label>
                            </div>
                        
                            <div class="col-3">
                                <input type="checkbox" name="sbem">
                                <label class="mr-1" for="publish">Spieler mit Erwachsenenbetrieb setzen</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 edit-actions">
                        <div class="row">
                            <div class="col">
                                <button class="btn btn-primary" type="submit" name="submit">Spieler hinzufügen</button>    
                            </div>
                        </ div>
                    </div>
                </form> 
            </div>

        </div>
    </div>';
}


echo $content;
include('./components/footer.php');