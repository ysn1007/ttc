<?php
require_once 'admin/dbh.inc.php';
include('./includes/header.php');
$content = '';

$content .= '
<div class="site-wrap col-xs-12 col-sm-12 col-md-12">
    <div class="content-wrap col-xs-12 col-md-12">
        <div class="row">
            <section class="article-wrap col-xs-12 col-md-12 col-lg-12" id="article-wrap">
                <div class="row">
                    <div class="section-header">
                        <img src="img/tt-icon.svg" alt="">
                        <h2>Unsere Neuigkeiten</h2>
                    </div>';
                    $result = getActiveArticle($con, 1);
                    while($article = mysqli_fetch_assoc($result)) {
                        $content .= '
                        <section class="article-item col-xs-12 col-sm-6 col-md-6 col-lg-4">
                            <div class="article col-md-12">
                                <div class="row">
                                    <a href="post.php?id='.$article["id"].'">
                                        <!--div class="article-bg-img" style="width: 100%; height: 250px; Background-image: url(./img/article/'. $article["imgPath"] .'); background-size: cover; background-position: top;"></div-->
                                        <!--img src="img/article/'. $article["imgPath"] .'" alt="" width="100%"-->
                                        <div class="post-image article-bg-img"><img src="img/logo.svg" width="50"></div>
                                        <div class="article-content col-md-12">
                                            <div class="post-tag hidden">
                                                <div class="tag-item">'. $article["tags"] .'</div>
                                            </div>
                                            <h4>'. $article["headline"] .'</h4>
                                            <p>'. substr($article["copytext"], 0, 50) .'</p> 
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </section>';
                    }
                    $content .= '
                </div>
            </section>

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
            </section>
        </div>
    </div>
</div>

<!--div class="tables-wrap col-xs-12 col-md-12">
    <h3>Tabellen</h3>
    <div class="tables">
        <iframe src="https://bezirk1.tischtennislive.de/Ajax/Tischtennis/Tabelle_Mini.aspx?WettID=17792" width="300" height="300" id="ttLive-table"></iframe>
    </div>
</div-->';

echo $content;

include('./includes/footer.php');
