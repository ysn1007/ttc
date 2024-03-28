<?php 
require_once 'admin/dbh.inc.php';
include('./includes/header.php');

var_dump($_GET['dekade']);
$dekade = $_GET['dekade'];
var_dump("DEKADE - ".$dekade. "<br>");
$content .= '
<div class="site-wrap col-xs-12 col-sm-12 col-md-12">
    <div class="content-wrap new-player-wrap col-xs-12 col-md-12">
        <div class="row">
            <div class="col-xs-12 col-md-12 gal-img-group"> ';
            $res = getGalleryImgData($con);
            if($res->num_rows > 0 ) {
                while($row = mysqli_fetch_assoc($res)){
                    
                    if($dekade == $row['dekade']) {
                        var_dump($dekade);
                        $content .'
                        <div class="col-xs-6 col-md-4 gal-img-item">
                            <img src="'. $row['imagePath'] .'" width="" >
                        </div>';
                    }
                }
            }

            $content .= '
            </div>
        </div>
    </div> 
</div>';
echo $content;

include('./includes/footer.php');