<?php

require_once 'dbh.inc.php';
//session_start();
$content = '';



if($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["updateArticle"])) {
        $articleId = $_POST["article_id"];
        $headline = $_POST["headline"];
        $articleText = $_POST["text"];
        
        $tagNews = isset($_POST["tagNews"]) ? 1 : 0;
        $tagReviews = isset($_POST["tagReviews"]) ? 1 : 0;
        $tagPlayer = isset($_POST["tagPlayer"]) ? 1 : 0;
        $tagSocial = isset($_POST["tagSocial"]) ? 1 : 0;
        $publish = isset($_POST["publish"]) ? 1 : 0;

        $imgName = "artImg";
        $hasNewImage = isset($_FILES["fileName"]) && $_FILES["fileName"]["error"] === 0;

        // Initialisiere Standardwerte
        $file = null;
        $fileName = $_POST["imgName"] ?? '';
        $fileActExt = '';
        $fileTempName = '';
        $imgPath = $_POST["imgPath"] ?? '';

        if ($hasNewImage) {
            $file = $_FILES["fileName"];
            $fileName = $_FILES["fileName"]["name"];
            $fileTempName = $_FILES["fileName"]["tmp_name"];
            $fileError = $_FILES["fileName"]["error"];
            $fileSize = $_FILES["fileName"]["size"];

            // Dateigröße prüfen
            if ($fileError === 1 || $fileSize > 2000000) {
                header('Location: editArticle.php?uploadImg=tooBig');
                exit;
            }

            // Gültige Endung prüfen
            $fileExt = explode(".", $fileName);
            $fileActExt = strtolower(end($fileExt));
            $allowed = ["jpg", "jpeg", "png"];

            if (!in_array($fileActExt, $allowed)) {
                header("Location: article.php?error=invalidFileType");
                exit;
            }

            if (!getimagesize($fileTempName)) {
                header("Location: article.php?error=notAnImage");
                exit;
            }

            // Altes Bild löschen
            $oldImage = $_POST["imgPath"] ?? '';
            if (!empty($oldImage) && file_exists("../img/article/" . $oldImage)) {
                unlink("../img/article/" . $oldImage);
            }

            // Neues Bild speichern
            $imgNewName = $imgName . "." . uniqid("", true) . "." . $fileActExt;
            $uploadPath = "../img/article/" . $imgNewName;

            if (!move_uploaded_file($fileTempName, $uploadPath)) {
                header("Location: article.php?error=uploadFailed");
                exit;
            }

            $imgPath = $imgNewName;
        }

        // Aufruf der Update-Funktion
        updateArticle(
            $con,
            $articleId,
            $headline,
            $articleText,
            $tagNews,
            $tagReviews,
            $tagPlayer,
            $tagSocial,
            $file,
            $fileName,
            $fileActExt,
            $fileTempName,
            $imgName,
            $publish,
            $imgPath // optional, je nachdem wie deine Funktion es erwartet
        );
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
                
                $content .= '
                <div class="card-body">
                    <form action="'.basename($_SERVER['PHP_SELF']).'" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="article_id" value="'.$_GET["id"].'" >
                        <input type="hidden" name="imgPath" value="'. $article["imgPath"] .'"> 
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