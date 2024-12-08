<?php 
require_once 'admin/dbh.inc.php';
include('./includes/header.php');
$content = '';

$teamNr = $_REQUEST['id'];
$men = substr($teamNr, 1,6);
$teamNr = substr($teamNr, 0,1);

$addTeam = $teamNr + 1;

$res = getActivePlayersOfTeam($con, $teamNr);
$addRes = getActivePlayersOfTeam($con, $addTeam);

/*
*   liga
*/
$liga = "";
switch($teamNr) {
    case 1: $liga = "Verbandsliga";
    break;
    case 2: $liga = "1. Bezirksliga";
    break;
    case 3: $liga = "1. Bezirksliga";
    break;
    case 4: $liga = "2. Bezirksliga Herren B";
    break;
    case 5: $liga = "3. Kreisklasse"; 
}


$content .='
<div class="site-wrap col-xs-12 col-sm-12 col-md-12 ">
    <div class="content-wrap col-xs-12 col-md-12">
        <div class="galery-header">
            <img src="img/tt-icon.svg" alt="">
            <h2>'. $teamNr .'. ' . ucfirst($men) . '</h2>
        </div>

        <section class="team-section col-xs-12">
            <!--div class="team-img col-xs-12 col-md-6">
                <img src="" width="100%" alt="Team bild">
            </div-->
           
            <div class="team-data col-xs-12 col-md-12">
                <div class="team-line-up col-xs-12 col-md-12">
                    <div class="row">
                    <div class="tab-header">
                        <ul class="team-group-header">
                            <li class="team-header">
                                <div class="team-data-header">
                                    <span class="position">Pos.</span> 
                                    <span>Name</span>
                                </div>
                                <div class="team-attr-header">
                                    <span class="spv">spv</span>
                                    <span class="sbem">sbem</span>
                                    <span class="gender">m/w</span>
                                    <span class="ttrPoints">Punkte</span>
                                </div>   
                            </li>
                        </ul>
                    </div>
                    <ul class="team-group">';

                        while($player = mysqli_fetch_assoc($res)){
                            if($player['team'] == $teamNr && $player['position'] != 0) {
                            $content .='
                            <li class="player-data">
                                <div class="position">
                                    <div class="pos-nr">'. $player['position'] .'</div>
                                </div>
                                <div class="player">
                                    <div class="player-name">'. $player['Vorname'] .", ". $player['Nachname'] .'</div>
                                    <div class="player-attributes-group">
                                        <span class="player-attributes-item">'. (( $player['spv'] == 1 ) ? "<div class='checked'></div>" : "") .'</span>
                                        <span class="player-attributes-item">'. (( $player['sbem'] == 1 ) ? "<div class='checked'></div>" : "").'</span>
                                        <span class="player-attributes-item">'. (( $player['m/w'] == 1 ) ? "<div class='checked'></div>" : "").'</span>
                                        <span class="player-attributes-item">'. $player['livePZ'] .'</span>
                                    </div>   
                                </div>
                            </li>';
                            }
                        }
                        if(mysqli_num_rows($addRes) > 0) {   
                        $content .= '
                        <li class="reserve">Ersatzspieler</li>';
                        }
                        while($addPlayer = mysqli_fetch_assoc($addRes)){
                            $max = 2;
                            
                            if ($addPlayer['position'] <= $max && $addPlayer['position'] != 0) {
                                $content .='
                                <li class="player-data">
                                    <div class="position">
                                        <div class="pos-nr">'. $addPlayer['position'] .'</div>
                                    </div>
                                    <div class="player">
                                        <div class="player-name">'. $addPlayer['Vorname'] .", ". $addPlayer['Nachname'] .'</div>
                                        <div class="player-attributes-group">
                                        <span class="player-attributes-item">'. (( $addPlayer['spv'] == 1 ) ? "<div class='checked'></div>" : "").'</span>
                                        <span class="player-attributes-item">'. (( $addPlayer['sbem'] == 1 ) ? "<div class='checked'></div>" : "").'</span>
                                        <span class="player-attributes-item">'. (( $addPlayer['m/w']== 1  ) ? "<div class='checked'></div>" : "").'</span>
                                        <span class="player-attributes-item">'. $addPlayer['livePZ'] .'</span> 
                                    </div>
                                </li>';
                            }
                        }
                    $content .= '
                    </ul>
                </div>
                
                <!--div class="table-line-up col-xs-12 col-md-6">
                    Hier kommt die Tabelle für die liga.
                </div-->
            </div>
        </section>
    </div>
</div>';

echo $content;
 include('./includes/footer.php');