<?php 
require_once 'admin/dbh.inc.php';
include('./includes/header.php');

//var_dump($_GET['dekade']);
$dekade = $_GET['dekade'];

$content = '';

$content .= '
<div class="site-wrap col-xs-12 col-sm-12 col-md-12">
    <div class="content-wrap new-player-wrap col-xs-12 col-md-12">
        <div class="row">
            <div class="col-xs-12 col-md-12 gal-img-group"> 
                <div class="row">';
                $res = getDekadeImages($con, $dekade);
                //var_dump($res);
                while($row = mysqli_fetch_assoc($res)){
                    //var_dump($dekade);exit;
                    if($dekade === $row['dekade']) {
                        //var_dump($row);
                        $content .= '
                        <div class="col-xs-6 col-md-4 gal-img-item">
                            
                            <div class="col-xs-12 img-item-body">
                                <div class="img-header">
                                    <h3>'. $row['title'] .'</h3>
                                </div>
                                <div class="img-item">
                                    <img src="'. substr($row['imagePath'], 3 ) .'" class="img" >
                                </div>
                                <div class="img-data">
                                    <div>'. $row['descript'] .'</div>
                                    <div>'. $row['imageYear'] .'</div>
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