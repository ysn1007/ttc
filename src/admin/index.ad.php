<?php
require_once 'dbh.inc.php';

$content = '';
//var_dump($_SESSION['useruid']);exit;

if(!isset($_SESSION["useruid"])) {
    echo "Verbindungsfehler: 500";
} else {
    include('./components/header.php');
        if(isset($_SESSION["admin"])) {
            $content .= '
            

            <div class="col-12">
                <div class="row">
                    <div class="col-4" id="addPlayerPanel">
                       
                        <div class="card">
                            <div class="card-header">
                                <h4>Spieler Hiunzufügen</h4>
                            </div>

                            <div class="card-body">
                                <form action="'.basename($_SERVER['PHP_SELF']).'" method="post" enctype="multipart/form-data">
                                    
                                    <div class="col-12 mb-3">
                                        <div class="row">
                                            <div class="col-4">
                                                <label>Name</label><br>
                                                <input class="form-control" type="text" name="name" placeholder="Vorname" >
                                            </div>

                                            <div class="col-4">
                                                <label>Name</label><br>
                                                <input class="form-control" type="text" name="lastname" placeholder="Nachname" >
                                            </div>
                                            
                                            <div class="col-4">
                                                <label>LivePZ</label><br>
                                                <input class="form-control" type="text" name="livePZ" placeholder=" TT live Punkte" >
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <div class="row">
                                            <div class="col-4">
                                                <label>Team</label><br>
                                                <input class="form-control" type="text" name="team" placeholder="Mannschaft" >
                                            </div>
                                            <div class="col-4">
                                                <label>Position</label><br>
                                                <input class="form-control" type="text" name="position" placeholder="Position">
                                            </div>
                                            <!--div class="col-4">
                                                <label>E-Mail</label><br>
                                                <input class="form-control" type="text" name="eMail" placeholder="eMail" >
                                            </div-->
                                            
                                        </div>
                                    </div>

                                    <!--div class="col-12 mb-4">
                                        <div class="row">
                                            <div class="col-2">
                                                <input type="checkbox" name="active"  checked>
                                                <label class="mr-1" for="publish">Aktiv setzen</label>
                                            </div>
                                        
                                            <div class="col-2">
                                                <input type="checkbox" name="spv">
                                                <label class="mr-1" for="publish">Sperrvermerk setzen</label>
                                            </div>
                                        
                                            <div class="col-3">
                                                <input type="checkbox" name="sbem">
                                                <label class="mr-1" for="publish">Spieler mit Erwachsenenbetrieb setzen</label>
                                            </div>
                                        </div>
                                    </div-->
                                    
                                    <div class="col-12 edit-actions">
                                        <div class="row">
                                            <div class="col">
                                                <button class="btn btn-primary" type="submit" name="submit">Spieler hinzufügen</button>    
                                            </div>
                                        </div>
                                    </div>
                                </form> 
                            </div>
                        
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>Artikel hinzufügen</h4>
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
                    </div>
                    <div class="col-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>Bild hinzufügen</h4>
                            </div>
                            <div class="card-body">
                                <form action="'.basename($_SERVER['PHP_SELF']).'" method="post" enctype="multipart/form-data">
                                    <div class="col-6 mb-3">
                                        <input class="img-upload" type="file" name="imgName[]" multiple>
                                        <!--input class="img-name" type="text" name="imgName" placeholder="Bildname"-->
                                    </div>
                                    <div class="row row-cols-2">
                                        <div class="col-12">
                                            <div class="col-12 mb-3">
                                                <input class="form-control" type="text" name="year" placeholder="Das Jahr">
                                            </div> 
                                            <div class="col-12 mb-3">
                                                <input class="form-control" type="text" name="headline" placeholder="* Bildüberschrift">
                                            </div>
                                        
                                        </div>
                                        <div class="col-12">
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
                    </div>
                </div>
            </div>';
        }   
    echo $content;
    include('./components/footer.php');
}

?>


