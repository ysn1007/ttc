<?php 
require_once 'admin/dbh.inc.php';
include('./includes/header.php');
$content = '';

$teamNr = $_REQUEST['id'];
$teamNr = substr($teamNr, 0,1);
$addTeam = $teamNr + 1;

$res = getPlayersOfTeam($con, $teamNr);
$addRes = getPlayersOfTeam($con, $addTeam);

var_dump($teamNr);
$content .='
<div class="site-wrap col-xs-12 col-sm-12 col-md-12 ">
    <div class="content-wrap col-xs-12 col-md-12">
        <div class="galery-header">
            <img src="img/tt-icon.svg" alt="">
            <h2>1. Herren</h2>
        </div>

        <section class="team-section col-xs-12">
            <div class="team-img col-xs-12 col-md-6">
                <img src="img/team.jpg" width="100%" alt="Team bild">
            </div>

            <div class="team-data col-xs-12 col-md-6">
                <div class="team-line-up col-xs-12 col-md-12">
                    <div class="tab-header">Verbandsliga Nord</div>
                    <ul class="team-group">
                        <li class="player-header">
                            <div class="player-data-header"><span class="position">Pos.</span> Name</div>
                            <div class="player-points-header"><span class="ttr-points">Punkte</span></div>   
                        </li>';

                        while($player = mysqli_fetch_assoc($res)){
                            if($player['team'] == 1) {
                                
                            $content .='
                            <li class="player-data">
                                <div class="position">
                                    <div class="pos-nr">'. $player['position'] .'</div>
                                </div>
                                <div class="player">
                                    <div class="player-name">'. $player['Vorname'] .",". $player['Nachname'] .'</div>
                                    <div class="player-points"><span class="ttr-points">'. $player['livePZ'] .'</span></div>   
                                </div>
                            </li>';
                            }
                        }   
                        $content .= '
                        <li role="separator" class="divider"></li>';
                        while($addPlayer = mysqli_fetch_assoc($addRes)){
                            $max = 2;
                            
                            if ($addPlayer['position'] <= $max) {
                                $content .='
                                <li class="player-data">
                                    <div class="position">
                                        <div class="pos-nr">'. $addPlayer['position'] .'</div>
                                    </div>
                                    <div class="player">
                                        <div class="player-name">'. $addPlayer['Vorname'] .",". $addPlayer['Nachname'] .'</div>
                                        <div class="player-points"><span class="ttr-points">'. $addPlayer['livePZ'] .'</span></div>   
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