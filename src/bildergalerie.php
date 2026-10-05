<?php 
require_once 'admin/includes/dbh.inc.php';
include('./includes/header.php');

$dekade = $_GET['dekade'];
$res = getDekadeImages($con, $dekade);


// Array für die JavaScript-Übergabe vorbereiten
$galleryDataForJs = [];



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
                            <?php
                                // Bilddaten mit der ID als Key im Array speichern
                                $galleryDataForJs[$row['id']] = [
                                    'id'          => $row['id'],
                                    'title'       => $row['title'] ?? 'Bild ' . $row['id'],
                                    'src'         => substr($row['imagePath'], 3), // Gleicher Pfad-Trim wie im <img>
                                    'description' => $row['descript'] ?? ''
                                ];
                            ?>

                            <div class="col-xs-12 col-md-6 col-lg-4 gal-img-item js-gallery-trigger" data-img-id="<?= $row['id'] ?>" id="gal-img-item-<?= $row['id'] ?>">
                                <div class="col-xs-12 img-item-body" id="img-item-body">
                                    
                                    <div class="img-item" style="background-image: url(<?= substr($row['imagePath'], 3 ) ?>); background-size: cover; background-position: center;">
                                        <img src="img/glas.png" title="<?= $row['title'] ?>" class="img" loading="lazy">
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
        <!-- JS-Variable vor der Komponente bereitstellen -->
        <script>
            const galleryImages = <?= json_encode($galleryDataForJs, JSON_HEX_TAG | JSON_HEX_AMP); ?>;
        </script>
       <?php include("includes/components/img-slider.php"); ?>
        
    </div> 
</div>
<?php
include('./includes/footer.php');
?>