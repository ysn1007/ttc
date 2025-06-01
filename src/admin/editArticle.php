<?php

require_once 'dbh.inc.php';
session_start();
$content = '';



if($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST["updateArticle"])) {
        var_dump($_FILES);exit();
        //var_dump($_POST);exit();
        //var_dump($_POST["updateArticle"]);exit();

        $file = $_FILES["fileName"];
        $fileName = $file["name"];
        $fileTempName = $file["tmp_name"];
        $fileError = $file["error"];
        $fileSize = $file["size"];
        /* if(empty($_POST["fileName"])) {
            $imgName = "artImg";
        } else {
            # input imageName is not displayed, this else would be skipped.
            $imgName = strtolower(str_replace(" ", "-", $imgName)); 
        } */
        $fileExt = explode(".", $fileName);
        $fileActExt = strtolower(end($fileExt));

        $allowed = array("jpg", "jpeg", "png");
        
        if($fileName['error'] === 1){
            echo "Datei zu groß. Bitte die Datei komprimieren.";
        }

        if($fileSize > 2000000) {
            echo "Das Bild darf nicht größer als 2MB sein.";
        }

        // Text Aktualisierung
        $articleId = $_POST["article_id"];
        $headline = $_POST["headline"];
        $articleText = $_POST["text"];
        $imgPath = $_POST['imgPath'];
    
        if($_POST["publish"] == "on") {
            $active = 1;
        } else {
            $active = 0;
        }
    
        updateArticle($con, $articleId, $headline, $articleText, $imgPath, $active );

        
        
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