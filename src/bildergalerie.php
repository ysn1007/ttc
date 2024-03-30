<?php 
require_once 'admin/dbh.inc.php';
include('./includes/header.php');

$dekade = $_GET['dekade'];

$content = '';

$content .= '
<div class="site-wrap col-xs-12 col-sm-12 col-md-12">
    <div class="content-wrap new-player-wrap col-xs-12 col-md-12">
        <div class="row">
            <div class="col-xs-12 col-md-12 gal-img-group "> 
                <div class="row">';
                $res = getDekadeImages($con, $dekade);
                while($row = mysqli_fetch_assoc($res)){
                    if($dekade === $row['dekade']) {
                        $content .= '
                        <div class="col-xs-6 col-md-4 gal-img-item" id="gal-img-item">
                            
                            <div class="col-xs-12 img-item-body" id="img-item-body">
                                <div class="img-header">
                                    <!--h4>'. $row['title'] .'</h4-->
                                </div>
                                <div class="img-item">
                                    <img src="'. substr($row['imagePath'], 3 ) .'" title="'. $row['title'] .'" class="img" >
                                </div>
                                <div class="img-data">
                                    <h4>'. $row['title'] .'</h4>
                                    <!--div>'. $row['descript'] .'</div-->
                                    <!--div>'. $row['imageYear'] .'</div-->
                                </div>
                            </div>
                            
                        </div>';
                    }
                }

                $content .= '
                </div>
            </div>
        </div>
    </div> 
</div>';
echo $content;

include('./includes/footer.php');