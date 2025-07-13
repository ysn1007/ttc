<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/admin/config.ad.php');
session_start();
    $user = $cfg["database"]["user"];
    $psw = $cfg["database"]["psw"];
    $host = $cfg["database"]["host"];
    $dbName = $cfg["database"]["dbName"];
    
    
    // connect to database
    $con = mysqli_connect($host, $user, $psw, $dbName);
    
    if($con->connect_error) { 
        echo ('DB connection error: ' . $con->connect_error . "// FehlerNr: " . $con->connect_errno);
        exit();
    } 
    return $con;
    $con->close();


    /**
     * Holt alle Spieler aus der Datenbank
     */
    function getPlayers($con) {
    
        /**
         * Prepared statement
        */
        $sql = "SELECT * FROM player ORDER BY team ASC, position;";
        $stmt = mysqli_stmt_init($con);
        
        /**
         * prüft Variablen verbindung
        */
        if(!mysqli_stmt_prepare($stmt,$sql)) {
            header("location: player.php?error=loadingPlayersFailed");
        }
    
        /**
         * Fügt prepared statement mit der richtigen anzahl ein un d übergibt es es der DB.
         * 
        */
        //mysqli_stmt_bind_param($stmt);
        mysqli_stmt_execute($stmt);
    
        /**
         * Gibt das SQL resultat zurück.
        */
        $resultData = mysqli_stmt_get_result($stmt);
        return $resultData;
        
        mysqli_stmt_close($stmt);
    }
    
    /**
     * get player of id
     */
    function getPlayersId($con, $id) {
    
        /**
         * Prepared statement
        */
        $sql = "SELECT * FROM player WHERE id = ?;";
        $stmt = mysqli_stmt_init($con);
        
        /**
         * prüft Variablen verbindung
        */
        if(!mysqli_stmt_prepare($stmt,$sql)) {
            header("location: player.php?error=loadingPlayersFailed");
        }
    
        /**
         * Fügt prepared statement mit der richtigen anzahl ein und übergibt es es der DB.
         * 
        */
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
    
        /**
         * Gibt das SQL resultat zurück.
        */
        $resultData = mysqli_stmt_get_result($stmt);
        return $resultData;
        
        mysqli_stmt_close($stmt);
    }


    function getActivePlayersOfTeam($con, $teamNr) {
        /**
         * Prepared statement
        */
        $sql = "SELECT * FROM player WHERE team = ? AND aktiv = 1 ORDER BY position ASC;";
        $stmt = mysqli_stmt_init($con);

        /**
         * prüft Variablen verbindung
        */
        if(!mysqli_stmt_prepare($stmt,$sql)) {
            header("location: player.php?error=loadingTeamFailed");
        }

        /**
         * Fügt prepared statement mit der richtigen anzahl ein und übergibt es es der DB.
         * 
        */
        mysqli_stmt_bind_param($stmt, "i", $teamNr);
        mysqli_stmt_execute($stmt);

        /**
         * Gibt das SQL resultat zurück.
        */
        $resultData = mysqli_stmt_get_result($stmt);
        return $resultData;
        
        mysqli_stmt_close($stmt);
    }

    /**
     * Adds player to data base
     */
    function addPlayer($con, $name, $lastname, $livePZ, $team, $position, $active, $spv, $sbem ) {
        $stmt = mysqli_stmt_init($con);
        $sql = "INSERT INTO player (Vorname, Nachname, livePZ, team, position, aktiv, spv, sbem) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
       
        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: player.php?error=addPlayerFailed");
        }

        mysqli_stmt_bind_param($stmt,"ssiiisss", $name, $lastname, $livePZ, $team, $position, $active, $spv, $sbem);
        mysqli_stmt_execute($stmt);

        header("location: player.php?addPlayer=success");
    }

    /**
     * updates player data
     */
    function updatePlayer($con, $playerId, $name, $lastname, $livePZ, $team, $position, $active, $spv, $sbem ) {
        
        $stmt = mysqli_stmt_init($con);
        $query = "UPDATE player SET Vorname=?, Nachname=?, livePZ=?, team=?, position=?, aktiv=?, spv=?, sbem=?  WHERE id=?";

        mysqli_stmt_prepare($stmt, $query);
        mysqli_stmt_bind_param($stmt, "ssssssssi", $name, $lastname, $livePZ, $team, $position, $active, $spv, $sbem, $playerId);
        
        if(mysqli_stmt_execute($stmt)) {
            header("location: player.php?success=updatePlayer");
        } else {
            header("location: player.php?error=updatePlayerFailed");  
        }
    }


    /**
     * deletes player
     */
    function deletePlayer($con, $pid) {
        
        $stmt = mysqli_stmt_init($con);
        $query = "DELETE FROM player WHERE id = ?";

        mysqli_stmt_prepare($stmt, $query);
        mysqli_stmt_bind_param($stmt, "i", $pid);

        if(!mysqli_stmt_execute($stmt)) {
            header("location: player.php?error=deletePlayerFailed");
        } else {
            header("location: player.php?success=deletePlayer");
        }

    }

    /**
     * Holt Artikelinhalt aus der Datenbank 
     */
    function getArticle($con) {
    
        $sql = "SELECT * FROM article";
        $stmt = mysqli_stmt_init($con);
        
        if(!mysqli_stmt_prepare($stmt,$sql)) {
            header("location: article.php?error=loadingArticleFailed");
        }
    
        //mysqli_stmt_bind_param($stmt, "i");
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return $result;
        
        mysqli_stmt_close($stmt);
    }

    /**
     * Holt Artikelinhalt aus der Datenbank wenn diese Aktiv sind
     */
    function getActiveArticle($con) {
        $sql = "SELECT * FROM article WHERE active = 1";
        $stmt = mysqli_stmt_init($con);
        
        if(!mysqli_stmt_prepare($stmt,$sql)) {
            header("location: article.php?error=loadingArticleFailed");
        }
    
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return $result;
        
        mysqli_stmt_close($stmt);
    }

    /**
     * Get all article with id
     */
    function getArticleId($con, $id) {
        $sql = "SELECT * FROM article WHERE id= ?; ";
        $stmt = mysqli_stmt_init($con);

        if(!mysqli_stmt_prepare($stmt,$sql)) {
            header("location: article.php?error=loadingArticleWithIdFailed");
        }

        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return $result;

        mysqli_stmt_close($stmt);
    }

    /**
     * Fügt Artikel zur Datenbank
     */
    function addArticle($con, $headline, $articleText, $fileName, $imgNewName, $active, $tagNews, $tagPlayer, $tagReview, $tagSocial, $fileTempName, $fileDestination) {
        $stmt = mysqli_stmt_init($con);
        $sql = "INSERT INTO article (headline, copytext, tagNews, tagPlayer, tagReviews, tagSocial, imgName, imgPath, active) VALUES(?,?,?,?,?,?,?,?,?)";
        
        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: article.php?error=addArticleFailed");
        }
        
        if($fileName == ""){
            $fileName =   "Kein Bild";
            $imgNewName = "";
            mysqli_stmt_bind_param($stmt, "ssiiiisss", $headline, $articleText, $tagNews, $tagPlayer, $tagReview, $tagSocial, $fileName, $imgNewName, $active);
            mysqli_stmt_execute($stmt);
            header("location: article.php?upload=success");
        } else {
            mysqli_stmt_bind_param($stmt, "ssiiiisss", $headline, $articleText, $tagNews, $tagPlayer, $tagReview, $tagSocial, $fileName, $imgNewName, $active);
            mysqli_stmt_execute($stmt);   
            move_uploaded_file($fileTempName, $fileDestination);
            header("location: article.php?upload=success");
        }
       
    }
    
    /**
     * Bearbeitet ausgewählte Artikel in der Datenbank 
     */
    function updateArticle($con, $articleId, $headline, $articleText, $active ) {
        
        $stmt = mysqli_stmt_init($con);
        $query = "UPDATE article SET headline=?, copytext=?, active=? WHERE id=?";

        mysqli_stmt_prepare($stmt,$query);
        mysqli_stmt_bind_param($stmt, "ssss", $headline, $articleText, $active, $articleId);
        
        if(!mysqli_stmt_execute($stmt)) {
            header("location: article.php?error=updateArticleFailed");
        }else {
            header("location: article.php?success=updateArticle");
        }
        
    }

    /**
     * Löscht Artikel von der Datenbank
     */
    function deleteArticle($con, $articleId) {

        $loadImgPath = "SELECT * FROM article WHERE id=$articleId";
        $imgRes = mysqli_query($con, $loadImgPath);
        $resData = mysqli_fetch_array($imgRes);

        $image = $resData["imgPath"];
        $stmt = mysqli_stmt_init($con);
        $query = "DELETE FROM article WHERE id=?";

        mysqli_stmt_prepare($stmt,$query);
        mysqli_stmt_bind_param($stmt, "s", $articleId);

        if(!mysqli_stmt_execute($stmt)) {
            header("article.php?error=deleteArticleFailed");
        }else {
            if(file_exists("../img/article/".$image)) {
                $dir = getcwd();
                chdir("../img/article/");
                unlink($image);
                chdir($dir);
            }

            header("article.php?success=deleteArticle");
        }

    }

    /**
     * Gallerie
     * Bilder in der Gallerie verarbeiten
     */

     // Bild hochladen und abspeichern
    function addImage($con, $headline, $imgText, $year, $dekade, $fileName, $imgNewName, $active, $fileTempName, $fileDestination) {
        $tags = "kein Tag";
        $jetzt = time();
        $datum = date("Y.m.d H:i:s", $jetzt);

        /*variante1 - über oop*/
        $stmt2 = $con->stmt_init();
        $stmt2->prepare("INSERT INTO gallery (title, descript, imageYear, dekade, imageName, newImageName, imagePath, tags, active, created, modified) VALUES(?,?,?,?,?,?,?,?,?,?,?)"); 
        $stmt2->bind_param("sssssssssss", $headline, $imgText, $year, $dekade, $fileName, $imgNewName, $fileDestination, $tags, $active, $datum, $datum);
        $stmt2->execute();

        header("location: gallery.php?upload=success");

    }

    /**
     * Get connection to gallery database
     */
    function getGalleryImgData($con) {
       
        $sql = "SELECT * FROM gallery;";
        $stmt = mysqli_stmt_init($con);
       
        if(!mysqli_stmt_prepare($stmt,$sql)) {
            header("location: gallery.php?error=loadingGalleryImagesFailed");
        }

        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);

        mysqli_stmt_close($stmt);
        
    }

    /**
     * Get image of an id 
     */
    function getImageId($con, $imgId) {
        $sql = "SELECT * FROM gallery WHERE id = ?; ";
        $stmt = mysqli_stmt_init($con);

        if(!mysqli_stmt_prepare($stmt,$sql)) {
            header("location: gallery.php?error=loadingGalleryImageWithIdFailed");
        }

        mysqli_stmt_bind_param($stmt, "i", $imgId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return $result;

        mysqli_stmt_close($stmt);
    }

    /**
     * Updates image data
     */
    function updateImage($con, $imageId, $title, $descript, $year, $dekade, $active ) {
        $stmt = mysqli_stmt_init($con);
        $query = "UPDATE gallery SET title=?, descript=?, imageYear=?, dekade=?, active=? WHERE id=?";

        mysqli_stmt_prepare($stmt,$query);
        mysqli_stmt_bind_param($stmt, "ssssss", $title, $descript, $year, $dekade, $active, $imageId);
        
        if(!mysqli_stmt_execute($stmt)) {
            header("location: gallery.php?error=updateImageFailed");
        }else {
            header("location: gallery.php?success=updateImage");
        }
    }

    /**
     * Deletes image of parameter id
     */
    function deleteImage($con, $imageId) {
        $imgData = "SELECT * FROM gallery WHERE id = $imageId";
        $res = mysqli_query($con, $imgData);
        $resData = mysqli_fetch_array($res);

        $imgPath = $resData['imagePath'];
        
        $stmt = mysqli_stmt_init($con);
        $query = "DELETE FROM gallery WHERE id = ?";

        mysqli_stmt_prepare($stmt, $query);
        mysqli_stmt_bind_param($stmt, "s", $imageId);

        if(!mysqli_stmt_execute($stmt)) {
            header("location: gallery.php?error=deleteGalleryImageFailed");
        }else {

            if(file_exists($imgPath)) {
                unlink($imgPath);
            }

            header("location: gallery.php?success=deleteGalleryImage");
        }

    }


    /**
     * Gets Images from specific dekade and if they are active to be published
     */
    function getDekadeImages($con, $dekade) {
        $query = "SELECT * FROM gallery WHERE dekade = ? AND active = 1;";
        $stmt = mysqli_stmt_init($con);
        
        if(!mysqli_stmt_prepare($stmt,$query)) {
            header("location: gallery.php?error=loadingGalleryImageWithIdFailed");
        }

        mysqli_stmt_bind_param($stmt, "s", $dekade);
        mysqli_stmt_execute($stmt);

        $res = mysqli_stmt_get_result($stmt);
        return $res;

        mysqli_stmt_close($stmt);

    }
      
