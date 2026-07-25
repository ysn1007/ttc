<?php
require_once 'admin/includes/dbh.inc.php';
include('./includes/header.php');

$content = '';

$content .= '
<div class="site-wrap">
    <div class="content-wrap">';
        if($cfg["index-section"]["reviews"]["active"] == "on" ) {
            $content .= '
            <section class="article-wrap container" id="article-wrap">
                <div class="section-header">
                    <img src="img/tt-icon.svg" alt="">
                    <h2>Unsere Neuigkeiten</h2>
                </div>
                <div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-3">';
                    
                    $result = getActiveArticle($con);
                    while($article = mysqli_fetch_assoc($result)) {
                        $content .= '
                        <section class="article-item">
                            <div class="article">
                                <div class="row">
                                    <div class="post-tag">';
                                    if($article["tagNews"] === 1){
                                            $content .= '<div class="tag-item">Neues</div>';
                                    }
                                    if($article["tagReviews"] === 1){
                                            $content .= '<div class="tag-item">Bericht</div>';
                                    }
                                    if($article["tagPlayer"] === 1){
                                        $content .= '<div class="tag-item">Neuzugang</div>';
                                    }
                                    if($article["tagSocial"] === 1){
                                        $content .= '<div class="tag-item">Social</div>';
                                    }
                                    $content .= '    
                                    </div>
                                    <div class="post-image article-bg-img">';
                                        if($article["imgPath"] != "") {
                                            $content .= '
                                            <div class="article-img mb-3" style="width: 100%; height: 240px; Background-image: url(./img/article/'. $article["imgPath"] .'); background-repeat: no-repeat; background-size: cover; background-position: top;"></div>';
                                        } else {
                                            $content .= '
                                            <div class="article-img mb-3" style="width: 100%; height: 240px; Background-image: url(img/tt-icon.svg); background-size: contain; background-repeat: no-repeat; background-position: top; margin-bottom: 30px;"></div>';
                                        }
                                        $content .= '
                                    </div>
                                    <div class="article-content col-md-12">
                                        <h5>'. $article["headline"] .'</h5>
                                        <!--p>'. substr($article["copytext"], 0, 40) .' [...]</p--> 
                                    </div>

                                    <button type="button" class="btn btn-default" data-bs-toggle="modal" data-bs-target="#article-'.$article["id"].'">
                                        Artikel lesen <img src="./img/arrow.svg" width="15px">
                                    </button>
                                </div>
                            </div>
                        </section>
                        
                        
                        <!-- Modal -->
                        <div class="modal fade" id="article-'.$article["id"].'" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">'. $article["headline"] .'</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="article-img">';
                                        if($article["imgPath"] != "") {
                                            $content .= '
                                            <div class="article-img mb-3" style="width: 100%; height: 500px; Background-image: url(./img/article/'. $article["imgPath"] .'); background-repeat: no-repeat; background-size: cover; background-position: top;"></div>';
                                        } else {
                                            $content .= '
                                            <div class="article-img mb-3" style="width: 100%; height: 200px; Background-image: url(img/tt-icon.svg); background-size: contain; background-repeat: no-repeat; background-position: top; margin-bottom: 30px;"></div>';
                                        }

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

                                        <div  class="article-content">
                                        '.$article["copytext"].' 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';

                    }
                    $content .= '
                </div>
            </section>';
        }

        if($cfg["index-section"]["social"]["active"] == "on" ) {
            $content .= '
            <section class="social-wrap col-xs-12 col-md-12 col-lg-12" id="social-wrap"">
                <div class="row">
                    <div class="section-header">
                        <img src="img/tt-icon.svg" alt="">
                        <h2>Social Media</h2>
                    </div>

                    <div class="social-group">
                            Social Media
                    </div>
                </div>
            </section>';
        }
        $content .= '
    </div>
</div>';

echo $content;

include('./includes/footer.php');
