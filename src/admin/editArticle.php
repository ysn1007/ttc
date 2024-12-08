<?php

require_once 'dbh.inc.php';
session_start();
$content = '';



if($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST["updateArticle"])) {
        //var_dump($_POST["updateArticle"]);exit();
        $articleId = $_POST["article_id"];
        $headline = $_POST["headline"];
        $articleText = $_POST["text"];
        
        if($_POST["publish"] == "on") {
            $active = 1;
        } else {
            $active = 0;
        }
        
        updateArticle($con, $articleId, $headline, $articleText, $active );
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
                            <!--input class="img-upload" type="file" name="fileName"-->
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