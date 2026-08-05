<?php
require_once 'admin/includes/dbh.inc.php';
include('./includes/header.php');
?>

<div class="site-wrap">
    <div class="content-wrap">
        <?php if($cfg["index-section"]["reviews"]["active"]) : ?>
        
            <section class="article-wrap container" id="article-wrap">
                <div class="section-header">
                    <img src="img/tt-icon.svg" alt="">
                    <h1>Unsere Neuigkeiten</h1>
                </div>
                <div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-3">
                    <?php    
                    $limit = $cfg["index-section"]["reviews"]["limit"];
                    $articles = getActiveArticle($con, $limit);
                    foreach ($articles as $article) : ?>
                        <!--?php
                        var_dump($article);
                        ?-->
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
                                        <h5 class="titel"><?= $article["headline"] ?></h5>
                                        <span class="datum"><?= date('d.m.y', strtotime($article["article_date"])); ?></span>
                                    </div>

                                    <?php 
                                    // 1. Sicheres Auslesen der Config-Einstellungen
                                    $isSocialActive = $cfg["social-media"]["active"] ?? false;

                                    // Falls du eine separate Channels-Config hast, nutzt er diese, sonst erlaubt er sie standardmäßig
                                    $channels = $cfg["social-media"]["channels"] ?? [
                                        "facebook"  => true,
                                        "instagram" => true,
                                        "youtube"   => true,
                                        "tiktok"    => true
                                    ];

                                    // 2. Prüfen, ob für den Artikel mindestens ein gültiger Link vorhanden ist
                                    $hasFb  = !empty($channels["facebook"])  && !empty($article['social']['FB']);
                                    $hasIns = !empty($channels["instagram"]) && !empty($article['social']['INS']);
                                    $hasYt  = !empty($channels["youtube"])   && !empty($article['social']['YT']);
                                    $hasTt  = !empty($channels["tiktok"])    && !empty($article['social']['TT']);

                                    $hasLinks = $isSocialActive && ($hasFb || $hasIns || $hasYt || $hasTt);
                                    ?>                    
                                    <?php if ($hasLinks) : ?>
                                        <div class="col-12 line"></div>
                                        <div class="col-12 article-social-section">
                                            
                                            <div class="col-12">Weitere Infos dazu auf</div>
                                            <div class="col-12 chanel-line-up">
                                                <ul class="bb">
                                                    <?php if ($hasFb) : ?>
                                                        <li>
                                                            <a href="<?= htmlspecialchars($article['social']['FB']) ?>" class="social-icon-link" target="_blank">
                                                                <img src="img/social/facebook.svg" width="20" height="20" alt="Facebook">
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>

                                                    <?php if ($hasIns) : ?>
                                                        <li>
                                                            <a href="<?= htmlspecialchars($article['social']['INS']) ?>" class="social-icon-link" target="_blank">
                                                                <img src="img/social/instagram.svg" width="20" height="20" alt="Instagram">
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>

                                                    <?php if ($hasYt) : ?>
                                                        <li>
                                                            <a href="<?= htmlspecialchars($article['social']['YT']) ?>" class="social-icon-link" target="_blank">
                                                                <img src="img/social/youtube.svg" width="20" height="20" alt="YouTube">
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>

                                                    <?php if ($hasTt) : ?>
                                                        <li>
                                                            <a href="<?= htmlspecialchars($article['social']['TT']) ?>" class="social-icon-link" target="_blank">
                                                                <img src="img/social/tiktok.svg" width="20" height="20" alt="TikTok">
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <button type="button" class="btn btn-default ms-auto" data-bs-toggle="modal" data-bs-target="#article-<?= $article["id"] ?>">
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
                                        <h5 class="modal-title" id="staticBackdropLabel"></h5>
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
                                            <h3><?= $article["headline"] ?></h3><br>
                                            <p><?= $article["copytext"] ?></p> 
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
