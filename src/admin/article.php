<?php
require_once 'dbh.inc.php';
include('./components/header.php');

$allArticles = getArticle($con);

if(isset($_SESSION["admin"]) || isset($_SESSION["manager"]) || isset($_SESSION["author"]) ) {
    $content .= '
    <div class="accordion" id="accordionExample-3">
        <section class="img-group-header d-flex justify-content-between align-items-center mb-3 p-4">
            <div class="dekades">Anzahl Artikel gespeichert</div>
            <div class="add-img add-item">
                <a class="btn btn-primary" href="addArticle.php">Artikel hinzufügen</a>
            </div>
        </section>    
        <div class="accordion-item">
            <h2 class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                Alle Artikel 
            </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    
                    <form action="editArticle.php" method="post" enctype="multipart/form-data">
                        <div class="table-responsive">
                            <table class="tbl table table-light table-striped"> 
                                <thead>
                                    <tr>
                                        <th scope="col">Nr.</th>
                                        <th scope="col">Überschrift</th>
                                        <th scope="col">Text</th>
                                        <th scope="col">News</th>
                                        <th scope="col">Meldungen</th>
                                        <th scope="col">Neuzugang</th>
                                        <th scope="col">Social Media</th>
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
                                if($allArticles) {
                                    foreach($allArticles as $article) {
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
                                            <td><input type="text" name="" title="'. $article["tagNews"] .'" value="'. $article["tagNews"] .'"></td>
                                            <td><input type="text" name="" title="'. $article["tagReviews"] .'" value="'. $article["tagReviews"] .'"></td>
                                            <td><input type="text" name="" title="'. $article["tagPlayer"] .'" value="'. $article["tagPlayer"] .'"></td>
                                            <td><input type="text" name="" title="'. $article["tagSocial"] .'" value="'. $article["tagSocial"] .'"></td>
                                            <!--td><input type="text" name="" value="'. $article["imgName"] .'"></td-->
                                            <!--td><input type="text" name="" value="'. $article["imgPath"] .'"></td-->';
                                            if($article["imgPath"] != "") {
                                                $content .= '
                                                <td>
                                                    <img src="../img/article/'.$article["imgPath"].'" width="50" height="50"/>
                                                </td>';
                                                
                                            } else {
                                                $content .= '
                                                <td>
                                                    <img src="../img/tt-icon.svg" width="50" height="50"/>
                                                </td>';
                                            } 
                                            
                                            $content .= '
                                            <td><input type="text" name="" value="'. $status .'"></td>';
                                            if(isset($_SESSION["admin"])) {
                                                $content .= '<td><a class="btn btn-success" href="editArticle.php?id='. $article["id"] .'">Bearbeiten</a> ';
                                            }
                                            $content .= '
                                        </tr>';
                                    }
                                }
                                $content .= '
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>'; 
}

echo $content;
include('./components/footer.php');