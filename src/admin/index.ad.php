<?php
//require_once('config.php');
require_once 'dbh.inc.php';
session_start();
$content = '';


if(!isset($_SESSION["useruid"])) {
    echo "Verbindungsfehler: 500";
} else {
    include('./components/header.php');
        if(isset($_SESSION["admin"])) {
            $content .= '
            <div class="accordion gal" id="gal-data-group">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Galerie
                    </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                    <form action="editPlayer.php" method="post" enctype="multipart/form-data">
                    <table class="tbl table table-light table-striped"> 
                            <div class="add-img add-item">
                                <a class="btn btn-primary" href="addImg.php">Bild hinzufügen</a>
                            </div>
                            <thead>
                                <tr>
                                    <th class="col-1">Bildnr.</th>
                                    <th class="col-1">Titel</th>
                                    <th class="col-3">Beschreibung</th>
                                    <th class="col-1">Jahr</th>
                                    <th class="col-1">Jahrzehnt</th>
                                    <th class="col-1">Bild</th>
                                    <th class="col-1">Aktiv</th>';
                                    if(isset($_SESSION["admin"])) {
                                        $content .= '<th class="col-1">Aktion</th>';
                                    }
                                    $content .= '
                                </tr>
                            </thead>

                        <tbody>';
                        $res = getGalleryImgData($con);
                        if($res->num_rows > 0 ) {
                            while($row = $res->fetch_assoc()){
                                //var_dump($row);exit();
                                $content .='
                                <tr>
                                    <td><input id="txt" type="text" name="id" value="'. $row['id'] .'"></td>
                                    <td><input id="title" type="text" name="title" value="'.$row['title'].'"></td>
                                    <td><input id="descript" type="text" name="descript" value="'.$row['descript'].'"></td>
                                    <td><input id="year" type="text" name="year" value="'.$row['imageYear'].'"></td>
                                    <td><input id="dekade" type="text" name="dekade" value="'.$row['dekade'].'"></td>
                                    <td><img src="'.$row['imagePath'].' " width="50px"></td>
                                    <td><input id="active" type="text" name="active" value="'.$row['active'].'"></td>';
                                    if(isset($_SESSION["admin"])) {
                                        $content .= '
                                        <td><a href="editImage.php?id='. $row["id"] .'" class="btn btn-success">Bearbeiten</a></td>';
                                    }
                                    $content .= '   
                                </tr>';
                            } 
                        }else {
                            echo "keine Daten erhalten.";
                        }
                        $content .= '
                        </tbody>
                    </table>
                </form>
                    </div>
                    </div>
                </div>
            </div>';
            if(isset($_SESSION["admin"]) || isset($_SESSION["manager"]) ) {
            $content .= '
            <div class="accordion" id="accordionExample-2">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Spieler
                    </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            
                            <form action="editPlayer.php" method="post" enctype="multipart/form-data">
                                <div class="add-player add-item">
                                    <a class="btn btn-primary" href="addPlayer.php">Spieler hinzufügen</a>
                                </div>
                                <table class="tbl table table-light table-striped"> 
                                    <thead>
                                        <tr>
                                            <th scope="col">Nr.</th>
                                            <th scope="col">Nachname,Name</th>
                                            <th scope="col">LivePZ</th>
                                            <th scope="col">Team</th>
                                            <th scope="col">Position</th>
                                            <th scope="col">aktiv</th>
                                            <th scope="col">Spv</th>
                                            <th scope="col">Sbem</th>';
                                            if(isset($_SESSION["admin"])) {
                                                $content .= '<th>Aktion</th>';
                                            }
                                            $content .= '
                                        </tr>
                                    </thead>

                                    <tbody>';
                                        $playerData = getPlayers($con);
                                        while($players = mysqli_fetch_assoc($playerData)) {
                                            $content .= '
                                                <tr>
                                                    <th scope="row">'. $players["id"].'</th>
                                                    <td><input type="text" name="name" value="'. $players["Vorname"] .", ". $players["Nachname"] .'"></td>
                                                    <td><input type="text" name="livepz" value="'. $players["livePZ"] .'"></td>
                                                    <td><input type="text" name="team" value="'. $players["team"] .'"></td>
                                                    <td><input type="text" name="position" value="'. $players["position"] .'"></td>
                                                    <td><input type="text" name="position" value="'. $players["aktiv"] .'"></td>
                                                    <td><input type="text" name="spv" value="'. $players["spv"] .'"></td>
                                                    <td><input type="text" name="sbem" value="'. $players["sbem"] .'"></td>';
                                                    if(isset($_SESSION["admin"])) {
                                                        $content .= '<td><a href="editPlayer.php?id='. $players["id"] .'" class="btn btn-success">Bearbeiten</a>';
                                                    }
                                                    $content .='
                                                </tr>';
                                        }
                                    $content .= '
                                    </tbody>
                                </table>
                            </form>
                        
                        </div>
                    </div>
                </div>
            </div>';
            }

            if(isset($_SESSION["admin"]) || isset($_SESSION["manager"]) || isset($_SESSION["author"]) ) {
            $content .= '
            <div class="accordion" id="accordionExample-3">    
                <div class="accordion-item">
                    <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Artikel 
                    </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            
                            <form action="editArticle.php" method="post" enctype="multipart/form-data">
                                <div class="add-article add-item">
                                    <a class="btn btn-primary" href="addArticle.php">Artikel hinzufügen</a>
                                </div>
                                <table class="tbl table table-light table-striped"> 
                                    <thead>
                                        <tr>
                                            <th scope="col">Nr.</th>
                                            <th scope="col">Überschrift</th>
                                            <th scope="col">Text</th>
                                            <th scope="col">Tags</th>
                                            <!--th scope="col">Bildname</th-->
                                            <!--th scope="col">Bildpfad</th-->
                                            <th scope="col">Bild</th>
                                            <th scope="col">Status</th>';
                                            if(isset($_SESSION["admin"])) {
                                                $content .= '<th>Aktion</th>';
                                            }
                                            $content .= '
                                        </tr>
                                    </thead>
                                    
                                    <tbody>';
                                    
                                    $result = getArticle($con);
                                    while($article = mysqli_fetch_assoc($result)) {

                                        if ($article["active"] == 1) {
                                            $status = "online";
                                        } else {
                                            $status = "offline";
                                        }

                                        $content .= '
                                        <tr>
                                            <th scope="row">'. $article["id"] .'</th>
                                            <td><input type="text" name="" value="'. $article["headline"] .'" ></td>
                                            <td><input type="text" name="" title="'. $article["copytext"] .'" value="'. $article["copytext"] .'"></td>
                                            <td><input type="text" name="" title="'. $article["tags"] .'" value="'. $article["tags"] .'"></td>
                                            <!--td><input type="text" name="" value="'. $article["imgName"] .'"></td-->
                                            <!--td><input type="text" name="" value="'. $article["imgPath"] .'"></td-->
                                            <td>
                                                <img src="../img/article/'.$article["imgPath"].'" width="50" height="50"/>
                                            </td>
                                            <td><input type="text" name="" value="'. $status .'"></td>';
                                            if(isset($_SESSION["admin"])) {
                                                $content .= '<td><a class="btn btn-success" href="editArticle.php?id='. $article["id"] .'">Bearbeiten</a> ';
                                            }
                                            $content .= '
                                        </tr>';
                                    }
                                    $content .= '
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>'; 
            }
            $content .= '
            <div class="accordion" id="accordionExample-4">    
                <div class="accordion-item">
                    <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseThree">
                        Spielberichte
                    </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <strong>This is the third item\'s accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It\'s also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                    </div>
                    </div>
                </div>
            </div>';
        }   
    echo $content;
    include('./components/footer.php');
}

?>


