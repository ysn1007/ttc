<?php


session_start();
$content = '';

if(isset($_POST["submit"])) {
    //var_dump($_POST); exit;
    $headline = $_POST["headline"];
    $articleText = $_POST["text"];

    if($_POST["publish"] == "on") {
        $active = 1;
    }

    if(isset($_POST["tagNews"])) {
        $tagNews = 1;
    }else {
        $tagNews = 0;
    }
    if(isset($_POST["tagPlayer"])) {
        $tagPlayer = 1;
    }else {
        $tagPlayer = 0;
    }
    if(isset($_POST["tagReview"])) {
        $tagReview = 1;
    }else {
        $tagReview = 0;
    }
    if(isset($_POST["tagSocial"])) {
        $tagSocial = 1;
    }else {
        $tagSocial = 0;
    }

    //var_dump($tagNews, $tagPlayer, $tagReview, $tagSocial); exit;

    if(empty($_POST["fileName"])) {
        $imgName = "artImg";
    } else {
        # input imageName is not displayed, this else would be skipped.
        $imgName = strtolower(str_replace(" ", "-", $imgName)); 
    }

    $file = $_FILES["fileName"];
    $fileName = $file["name"];
    $fileTempName = $file["tmp_name"];
    $fileError = $file["error"];
    $fileSize = $file["size"];

    $fileExt = explode(".", $fileName);
    $fileActExt = strtolower(end($fileExt));
    
    $allowed = array("jpg", "jpeg", "png"); 

    if(in_array($fileActExt, $allowed)) {
        if($fileSize > 2000000) {
            echo "Das Bild darf nicht größer als 2MB sein.";
        }

        if($fileError === 0) {
            $imgNewName =  $imgName . "." . uniqid("", true) . "." . $fileActExt;
            $fileDestination = "../img/article/" . $imgNewName;
            
            require_once 'dbh.inc.php';

            if(empty($headline) || empty($articleText)) {
                header("location: addArticle.php?upload=empty");
                exit();
            }
           
            
            addArticle($con, $headline, $articleText, $fileName, $imgNewName, $active, $tagNews, $tagPlayer, $tagReview, $tagSocial, $fileTempName, $fileDestination);

            

        } else {
            echo "Etwas ist schief gelaufen.";
            exit();
        }
    } else {
        echo "Nur Bilder als .jpg, .jpeg oder .png werden akzeptiert.";
        exit();
    }

}

include('./components/header.php');

if(isset($_SESSION["admin"]) || isset($_SESSION["manager"]) || isset($_SESSION["author"]) ){
    $content .= '
    <div class="col add-article-section">
        <div class="card">
            <div class="card-header">
                <h4>Artikel hinzufügen <a href="index.ad.php" class="btn btn-danger float-end">Zurück</a></h4>
            </div>
            <div class="card-body">
                <form action="'.basename($_SERVER['PHP_SELF']).'" method="post" enctype="multipart/form-data">
                    <div class="col-6 mb-3">
                        <input class="img-upload" type="file" name="fileName">
                    </div>
                    <div class="col-12 mb-3">
                        <input class="form-control" type="text" name="headline" placeholder="Überschrift">
                    </div>
                    <div class="col-12 mb-3">
                        <textarea class="form-control" type="text" name="text"  rows="10" cols="100" placeholder="Artikel"></textarea>
                    </div>
                        
                    <div class="col-12 mb-3">
                        <h5>Tags</h5>
                        <input type="checkbox" name="tagNews" >
                        <label class="check-label-item" for="tag1">Meldung</label>
                        <input type="checkbox" name="tagPlayer" >
                        <label class="check-label-item" for="tag2">Neuzugang</label>
                        <input type="checkbox" name="tagReviews" >
                        <label class="check-label-item" for="tag3">Spielbericht</label>
                        <input type="checkbox" name="tagSocial" >
                        <label class="check-label-item" for="tag4">Social Media</label>
                    </div>
                    <div class="col-12 mb-3">
                        <input type="checkbox" name="publish" checked>
                        <label for="publish">Artikel Veröffenltichen</label>
                    </div>
                    <button class="btn btn-primary" type="submit" name="submit">Veröffentlichen</button>
                </form> 
            </div>
        </div>
    </div>';
}


echo $content;
include('./components/footer.php');