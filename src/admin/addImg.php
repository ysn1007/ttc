<?php
session_start();
$content = '';

// uniqid gives 4 chars, but you could adjust it to your needs.
function uniqidReal($lenght = 4) { 
    if (function_exists("random_bytes")) {
        $bytes = random_bytes(ceil($lenght / 2));
    } elseif (function_exists("openssl_random_pseudo_bytes")) {
        $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
    } else {
        throw new Exception("no cryptographically secure random function available");
    }
    return substr(bin2hex($bytes), 0, $lenght);
}

if(isset($_POST["submit"])) {
    $headline = $_POST["headline"];
    $year = $_POST["year"];
    $imgText = $_POST["text"];
    $dekade = $_POST["dekade"];
    
    if(isset($_POST['dekade'])) {
        switch($_POST['dekade']){
            case 1: $dekade = "1950-1959"; break;
            case 2: $dekade = "1960-1969"; break;
            case 3: $dekade = "1970-1979"; break;
            case 4: $dekade = "1980-1989"; break;
            case 5: $dekade = "1990-1999"; break;
            case 6: $dekade = "2000-2009"; break;
            case 7: $dekade = "2010-2019"; break;
            case 8: $dekade = "2020-2029"; break;
            default: $dekade = "";
        }
    }

    if(empty($headline) || empty($imgText) ) {
        header("location: addImg.php?upload=empty");
        exit();
    }

    if(empty($dekade)) {
        echo "Bitte das jahrzeht wählen.";
        header("location: addImg.php?dekade=empty");
        exit();
    }

    if(empty($year)){
        $year = "0";
    }
    
    if($_POST["publish"] == "on") {
        $active = 1;
    }

    $file = $_FILES["imgName"]["name"];
    $fileName = $_FILES["imgName"]["name"];
    $fileTempName = $_FILES["imgName"]["tmp_name"];
    $fileError = $_FILES["imgName"]["error"];
    $fileSize = $_FILES["imgName"]["size"];
    $fileType = $_FILES["imgName"]["type"];
    
    $allowed = array("jpg", "jpeg", "png"); 
    
    foreach($fileName as $key => $file) {

        $uniq = uniqidReal();
        $fileExt = explode(".", $fileName[$key]);
        $fileActExt = strtolower(end($fileExt));
        
        if(in_array($fileActExt, $allowed)) {
           
            if($fileSize[$key] > 2000000) {
                echo "Das Bild darf nicht größer als 2MB sein.";
                header("location: addImg.php?upload=imagesToBig");
                exit();
            }

            if($fileError[$key] === 0) {
                require_once 'dbh.inc.php';

                $imgNewName =  "img-". $year . "-" . $dekade . "-" . $uniq . "." . $fileActExt;
                
                if($dekade == "1950-1959"){
                    $fileDestination = "../img/gallery/1950-1959/" . $imgNewName;
                }
                if($dekade == "1960-1969"){
                    $fileDestination = "../img/gallery/1960-1969/" . $imgNewName;
                }
                if($dekade == "1970-1979"){
                    $fileDestination = "../img/gallery/1970-1979/" . $imgNewName;
                }
                if($dekade == "1980-1989"){
                    $fileDestination = "../img/gallery/1980-1989/" . $imgNewName;
                }
                if($dekade == "1990-1999"){
                    $fileDestination = "../img/gallery/1990-1999/" . $imgNewName;
                }
                if($dekade == "2000-2009"){
                    $fileDestination = "../img/gallery/2000-2009/" . $imgNewName;
                }
                if($dekade == "2010-2019"){
                    $fileDestination = "../img/gallery/2010-2019/" . $imgNewName;
                }
                if($dekade == "2020-2029"){
                    $fileDestination = "../img/gallery/2020-2029/" . $imgNewName;
                }

                move_uploaded_file($fileTempName[$key], $fileDestination);
               
                addImage($con, $headline, $imgText, $year, $dekade, $fileName[$key], $imgNewName, $active, $fileTempName[$key], $fileDestination);
               
            } else {
                echo "Etwas ist schief gelaufen.";
                exit();
            }
        } else {
            echo "Nur Bilder als .jpg, .jpeg oder .png werden akzeptiert.";
            exit();
        }
    }

    

}

include('./components/header.php');

if(isset($_SESSION["admin"]) || isset($_SESSION["manager"]) || isset($_SESSION["author"]) ){
    $content .= '
    <div class="col add-img-section">
        <div class="card">
            <div class="card-header">
                <h4>Bild hinzufügen <a href="javascript:history.go(-1)" class="btn btn-danger float-end">Zurück</a></h4>
            </div>
            <div class="card-body">
                <form action="'.basename($_SERVER['PHP_SELF']).'" method="post" enctype="multipart/form-data">
                    <div class="col-6 mb-3">
                        <input class="img-upload" type="file" name="imgName[]" multiple>
                        <!--input class="img-name" type="text" name="imgName" placeholder="Bildname"-->
                    </div>
                    <div class="row row-cols-2">
                        <div class="col-3">
                            <div class="col-12 mb-3">
                                <input class="form-control" type="text" name="year" placeholder="Das Jahr">
                            </div> 
                            <div class="col-12 mb-3">
                                <input class="form-control" type="text" name="headline" placeholder="* Bildüberschrift">
                            </div>
                           
                        </div>
                        <div class="col-3">
                            <div class="col-12 mb-3">
                                <select class="form-select form-select-md mb-3" name="dekade" aria-label=".form-select-md example">
                                    <option selected>* Das Jahrzehnt auswählen</option>
                                    <option value="1">1950-1959</option>
                                    <option value="2">1960-1969</option>
                                    <option value="3">1970-1979</option>
                                    <option value="4">1980-1989</option>
                                    <option value="5">1990-1999</option>
                                    <option value="6">2000-2009</option>
                                    <option value="7">2010-2019</option>
                                    <option value="8">2020-2029</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <textarea class="form-control" type="text" name="text"  rows="1" cols="100" placeholder="*Bildtext"></textarea>
                    </div>
                    
                    <div class="col-12 mb-3">
                        <input type="checkbox" name="publish" checked>
                        <label for="publish">Bild Veröffenltichen</label>
                    </div>
                    <button class="btn btn-primary" type="submit" name="submit">Bild hochladen</button>
                </form> 
            </div>
        </div>
    </div>';
}


echo $content;
include('./components/footer.php');