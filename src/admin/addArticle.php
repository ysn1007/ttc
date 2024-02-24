<?php


session_start();
$content = '';

if(isset($_POST["submit"])) {
    $headline = $_POST["headline"];
    $articleText = $_POST["text"];

    if($_POST["publish"] == "on") {
        $active = 1;
    }

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
           
            
            addArticle($con, $headline, $articleText, $fileName, $imgNewName, $active, $fileTempName, $fileDestination);

            

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
                        <input class="form-control" type="text" name="headline" placeholder="Artikelüberschrift">
                    </div>
                    <div class="col-12 mb-3">
                        <textarea class="form-control" type="text" name="text"  rows="10" cols="100" placeholder="Artikeltext"></textarea>
                    </div>
                        
                    <!--div class="col-12 mb-3">
                        <div>Tags</div>
                        <input type="checkbox" name="tag1" >
                        <label class="check-label-item" for="tag1">Neu</label>
                        <input type="checkbox" name="tag2" >
                        <label class="check-label-item" for="tag2">Neuzugang</label>
                        <input type="checkbox" name="tag3" >
                        <label class="check-label-item" for="tag3">Spielbericht</label>
                        <!--input type="checkbox" name="tag4" >
                        <label class="check-label-item" for="tag4">Video</label-->
                    </div-->
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