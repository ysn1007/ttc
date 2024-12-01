<?php
require_once 'admin/dbh.inc.php';
require_once 'config.php';

//echo "root :". __ROOT__;


include('./includes/header.php');

$content = '';

$content .= '
<div class="site-wrap col-xs-12 col-sm-12 col-md-12">
    <div class="content-wrap col-xs-12 col-md-12">
        <div class="row">';

            if($cfg["reviews"]["active"] == "on" ) {
                $content .= '
                <section class="article-wrap col-xs-12 col-md-12 col-lg-12" id="article-wrap">
                    <div class="row">
                        <div class="section-header">
                            <img src="img/tt-icon.svg" alt="">
                            <h2>Unsere Neuigkeiten</h2>
                        </div>';
                       
                        $result = getActiveArticle($con);
                        while($article = mysqli_fetch_assoc($result)) {
                            $content .= '
                            <section class="article-item col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                <div class="article col-md-12">
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
                                        <div class="post-image article-bg-img">
                                            <img src="img/tt-icon.svg" width="50" loading="lazy">
                                        </div>
                                        <div class="article-content col-md-12">
                                            
                                            <h4>'. $article["headline"] .'</h4>
                                            <!--p>'. substr($article["copytext"], 0, 40) .' [...]</p--> 
                                        </div>
                                       
                                        <div class="col-xs-12 more text-right ">
                                            <a class="btn btn-default" href="post.php?id='.$article["id"].'">Weiter lesen</a>
                                        </div>
                                    </div>
                                </div>
                            </section>';

                        }
                        $content .= '
                    </div>
                </section>';
            }

            if($cfg["social"]["active"] == "on" ) {
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
    </div>
</div>';

echo $content;

include('./includes/footer.php');
