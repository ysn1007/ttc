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
        
        if($_POST["tagNews"] == "on") {
            $tagNews = 1;
        } else {
            $tagNews = 0;
        }
        if($_POST["tagReviews"] == "on") {
            $tagReviews = 1;
        } else {
            $tagReviews = 0;
        }
        if($_POST["tagPlayer"] == "on") {
            $tagPlayer = 1;
        } else {
            $tagPlayer = 0;
        }
        if($_POST["tagSocial"] == "on") {
            $tagSocial = 1;
        } else {
            $tagSocial = 0;
        }
        if($_POST["publish"] == "on") {
            $publish = 1;
        } else {
            $tagPulish = 0;
        }

        

        //var_dump("news : " . $_POST["tagNews"], "Reviews : " . $_POST["tagReviews"], "Player : ". $_POST["tagPlayer"], "Social : ".$_POST["tagSocial"], "publish : ". $_POST["publish"]);exit;
        
        updateArticle($con, $articleId, $headline, $articleText, $tagNews, $tagReviews, $tagPlayer, $tagSocial, $publish );
    }

    // if(isset($_FILES)) {
    //     print_r($_FILES);exit;
    //     // if(empty($_POST["fileName"])) {
    //     //     var_dump("file upload");exit;
    //     //     $imgName = "artImg";
    //     // } else {
    //     //     # input imageName is not displayed, this else would be skipped.
    //     //     $imgName = strtolower(str_replace(" ", "-", $imgName)); 
    //     // }

    //     // $file = $_FILES["fileName"];
    //     // $fileName = $file["name"];
    //     // $fileTempName = $file["tmp_name"];
    //     // $fileError = $file["error"];
    //     // $fileSize = $file["size"];
    
    //     // $fileExt = explode(".", $fileName);
    //     // $fileActExt = strtolower(end($fileExt));

    //     // $allowed = array("jpg", "jpeg", "png"); 


    //     // if($fileError !== 4 ){
            
    //     //     if(in_array($fileActExt, $allowed)) {

    //     //         if($fileError === 0 ) {
    //     //             require_once 'dbh.inc.php';
    //     //             if($fileSize > 2000000) {
    //     //                 echo "Das Bild darf nicht größer als 2MB sein.";
    //     //             }
                
    //     //             $imgNewName =  $imgName . "." . uniqid("", true) . "." . $fileActExt;
    //     //             $fileDestination = "../img/article/" . $imgNewName;
                
        
    //     //             if(empty($headline) || empty($articleText)) {
    //     //                 header("location: addArticle.php?upload=empty");
    //     //                 exit();
    //     //             }

    //     //             addArticle($con, $headline, $articleText, $fileName, $imgNewName, $active, $tagNews, $tagPlayer, $tagReview, $tagSocial, $fileTempName, $fileDestination);
        
    //     //         } else {
    //     //             echo "Etwas ist schief gelaufen.";
    //     //             exit();
    //     //         }

    //     //     } else {
    //     //         echo "Es wurde kein Bild gefunden. Bitte versuche es erneut";
    //     //         exit();
    //     //     }

    //     // } 
        
    //     // else {
    //     //     if($fileError === 4 ) {
    //     //         require_once 'dbh.inc.php';
    //     //         $imgNewName = "";
    //     //         $fileDestination = "";
    //     //         $fileTempName = "";
    //     //         addArticle($con, $headline, $articleText, $fileName, $imgNewName, $active, $tagNews, $tagPlayer, $tagReview, $tagSocial, $fileTempName, $fileDestination);
    //     //     }
    //     // }    
    // }

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
                if($article["active"] == 1) {
                    $status = "checked";
                } 
                if($article["tagNews"] == 1) {
                    $status = "checked";
                } 
                if($article["tagReviews"] == 1) {
                    $status = "checked";
                } 
                if($article["tagPlayer"] == 1) {
                    $status = "checked";
                } 
                if($article["tagSocial"] == 1) {
                    $status = "checked";
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
                            <input type="checkbox" name="publish" '. $status .'>
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