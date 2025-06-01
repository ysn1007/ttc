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
<div class="site-wrap">
    <div class="content-wrap">
        <section class="container team-wrap">
            <div class="galery-header">
                <img src="img/tt-icon.svg" alt="">
                <h2>'. $teamNr .'. ' . ucfirst($men) . '</h2>
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

                <div class="row justify-content-center">
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
                                    
                                    <div class="player">
                                        <div class="position">
                                            <div class="pos-nr">'. $player['position'] .'</div>
                                        </div>
                                        <div class="player-name">'. $player['Vorname'] .", ". $player['Nachname'] .'</div>
                                        <div class="player-attributes-group">
                                            <span class="player-attributes-item player-info">'. (( $player['spv'] == 1 ) ? "<div class='checked'></div>" : "") .'</span>
                                            <span class="player-attributes-item player-info">'. (( $player['sbem'] == 1 ) ? "<div class='checked'></div>" : "").'</span>
                                            <span class="player-attributes-item player-info">'. (( $player['m/w'] == 1 ) ? "<div class='checked'></div>" : "").'</span>
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
                                        
                                        <div class="player">
                                            <div class="position">
                                                <div class="pos-nr">'. $addPlayer['position'] .'</div>
                                            </div>
                                            <div class="player-name">'. $addPlayer['Vorname'] .", ". $addPlayer['Nachname'] .'</div>
                                            <div class="player-attributes-group">
                                            <span class="player-attributes-item player-info">'. (( $addPlayer['spv'] == 1 ) ? "<div class='checked'></div>" : "").'</span>
                                            <span class="player-attributes-item player-info">'. (( $addPlayer['sbem'] == 1 ) ? "<div class='checked'></div>" : "").'</span>
                                            <span class="player-attributes-item player-info">'. (( $addPlayer['m/w']== 1  ) ? "<div class='checked'></div>" : "").'</span>
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
                </div>
                
            </div>
        </section>
    </div>
</div>';

echo $content;
 include('./includes/footer.php');