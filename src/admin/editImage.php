<?php

require_once 'dbh.inc.php';
session_start();
$content = '';



if($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if(isset($_POST["updateImage"])) {
       
        $imageId = $_POST["imageId"];
        $title = $_POST["headline"];
        $descript = $_POST["text"];
        $year = $_POST["year"];
        $dekade = $_POST["dekade"];

        switch($dekade) {
            case 1 : 
                $dekade = "1950-1959";
                break;
            case 2 : 
                $dekade = "1960-1969";
                break;
            case 3 : 
                $dekade = "1970-1979";
                break;
            case 4 : 
                $dekade = "1980-1989";
                break;
            case 5 : 
                $dekade = "1990-1999";
                break;
            case 6 : 
                $dekade = "2000-2009";
                break;
            case 7 : 
                $dekade = "2010-2019";
                break;
            case 8 : 
                $dekade = "2020-2029";
                break;
            default: "0";
        }

        if($_POST["publish"] == "on") {
            $active = 1;
        } else {
            $active = 0;
        }
        
        updateImage($con, $imageId, $title, $descript, $year, $dekade, $active );
    }

    if(isset($_POST["deleteImage"])) {
        $imageId = $_POST["imageId"];

        deleteImage($con, $imageId );

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
               $res = getImageId($con, $_GET["id"]);
               while($row = mysqli_fetch_assoc($res)){
                if($row["active"] == 1) {
                    $status = "checked";
                } else {
                    $status = "";
                }
                
                $content .= '
                <div class="card-body">
                    <form action="'.basename($_SERVER['PHP_SELF']).'" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="imageId" value="'.$_GET["id"].'" >
                         
                        <div class="col-6 mb-3">
                            <div class="article-bg-img" style="width: 270px; height: 200px; Background-image: url('. $row["imagePath"] .'); background-size: cover; background-position: top; margin-bottom: 30px;"></div>
                        </div>
                        <div class="row row-cols-2">
                        <div class="col-3">
                            <div class="col-12 mb-3">
                                <input class="form-control" type="text" name="year" value="'. $row['imageYear'] .'">
                            </div> 
                        </div>
                        <div class="col-3">
                            <div class="col-12 mb-3">
                                <select class="form-select form-select-md mb-3" name="dekade" aria-label=".form-select-md example">';
                                    
                                    if($row['dekade'] == "1950-1959"){
                                        $content .= '
                                        <option selected value="1">1950-1959</option>
                                        <option value="2">1960-1969</option>
                                        <option value="3">1970-1979</option>
                                        <option value="4">1980-1989</option>
                                        <option value="5">1990-2099</option>
                                        <option value="6">2000-2009</option>
                                        <option value="7">2010-2019</option>
                                        <option value="8">2020-2029</option>
                                        ';
                                    }
                                    if($row['dekade'] == "1960-1969"){
                                        $content .=' 
                                        <option value="1">1950-1959</option>
                                        <option selected value="2">1960-1969</option>
                                        <option value="3">1970-1979</option>
                                        <option value="4">1980-1989</option>
                                        <option value="5">1990-2099</option>
                                        <option value="6">2000-2009</option>
                                        <option value="7">2010-2019</option>
                                        <option value="8">2020-2029</option>
                                        ';
                                    }
                                    if($row['dekade'] == "1970-1979"){
                                        $content .=' 
                                        <option value="1">1950-1959</option>
                                        <option value="2">1960-1969</option>
                                        <option selected value="3">1970-1979</option>
                                        <option value="4">1980-1989</option>
                                        <option value="5">1990-2099</option>
                                        <option value="6">2000-2009</option>
                                        <option value="7">2010-2019</option>
                                        <option value="8">2020-2029</option>
                                        ';
                                    }
                                    if($row['dekade'] == "1980-1989"){
                                        $content .=' 
                                        <option value="1">1950-1959</option>
                                        <option value="2">1960-1969</option>
                                        <option value="3">1970-1979</option>
                                        <option selected value="4">1980-1989</option>
                                        <option value="5">1990-2099</option>
                                        <option value="6">2000-2009</option>
                                        <option value="7">2010-2019</option>
                                        <option value="8">2020-2029</option>
                                        ';
                                    }
                                    if($row['dekade'] == "1990-1999"){
                                        $content .=' 
                                        <option value="1">1950-1959</option>
                                        <option value="2">1960-1969</option>
                                        <option value="3">1970-1979</option>
                                        <option value="4">1980-1989</option>
                                        <option selected value="5">1990-2099</option>
                                        <option value="6">2000-2009</option>
                                        <option value="7">2010-2019</option>
                                        <option value="8">2020-2029</option>
                                        ';
                                    }
                                    if($row['dekade'] == "2000-2009"){
                                        $content .=' 
                                        <option value="1">1950-1959</option>
                                        <option value="2">1960-1969</option>
                                        <option value="3">1970-1979</option>
                                        <option value="4">1980-1989</option>
                                        <option value="5">1990-2099</option>
                                        <option selected value="6">2000-2009</option>
                                        <option value="7">2010-2019</option>
                                        <option value="8">2020-2029</option>
                                        ';
                                    }
                                    if($row['dekade'] == "2010-2019"){
                                        $content .=' 
                                        <option value="1">1950-1959</option>
                                        <option value="2">1960-1969</option>
                                        <option value="3">1970-1979</option>
                                        <option value="4">1980-1989</option>
                                        <option value="5">1990-2099</option>
                                        <option value="6">2000-2009</option>
                                        <option selected value="7">2010-2019</option>
                                        <option value="8">2020-2029</option>
                                        ';
                                    }
                                    if($row['dekade'] == "2020-2029"){
                                        $content .=' 
                                        <option value="1">1950-1959</option>
                                        <option value="2">1960-1969</option>
                                        <option value="3">1970-1979</option>
                                        <option value="4">1980-1989</option>
                                        <option value="5">1990-2099</option>
                                        <option value="6">2000-2009</option>
                                        <option value="7">2010-2019</option>
                                        <option selected value="8">2020-2029</option>
                                        ';
                                    }
                                    $content .= '
                                </select>
                            </div>
                        </div>
                    </div>
                        <div class="col-12 mb-3">
                            <input class="form-control" type="text" name="headline" placeholder="Titel" value="'.$row["title"].'">
                        </div>
                        <div class="col-12 mb-3">
                            <textarea class="form-control" type="text" name="text"  rows="10" cols="100" placeholder="Bildtext">'.$row["descript"].'</textarea>
                        </div>
                            
                        <div class="col-12 mb-3">
                            <input type="checkbox" name="publish" '. $status .'>
                            <label for="publish">Bild Veröffenltichen</label>
                        </div>
                        <div class="col-12 edit-actions">
                            <div class="row">
                                <div class="col">
                                    <button class="btn btn-primary" type="updateImage" name="updateImage">Bild Aktualisieren</button>    
                                </div>
                                <div class="col">
                                    <button class="btn btn-danger" type="deleteImage" name="deleteImage">Bild löschen </button>
                                </div>
                            </ div>
                        </div>
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