<?php
if(isset($_POST["submit"])) {

    $imgName = $_POST["fileName"];
    $headline = $_POST["headline"];
    $articleText = $_POST["text"];

    print_r($_POST["submit"]); exit();

    if($_POST["publish"] == checked) {
        $active = 1;
    }

    if(empty($_POST["fileName"])) {
        $imgName = "artImg";
    } else {
        # input imageName is not displayed, this else would be skipped.
        $imgName = strtolower(str_replace(" ", "-", $imgName)); 
    }

    $file = $_FILES["file"];
    $fileName = $_FILES["name"];
    $fileTempName = $_FILES["temp_name"];
    $fileError = $_FILES["error"];
    $fileSize = $_FILES["size"];

    $fileExt = explode(".", $fileName);
    $fileActExt = strtolower(end($fileExt));

    $allowed = array("jpg", "jpeg", "png"); 

    if(in_array($fileActExt, $allowed)) {
        echo "check in: file allowed"; exit();
        if($fileSize > 2000000) {
            echo "Das Bild darf nicht größer als 2MB sein.";
        }

        if($fileError === 0) {
            echo "check in: check file Error ";
           $imgNewName =  $imgName . "." . uniqid("", true) . "." . $fileActExt;
           $fileDestination = "../img/article/" . $imgNewName;

           include_once 'dbh.inc.php';

           if(empty($headline) || empty($articleText)) {
                header("location: addArticle.php?upload=empty");
                exit();
           }

           $stmt = mysqli_stmt_init($con);
           $sql = "INSERT INTO article(headline, text, imgName, imgPath, active) VALUES(?,?,?,?,?); ";

           if(!mysqli_stmt_prepare($stmt, $sql)) {
                echo "SQL stmt failed: addArticle51";
           } else {
            mysqli_stmt_bind_param($stmt, "sssss", $headline, $articleText, $fileName, $imgNewName, $active);
            mysqli_stmt_execute($stmt);

            move_uploaded_file($fileTempName, $fileDestination);

            header("location: index.ad.php?upload=success");
           }

        } else {
            echo "Etwas ist schief gelaufen.";
            exit();
        }
    } else {
        echo "test";
        echo "Nur Bilder als .jpg, .jpeg oder .png werden akzeptiert.";
        exit();
    }

}