<?php 
require_once 'admin/dbh.inc.php';
include('./includes/header.php');

$dekade = $_GET['dekade'];
//var_dump($dekade);
$content = '';
$res = getDekadeImages($con, $dekade);

$content .= '
<div class="site-wrap col-xs-12 col-sm-12 col-md-12">
    <div class="content-wrap new-player-wrap col-xs-12 col-md-12">
        <div class="row">

            <section class="col-xs-12 col-md-12 gal-img-group" id="gal-group"> 
                <div class="galery-header">
                    <img src="img/tt-icon.svg" alt="">
                    <h2>Bildergallarie '. $dekade .'</h2>
                </div>

                <div class="row">';
                if(!$row['num_rows'] = 0) {
                    while($row = mysqli_fetch_assoc($res)){
                    
                        if($dekade === $row['dekade']) {
                            $content .= '
                            <div class="col-xs-12 col-md-4 gal-img-item" id="gal-img-item">
                                <div class="col-xs-12 img-item-body" id="img-item-body">
                                    <!--div class="img-header">
                                        <h4>'. $row['title'] .'</h4>
                                    </div-->
                                    <div class="img-item bl">
                                        <img src="'. substr($row['imagePath'], 3 ) .'" title="'. $row['title'] .'" class="img" loading="lazy">
                                    </div>
                                    <div class="img-data">
                                        <!--h4>Im Jahr '. $row['imageYear'] . " " .  (($row['title'] != " ") ? $row['title'] : "Keine weiteren Angaben.") .'</h4-->
                                        <!--div>'. $row['descript'] .'</div-->
                                        <!--div>'. $row['imageYear'] .'</div-->
                                    </div>
                                </div>
                            </div>';
                        }
                        
                    }
                } else {

                    $content .= '
                    <div class="col-xs-12 gal-img-item" id="gal-img-item">
                        <div class="col-xs-12 img-item-body" id="img-item-body">
                            <p>Bitte prüfe deine Eingabe, es wurden keine Daten erhalten.</p>
                        </div>
                    </div';
                }

                $content .= '
                    
                </div>
            </section>
           
        </div>
    </div> 
     
</div>
// script unter dem full image teil hinzufügen

<script type="text/javascript">
    
    function fullImageView(imgSrc) {
        //alert(imgSrc);
        // noch etwas text
        document.getElementById("full-scale-img").innerHTML += `<img src="`+imgSrc+`">`;
        document.getElementById("fullImageView").style.display = "block";
    }

</script>

';
echo $content;

include('./includes/footer.php');