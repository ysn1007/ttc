<?php 
require_once 'admin/dbh.inc.php';
$id =  $_GET['id'];

$content = '';

include('./includes/header.php');
$result = getArticle($con, 1);
while($article = mysqli_fetch_assoc($result)) {
    if($article["id"] == $id) {
        $content .= '
        <div class="site-wrap">
            <div class="content-wrap">
                <section class="container post-group" id="post"> 
                    <div class="row">
                        <div class="section-header">
                            <a href="index.php"><img src="img/back-icon.png" alt="zurück icon" width="30">zurück</a>
                        </div>
                        <div class="head-content mb-4">';
                            if($article["imgPath"] != "") {
                                $content .= '
                                <div class="article-img mb-3" style="width: 100%; height: 500px; Background-image: url(./img/article/'. $article["imgPath"] .'); background-repeat: no-repeat; background-size: cover; background-position: top;"></div>';
                            } else {
                                $content .='
                                <div class="article-img mb-3" style="width: 100%; height: 200px; Background-image: url(img/tt-icon.svg); background-size: contain; background-repeat: no-repeat; background-position: top; margin-bottom: 30px;"></div>';
                            }
                            $content .= '
                            
                            <div class="headline"><h2>'. $article["headline"] .'</h2></div>';
                            if(isset($article["tagNews"]) || isset($article["tagReviews"]) || isset($article["tagPlayer"]) || isset($article["tagSocial"] )) {
                                $content .= '
                                <div class="post-tag">';
                                    if ($article["tagNews"] == 1) {
                                        $meldung = "Meldung";
                                        $content .= '
                                        <div class="tag-item '. $meldung .'">'. $meldung .'</div>';
                                    } 
                                    if ($article["tagReviews"] == 1) {
                                        $bericht = "Bericht";
                                        $content .= '
                                        <div class="tag-item '. $bericht .'">'. $bericht .'</div>';
                                    } 
                                    if ($article["tagPlayer"] == 1) {
                                        $neuzugang = "Neuzugang";
                                        $content .= '
                                        <div class="tag-item '. $neuzugang .'">'. $neuzugang .'</div>';
                                    } 
                                    if ($article["tagSocial"] == 1) {
                                        $social = "Social Media";
                                        $content .= '
                                        <div class="tag-item '. $social .'">'. $social .'</div>'; 
                                    } 
                                $content .= '
                                </div>';
                            }
                        $content .= '
                        </div>

                        <div class="content">
                            <p>'.$article["copytext"] .' </p>
                        </div>
                    </div>
                </section>
            </div>
        </div>';
        echo $content;
    }
    
} 
include('./includes/footer.php');