<?php
require_once 'admin/includes/dbh.inc.php';
include('./includes/header.php');
?>

<div class="site-wrap">
    <div class="content-wrap">
        <?php if($cfg["index-section"]["reviews"]["active"] == "on" ) : ?>
        
            <section class="article-wrap container" id="article-wrap">
                <div class="section-header">
                    <img src="img/tt-icon.svg" alt="">
                    <h2>Unsere Neuigkeiten</h2>
                </div>
                <div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-3">
                    <?php    
                    $articles = getActiveArticle($con);
                    foreach ($articles as $article) : ?>
                        <section class="article-item">
                            <div class="article">
                                <div class="row">
                                    <div class="post-tag">
                                    <?php if(!empty($article["tagNews"]) && $article["tagNews"] == 1) : ?>
                                        <div class="tag-item">Neues</div>
                                    <?php endif; ?>
                                    <?php if(!empty($article["tagReviews"]) && $article["tagReviews"] == 1) : ?>
                                        <div class="tag-item">Bericht</div>
                                    <?php endif; ?>
                                    <?php if(!empty($article["tagPlayer"]) && $article["tagPlayer"] == 1) : ?>
                                       <div class="tag-item">Neuzugang</div>
                                    <?php endif; ?>
                                    <?php if(!empty($article["tagSocial"]) && $article["tagSocial"] == 1) : ?>
                                        <div class="tag-item">Social</div>
                                    <?php endif; ?>    
                                    </div>
                                    <div class="post-image article-bg-img">
                                    <?php if($article["imgPath"] != "") : ?>
                                        <div class="article-img mb-3" style="width: 100%; height: 240px; Background-image: url(./img/article/<?= $article["imgPath"] ?>); background-repeat: no-repeat; background-size: cover; background-position: top;"></div>
                                    <?php else : ?>
                                        <div class="article-img mb-3" style="width: 100%; height: 240px; Background-image: url(img/tt-icon.svg); background-size: contain; background-repeat: no-repeat; background-position: top; margin-bottom: 30px;"></div>
                                    <?php endif ?>
                                    </div>
                                    <div class="article-content col-md-12">
                                        <h5><?= $article["headline"] ?></h5>
                                    </div>

                                    <button type="button" class="btn btn-default" data-bs-toggle="modal" data-bs-target="#article-<?= $article["id"] ?>">
                                        Artikel lesen <img src="./img/arrow.svg" width="15px">
                                    </button>
                                </div>
                            </div>
                        </section>
                        
                        
                        <!-- Modal -->
                        <div class="modal fade" id="article-<?= $article["id"] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel"><?= $article["headline"] ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="article-img">
                                        <?php if($article["imgPath"] != "") : ?>
                                            <div class="article-img mb-3" style="width: 100%; height: 500px; Background-image: url(./img/article/<?= $article["imgPath"] ?>); background-repeat: no-repeat; background-size: cover; background-position: top;"></div>
                                        <?php else : ?>
                                            <div class="article-img mb-3" style="width: 100%; height: 200px; Background-image: url(img/tt-icon.svg); background-size: contain; background-repeat: no-repeat; background-position: top; margin-bottom: 30px;"></div>
                                        <?php endif; ?>

                                        <?php if(!empty($article["tagNews"]) || !empty($article["tagReviews"]) || !empty($article["tagPlayer"]) || !empty($article["tagSocial"] )) : ?>
                                            
                                            <div class="post-tag">
                                            <?php if (!empty($article["tagNews"]) && $article["tagNews"] == 1) : ?>
                                                <div class="tag-item meldung">Meldung</div>
                                            <?php endif; ?>
                                            
                                            <?php if ( !empty($article["tagReviews"]) && $article["tagReviews"] == 1) : ?>
                                                <div class="tag-item bericht">Bericht</div>
                                            <?php endif; ?>

                                            <?php if (!empty($article["tagPlayer"]) && $article["tagPlayer"] == 1) : ?>
                                                <div class="tag-item neuzugang">Neuzugang</div>
                                            <?php endif; ?>

                                            <?php if (!empty($article["tagSocial"]) && $article["tagSocial"] == 1) : ?>
                                                <div class="tag-item social-media">Social Media</div> 
                                            <?php endif; ?>
                                            
                                            </div>
                                        <?php endif; ?>
                                       
                                        </div>

                                        <div  class="article-content">
                                        <?= $article["copytext"] ?> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                </div>
            </section>
            
        <?php endif; ?>

        <?php if($cfg["index-section"]["social"]["active"] == "on" ) : ?>
            
            <section class="social-wrap col-xs-12 col-md-12 col-lg-12" id="social-wrap">
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
        <?php endif; ?>
        
    </div>
</div>
<?php
include('./includes/footer.php');
?>
