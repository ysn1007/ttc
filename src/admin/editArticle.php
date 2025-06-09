<?php

require_once 'dbh.inc.php';
session_start();
$content = '';



if($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST["updateArticle"])) {

        // Variablen definition für Artikel Aktualisierung
        $articleId = $_POST["article_id"];
        $headline = $_POST["headline"];
        $articleText = $_POST["text"];
        $imgPath = $_POST['imgPath'];

        if($_POST["publish"] == "on") {
            $active = 1;
        } else {
            $active = 0;
        }

        var_dump($_FILES);
        echo "<pre>";
        var_dump($_POST);
        
        // neues Bild speichern
        if($fileError === 0 ) {
            echo "Fehler Beim hochladen eines Bildes";
            header('Location: editArticle.php?uploadImg=fileError');exit;
        }
        
        if($_FILES["fileName"] != "") {
            echo "Bild vorhanden" . "<br>";

            // Integiert Datenbank
            require_once 'dbh.inc.php';

            // variablen definition für die geladene Datei
            $file = $_FILES["fileName"];
            $fileName = $_FILES["fileName"]["name"];
            $fileTempName = $_FILES["fileName"]["tmp_name"];
            $fileError = $_FILES["fileName"]["error"];
            $fileSize = $_FILES["fileName"]["size"];
            $imgName = "artImg";

            mysqli_stmt_prepare($stmt,$query);
            mysqli_stmt_bind_param($stmt, "s", $articleId);

            if($fileName['error'] === 1){
                echo "Datei zu groß. Bitte die Datei komprimieren.";
                header('Location: editArticle.php?uploadImg=tooBig');exit;
            }


            if($_FILES["fileName"]["tmp_name"] != ""){ 
                var_dump("Bild zwischenspeicher: ".$_FILES["fileName"]["tmp_name"]);

                /**
                 * Löschen des vorhandenen Bildes
                 * 
                **/
                 
                $image = $_POST["imgPath"];
                // prüft ob Artikel schon bild gespeichert hat
                if(file_exists("../img/article/".$image)) {

                    // Löschen des aktuellen Bildes
                    $dir = getcwd();
                    chdir("../img/article/");
                    unlink($image);
                    chdir($dir);

                    var_dump("Bild wurde gelöscht.");
                }
                
                /**
                 * Speichern des neuen Bildes 
                 * 
                **/ 

                // extrahiert datei typ zur prüfung ob Datei erlaubt
                $fileExt = explode(".", $fileName);
                $fileActExt = strtolower(end($fileExt));

                // erlaubte dateien
                $allowed = array("jpg", "jpeg", "png");
                
                // prüft den Dateityp korrekt ist.
                if(!in_array($fileActExt, $allowed)) {
                    var_dump("Prüfung dateityp");
                    //echo "Dateityp des Bildes Prüfen. Es sind nur .jpg, .jpeg und .png erlaubt.";
                    header('Location: editArticle.php?uploadImgType=notRight');exit;
                }

                // Prüft Bildgröße, Bild nbicht größer als 2MB
                if($fileSize > 2000000) {
                     var_dump("Prüfung dateigröße");
                    echo "Das Bild darf nicht größer als 2MB sein.";
                    header('Location: editArticle.php?uploadImg=toBig');exit;
                }
            
                // Dateipfad und Dateiname werden vergeben 
                $imgNewName =  $imgName . "." . uniqid("", true) . "." . $fileActExt;
                $fileDestination = "../img/article/" . $imgNewName;
                var_dump("imgNewName: ".$imgNewName . ", and destination :" . $fileDestination );
                move_uploaded_file($fileTempName, $fileDestination);
                $imgPath = $imgNewName;
                // Daten zum Aktualisieren des Artikels übertragen
                updateArticle($con, $articleId, $headline, $articleText, $fileName, $imgPath, $active );
                                
            }
        }
    
        updateArticle($con, $articleId, $headline, $articleText, $fileName, $imgPath, $active );

    }

    if(isset($_POST["deleteArticle"])) {

        $articleId = $_POST["article_id"];

        deleteArticle($con, $articleId, );

    }
}


include('./components/header.php');

if(isset($_SESSION["admin"]) || isset($_SESSION["manager"]) || isset($_SESSION["author"]) ){
    $content .= '
    
    <div class="col edit-article-section">
        <div class="card">
            <div class="card-header">
                <h4>Artikel bearbeiten <a href="index.ad.php" class="btn btn-danger float-end">Zurück</a></h4>
            </div>';
            if(isset($_GET["id"])) {

               $result = getArticleId($con, $_GET["id"]);
               while($article = mysqli_fetch_assoc($result)){

                if($article["active"] == 1) {
                    $status = "checked";
                } else {
                    $status = "";
                }
                $content .= '
                <div class="card-body">
                    <form action="'.basename($_SERVER['PHP_SELF']).'" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="article_id" value="'.$_GET["id"].'" >
                        <input type="hidden" name="imgPath" value="'. $article["imgPath"] .'">
                         
                        <div class="col-6 mb-3">';
                            if($article["imgPath"] != "") {
                                $content .= '
                                <div class="article-bg-img" style="width: 270px; height: 200px; Background-image: url(../img/article/'. $article["imgPath"] .'); background-size: cover; background-position: top; margin-bottom: 30px;"></div>
                                <img src="/img/article/'. $article["imgPath"] .'" alt="" type="file" width=100px>';
                            } /* else {
                                $content .='
                                <div class="article-bg-img" style="width: 270px; height: 200px; Background-image: url(../img/tt-icon.svg); background-size: cover; background-position: top; margin-bottom: 30px;"></div><br>
                                <p>Kein Bild vorhanden</p>';
                            } */
                            $content .= '
                            <input class="img-upload" type="file" name="fileName">
                            <!--input class="img-name" type="text" name="imgName" placeholder="Bildname"-->
                        </div>
                        
                        <div class="col-12 mb-3">
                            <input class="form-control" type="text" name="headline" placeholder="Titel" value="'.$article["headline"].'">
                        </div>
                        <div class="col-12 mb-3">
                            <!--span class="input form-control" type="text" name="text" role="textbox" placeholder="Text" contenteditable>'.$article["copytext"].'</span-->
                            <textarea class="form-control" type="text" name="text"  rows="10" cols="100" placeholder="Artikeltext">'.$article["copytext"].'</textarea>
                        </div>
                            
                        <div class="col-12 mb-3">
                            <input type="checkbox" name="publish" '. $status .'>
                            <label for="publish">Artikel Veröffenltichen</label>
                        </div>
                        <div class="col-12 edit-actions">
                            <div class="row">
                                <div class="col">
                                    <button class="btn btn-primary" type="updateArticle" name="updateArticle">Artikel Aktualisieren</button>    
                                </div>
                                <div class="col">
                                    <button class="btn btn-danger" type="deleteArticle" name="deleteArticle">Artikel löschen </button>
                                </div>
                            </ div>
                        </div>
                        <!--button class="btn btn-primary" type="updateArticle" name="submit">Aktualisieren</button-->
                    </form> 
                </div>';
               }

            }
        $content .= '
        </div>
    </div>';
}


echo $content;
include('./components/footer.php');