<?php

require_once 'dbh.inc.php';
include('./components/header.php');

$dekade = $_GET['dekade'];
$res = getDekadeImages($con, $dekade);
$count = mysqli_num_rows($res);

$content .= '';

$content .= '

<div class="accordion gal" id="gal-data-group">
<section class="img-group-header d-flex justify-content-between align-items-center mb-3 p-4">
    <div class="dekades">'. $count .' Bilder vom '.$dekade .'</div>
    <div class="add-img add-item">
        <a class="btn btn-primary" href="addImg.php">Bild hinzufügen</a>
    </div>
</section>
    <div class="accordion-item">
        <h2 class="accordion-header">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
            Alle Bilder dieses Jahrzehnt
        </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collase show" data-bs-parent="#accordionExample">
        <div class="accordion-body">
        
        <form action="editPlayer.php" method="post" enctype="multipart/form-data">
        <div class="table-responsive">
            
            <table class="table table-light"> 
                    <thead>
                        <tr>
                            <th class="col-1">Bildnr.</th>
                            <th class="col-2">Titel</th>
                            <th class="col-3">Beschreibung</th>
                            <th class="col-1">Jahr</th>
                            <!--th class="col-1">Jahrzehnt</th-->
                            <th class="col-1">Bild</th>
                            <!--th class="col-1">Aktiv</th-->';
                            if(isset($_SESSION["admin"])) {
                                $content .= '<th class="col-1">Aktion</th>';
                            }
                            $content .= '
                        </tr>
                    </thead>
                <tbody>';
                
                
                if($res->num_rows > 0 ) {
                    while($row = $res->fetch_assoc()){
                        
                        $content .='
                        <tr>
                            <td><input id="txt" type="text" name="id" value="'. $row['id'] .'"></td>
                            <td><input id="title" type="text" name="title" value="'.$row['title'].'"></td>
                            <td><input id="descript" type="text" name="descript" value="'.$row['descript'].'"></td>
                            <td><input id="year" type="text" name="year" value="'.$row['imageYear'].'"></td>
                            <!--td><input id="dekade" type="text" name="dekade" value="'.$row['dekade'].'"></td-->
                            <td><img src="'.$row['imagePath'].' " width="50px"></td>
                            <!--td><input id="active" type="text" name="active" value="'.$row['active'].'"></td-->';
                            if(isset($_SESSION["admin"])) {
                                $content .= '
                                <td><a href="editImage.php?id='. $row["id"] .'" class="btn btn-success">Bearbeiten</a></td>';
                            }
                            $content .= '   
                        </tr>';
                    } 
                }else {
                    echo "keine Daten erhalten.";
                }
                $content .= '
                </tbody>
            </table>
        </div>
    </form>
        </div>
        </div>
    </div>
</div>';

echo $content;
include('./components/footer.php');