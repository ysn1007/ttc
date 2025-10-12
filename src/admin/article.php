<?php
require_once 'dbh.inc.php';
include('./components/header.php');

$allArticles = getArticle($con);
$content = "";
 //var_dump($_SESSION);exit;
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
                                        <th scope="col">Artikelart</th>
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
                                       
                                        $tagMap = [
                                            'tagNews'   => 'Neuigkeiten',
                                            'tagReviews'=> 'Spielberichte',
                                            'tagPlayer' => 'Neuzugang',
                                            'tagSocial' => 'Soziale Medien'
                                        ];

                                        $articleTypes = [];

                                        foreach ($tagMap as $tag => $label) {
                                            if (!empty($article[$tag])) {
                                                $articleTypes[] = $label;
                                            }
                                        }

                                        $articleTypeString = implode(', ', $articleTypes);

                                        

                                        $content .= '
                                        <tr>
                                            <th scope="row">'. $article["id"] .'</th>
                                            <td><input type="text" name="" value="'. $article["headline"] .'" ></td>
                                            <td style="width:20%"><input type="text" name="" title="'. $article["copytext"] .'" value="'. $article["copytext"] .'"></td>
                                            <td><input type="text" name="" title="Artikelart" value="'. htmlspecialchars($articleTypeString) .'"></td>
                                            <td><img src="../img/article/'.$article["imgPath"].'" width="50" height="50"/></td>
                                            <td style="width:5%"><input type="text" name="" value="'. $status .'"></td>';
                                            if($_SESSION["admin"] == 1 || $_SESSION["manager"] == 1 || $_SESSION["author"] == 1) {
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