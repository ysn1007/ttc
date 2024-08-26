<?php

    $user ="root";
    $psw = "root";
    $host = "localhost";
    $dbName = "ttcr_db";
    
    
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
        $sql = "SELECT * FROM player;";
        $stmt = mysqli_stmt_init($con);
        
        /**
         * prüft Variablen verbindung
        */
        if(!mysqli_stmt_prepare($stmt,$sql)) {
            header("location: login.php?error=loadingPlayersFailed");
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
            header("location: login.php?error=loadingPlayersFailed");
        }
    
        /**
         * Fügt prepared statement mit der richtigen anzahl ein un d übergibt es es der DB.
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

    /**
     * Adds player to data base
     */
    function addPlayer($con, $name, $lastname) {
        $stmt = mysqli_stmt_init($con);
        $sql = "INSERT INTO player (Vorname, Nachname)" . "VALUES (?, ?)";
       
        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: index.ad.php?error=addPlayerFailed");
        }

        mysqli_stmt_bind_param($stmt,"ss", $name, $lastname);
        mysqli_stmt_execute($stmt);

        header("location: index.ad.php?addPlayer=success");
    }

    /**
     * updates player data
     */
    function updatePlayer($con, $playerId, $name, $lastname, $livePZ, $team, $position ) {
        echo "update player - <pre>"; 
        var_dump($playerId, $name, $lastname, $livePZ, $team, $position);
        //var_dump($active."<pre>");
        
        $stmt = mysqli_stmt_init($con);
        $query = "UPDATE player SET Vorname=?, Nachname=?, livePZ=?, team=?, position=?  WHERE id=?";
        //var_dump( $query);exit();

        mysqli_stmt_prepare($stmt, $query);
        mysqli_stmt_bind_param($stmt, "ssssss", $name, $lastname, $livePZ, $team, $position, $playerId);
        
        if(mysqli_stmt_execute($stmt)) {
            header("location: index.ad.php?success=updatePlayer");
        } else {
            header("location: index.ad.php?error=updatePlayerFailed");  
        }
    }

    /**
     * Holt Artikelinhalt aus der Datenbank 
     */
    function getArticle($con) {
    
        $sql = "SELECT * FROM article";
        $stmt = mysqli_stmt_init($con);
        
        if(!mysqli_stmt_prepare($stmt,$sql)) {
            header("location: index.ad.php?error=loadingArticleFailed");
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
            header("location: index.ad.php?error=loadingArticleFailed");
        }
    
        //mysqli_stmt_bind_param($stmt, "i", $active);
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
            header("location: index.ad.php?error=loadingArticleWithIdFailed");
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
    function addArticle($con, $headline, $articleText, $fileName, $imgNewName, $active, $fileTempName, $fileDestination) {
        
        $stmt = mysqli_stmt_init($con);
        
        #ToDo: add tags to DB 
        $tags = "kein #Tag";
        $sql = "INSERT INTO article (headline, copytext, tags, imgName, imgPath, active) VALUES(?,?,?,?,?,?)";

        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: index.ad.php?error=addArticleFailed");
        }
        mysqli_stmt_bind_param($stmt, "ssssss", $headline, $articleText, $tags, $fileName, $imgNewName, $active);
        mysqli_stmt_execute($stmt);
        move_uploaded_file($fileTempName, $fileDestination);

        header("location: index.ad.php?upload=success");
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
            header("location: index.ad.php?error=updateArticleFailed");
        }else {
            header("location: index.ad.php?success=updateArticle");
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
            header("location: index.ad.php?error=deleteArticleFailed");
        }else {

            if(file_exists("../img/article/".$image)) {
                unlink("../img/article/".$image);
            }

            header("location: index.ad.php?success=deleteArticle");
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

        header("location: index.ad.php?upload=success");

    }

    /**
     * Get connection to gallery database
     */
    function getGalleryImgData($con) {
       
        $sql = "SELECT * FROM gallery;";
        $stmt = mysqli_stmt_init($con);
       
        if(!mysqli_stmt_prepare($stmt,$sql)) {
            header("location: index.ad.php?error=loadingGalleryImagesFailed");
        }

        mysqli_stmt_execute($stmt);
        

        //$res = mysqli_stmt_get_result($stmt);
        return mysqli_stmt_get_result($stmt);
        //return $res;

        mysqli_stmt_close($stmt);
        
    }

    /**
     * Get image of an id 
     */
    function getImageId($con, $imgId) {
        $sql = "SELECT * FROM gallery WHERE id = ?; ";
        $stmt = mysqli_stmt_init($con);

        if(!mysqli_stmt_prepare($stmt,$sql)) {
            header("location: index.ad.php?error=loadingGalleryImageWithIdFailed");
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
            header("location: index.ad.php?error=updateImageFailed");
        }else {
            header("location: index.ad.php?success=updateImage");
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
            header("location: index.ad.php?error=deleteGalleryImageFailed");
        }else {

            if(file_exists($imgPath)) {
                unlink($imgPath);
            }

            header("location: index.ad.php?success=deleteGalleryImage");
        }

    }


    /**
     * Gets Images from specific dekade and if they are active to be published
     */
    function getDekadeImages($con, $dekade) {
        $query = "SELECT * FROM gallery WHERE dekade = ? AND active = 1;";
        $stmt = mysqli_stmt_init($con);
        
        if(!mysqli_stmt_prepare($stmt,$query)) {
            header("location: index.ad.php?error=loadingGalleryImageWithIdFailed");
        }

        mysqli_stmt_bind_param($stmt, "s", $dekade);
        mysqli_stmt_execute($stmt);

        $res = mysqli_stmt_get_result($stmt);
        return $res;

        mysqli_stmt_close($stmt);

    }
      
