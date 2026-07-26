<?php 
require_once 'admin/includes/dbh.inc.php';
include('./includes/header.php');

$dekade = $_GET['dekade'];
$res = getDekadeImages($con, $dekade);
?>

<div class="site-wrap">
    <div class="content-wrap new-player-wrap">
        <section class="container gal-img-group" id="gal-group"> 
            <div class="row"> 
                <div class="galery-header">
                    <img src="img/tt-icon.svg" alt="">
                    <h1>Bildergallarie <?= $dekade ?></h1>
                </div>

                <div class="row">
                
                <?php if(!$row['num_rows'] = 0) : ?>
                    <?php while($row = mysqli_fetch_assoc($res)) : ?>
                        <?php if($dekade === $row['dekade']) : ?>
                            <div class="col-xs-12 col-md-6 col-lg-4 gal-img-item" id="gal-img-item-<?= $row['id'] ?>">
                                <div class="col-xs-12 img-item-body" id="img-item-body">
                                    
                                    <div class="img-item" style="background-image: url(<?= substr($row['imagePath'], 3 ) ?>); background-size: cover; background-position: center;">
                                        <img src="img/glas.png" title="<?= $row['title'] ?>" class="img" loading="lazy">
                                    </div>
                                    
                                    <a class="btn btn-default mt-3" data-bs-toggle="modal" data-bs-target="#img-<?= $row['id'] ?>">
                                        Bild in orginalgröße sehen
                                    </a>
                                </div>
                            </div>
                            
                            <div class="modal fade" id="img-<?= $row['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel"><?=  (($row['title'] != "") ? $row['title'] : "Keine weiteren Angaben.") ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="img-wrapper">
                                                <img src="<?= substr($row['imagePath'], 3 ) ?>" width="100%" height="auto">
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <?= ((!empty($row['descript'])) ? $row['descript'] : "Keine weiteren Angaben") ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endwhile; ?>    
                <?php else : ?>
                    <div class="col-xs-12 gal-img-item" id="gal-img-item">
                        <div class="col-xs-12 img-item-body" id="img-item-body">
                            <p>Bitte prüfe deine Eingabe, es wurden keine Daten erhalten.</p>
                        </div>
                    </div>
                <?php endif; ?>  
                </div>
            </div>
        </section>
    </div> 
</div>
<?php
include('./includes/footer.php');
?>