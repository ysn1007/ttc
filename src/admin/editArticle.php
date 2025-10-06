<?php

require_once 'dbh.inc.php';
//session_start();
$content = '';



if($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST["updateArticle"])) {
        //var_dump($_POST);exit();
        $articleId = $_POST["article_id"];
        $headline = $_POST["headline"];
        $articleText = $_POST["text"];
        
        if(isset($_POST["tagNews"]) ) {
            $tagNews = 1;
        } else {
            $tagNews = 0;
        }
        if(isset($_POST["tagReviews"]) ) {
            $tagReviews = 1;
        } else {
            $tagReviews = 0;
        }
        if(isset($_POST["tagPlayer"]) ) {
            $tagPlayer = 1;
        } else {
            $tagPlayer = 0;
        }
        if(isset($_POST["tagSocial"]) ) {
            $tagSocial = 1;
        } else {
            $tagSocial = 0;
        }
        if(isset($_POST["publish"]) ) {
            $publish = 1;
        } else {
            $pulish = 0;
        }
        
        //var_dump($articleId );exit;
        //var_dump("News : ".$tagNews, "Reviews : ".$tagReviews, "Player : ".  $tagPlayer, "Aktiv : ". $tagPublish );exit;

        //var_dump("news : " . $_POST["tagNews"], "Reviews : " . $_POST["tagReviews"], "Player : ". $_POST["tagPlayer"], "Social : ".$_POST["tagSocial"], "publish : ". $_POST["publish"]);exit;
        
        /**
         * Wird ausgeführt wenn ein das Artikelbild aktualisiert wird
         */
        if(isset($_FILES)) {
            //var_dump($_FILES);exit;
            // variablen definition für die geladene Datei
            $file = $_FILES["fileName"];
            $fileName = $_FILES["fileName"]["name"];
            $fileTempName = $_FILES["fileName"]["tmp_name"];
            $fileError = $_FILES["fileName"]["error"];
            $fileSize = $_FILES["fileName"]["size"];
            $imgName = "artImg";

            //var_dump("prüfung: variables");exit;
            
            /**
             * Gibt Fehler aus, wenn Bild zu groß
             * 
             * fileSize prüft größer als 2MB
            **/
            if($fileName['error'] === 1){
                echo "Datei zu groß. Bitte die Datei komprimieren.";
                header('Location: editArticle.php?uploadImg=tooBig');
                exit;
            }
            //var_dump("prüfung: fileName");exit;
            /**
             * fileSize prüft größer als 2MB 
             * 
            **/
            if($fileSize > 2000000) {
                var_dump("Prüfung dateigröße");
                echo "Das Bild darf nicht größer als 2MB sein.";
                header('Location: editArticle.php?uploadImg=toBig');
                exit;
            }
            //var_dump("prüfung: fileSize");exit;
                
            /**
             * Prüft ob Bild im Zwischenspeicher vorhanden
             * 
            **/
            if(!$fileTempName) {
                echo "Kein Bild im Zwischenspeicher.";
                header("Location:  editArticle.php?uploadImg=Failed");
                exit;
            }

            //var_dump("prüfung: fileTempName");exit;

            /**
             * Prüft ob Bildendung korrekt ist
             * 
            **/
            $fileExt = explode(".", $fileName);
            $fileActExt = strtolower(end($fileExt));
            $allowed = array("jpg", "jpeg", "png");
            
            // prüft den Dateityp korrekt ist.
            if(!in_array($fileActExt, $allowed)) {
                var_dump("Prüfung dateityp");
                //echo "Dateityp des Bildes Prüfen. Es sind nur .jpg, .jpeg und .png erlaubt.";
                header('Location: editArticle.php?uploadImgType=notRight');exit;
            }
            //var_dump("prüfung: fileType");exit;

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
            }
            //var_dump("prüfung: Bild gelöscht");exit;

            /**
             * Speichern und aktualisiern des neuen Bildes 
             * 
            **/ 
            //var_dump($file, $filename, $fileTempName, $fileError); exit;
            updateArticleImage($con, $articleId, $file, $fileName, $fileActExt, $fileTempName, $imgName);
                
                
        }


        updateArticle($con, $articleId, $headline, $articleText, $tagNews, $tagReviews, $tagPlayer, $tagSocial, $publish );
    }

    
    if(isset($_POST["deleteArticle"])) {

        $articleId = $_POST["article_id"];

        deleteArticle($con, $articleId);

    }
}


include('./components/header.php');

if(isset($_SESSION["admin"]) || isset($_SESSION["manager"]) || isset($_SESSION["author"]) ){
    $content .= '
    
    <div class="col edit-article-section">
        <div class="card">
            <div class="card-header">
                <h4>Artikel bearbeiten <a href="javascript:history.go(-1)" class="btn btn-danger float-end">Zurück</a></h4>
            </div>';
            if(isset($_GET["id"])) {

               $result = getArticleId($con, $_GET["id"]);
               while($article = mysqli_fetch_assoc($result)){
                //var_dump($article);exit;
                
                $content .= '
                <div class="card-body">
                    <form action="'.basename($_SERVER['PHP_SELF']).'" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="article_id" value="'.$_GET["id"].'" >
                         
                        <div class="col-6 mb-3">';
                            if($article["imgPath"] != "") {
                                $content .= '
                                <div class="article-bg-img" style="width: 270px; height: 200px; Background-image: url(../img/article/'. $article["imgPath"] .'); background-size: cover; background-position: top; margin-bottom: 30px;"></div>';
                            } else {
                                $content .='
                                <div class="article-bg-img" style="width: 270px; height: 200px; Background-image: url(../img/tt-icon.svg); background-size: cover; background-position: top; margin-bottom: 30px;"></div><br>
                                <p>Kein Bild vorhanden</p>';
                            }
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
                            <h5>Tags</h5>
                            <input type="checkbox" name="tagNews" '. (($article["tagNews"] == 1) ? "checked" :  "") .'>
                            <label class="check-label-item" for="tag1">Neuigkeiten</label>
                            <input type="checkbox" name="tagPlayer" '. (($article["tagPlayer"] == 1) ? "checked" :  "") .'>
                            <label class="check-label-item" for="tag2">Neuzugang</label>
                            <input type="checkbox" name="tagReviews" '. (($article["tagReviews"] == 1) ? "checked" :  "") .'>
                            <label class="check-label-item" for="tag3">Spielbericht</label>
                            <input type="checkbox" name="tagSocial" '. (($article["tagSocial"] == 1) ? "checked" :  "") .'>
                            <label class="check-label-item" for="tag4">Social Media</label>
                        </div>
                        <div class="col-12 mb-3">
                            <input type="checkbox" name="publish" '. (($article["active"] == 1) ? "checked" :  "") .'>
                            <label for="publish">Artikel Veröffenltichen</label>
                        </div>
                        <div class="col-12 edit-actions">
                            <div class="row">
                                <div class="col">
                                    <button class="btn btn-primary" type="updateArticle" name="updateArticle">Artikel Aktualisieren</button>    
                                </div>
                                <div class="col">
                                    <button class="btn btn-danger" type="deleteArticle" name="deleteArticle">Artikel löschen</button>
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