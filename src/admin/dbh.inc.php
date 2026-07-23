<?php
// Startet die Session nur dann, wenn noch keine aktive Session existiert (verhindert PHP-Warnungen)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/admin/config.ad.php');

// Mysqli so einstellen, dass es bei DB-Fehlern Exceptions wirft (Standard ab PHP 8.1+)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $con = new mysqli(
        $cfg["database"]["host"],
        $cfg["database"]["user"],
        $cfg["database"]["psw"],
        $cfg["database"]["dbName"]
    );
    
    // Setze den Zeichensatz explizit auf UTF-8 (wichtig für Umlaute & Sonderzeichen)
    $con->set_charset("utf8mb4");

} catch (mysqli_sql_exception $e) {
    // Fehler ins PHP-Log schreiben, statt Zugangsdaten öffentlich im Browser anzuzeigen
    error_log("Database Connection Error: " . $e->getMessage());
    die("Datenbankverbindung fehlgeschlagen. Bitte versuchen Sie es später erneut.");
}


/**
 * Holt alle Spieler aus der Datenbank
 */
function getPlayers(mysqli $con): array {
    $sql = "SELECT * FROM player ORDER BY aktiv DESC, nachname ASC";
    $result = $con->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Holt einen einzelnen Spieler nach seiner ID
 */
function getPlayersId(mysqli $con, int $id): array {
    $stmt = $con->prepare("SELECT * FROM player WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    $player = $stmt->get_result()->fetch_assoc();
    return $player ? [$player] : [];
}

/**
 * Holt alle aktiven Spieler eines bestimmten Teams, sortiert nach Position
 */
function getActivePlayersOfTeam(mysqli $con, int $teamNr): array {
    $stmt = $con->prepare("SELECT * FROM player WHERE team = ? AND aktiv = 1 ORDER BY position ASC");
    $stmt->bind_param("i", $teamNr);
    $stmt->execute();

    // Holt ALLE passenden Spieler als assoziatives Array:
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/**
 * Adds player to data base
 */
function addPlayer( mysqli $con, string $name, string $lastname, int $livePZ, int $team, int $position, int $active ) : bool {
        $sql = "INSERT INTO player (Vorname, Nachname, livePZ, team, position, aktiv) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssiiii", $name, $lastname, $livePZ, $team, $position, $active); 
        return $stmt->execute();
        
    }

/**
 * updates player data
 */
function updatePlayer( mysqli $con, int $playerId, string $name, string $lastname, int $livePZ, int $team, int $position, int $active) : bool {
    $sql = "UPDATE player SET Vorname=?, Nachname=?, livePZ=?, team=?, position=?, aktiv=?  WHERE id=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssiiiii", $name, $lastname, $livePZ, $team, $position, $active, $playerId);

    return $stmt->execute();
}

/**
 * deletes player
 */
function deletePlayer(mysqli $con, int $pid): bool {
    $sql = "DELETE FROM player WHERE id = ?";
    $stmt = $con->prepare($sql);
    
    if (!$stmt) {
        // Falls die Vorbereitung fehlschlägt
        return false;
    }

    $stmt->bind_param("i", $pid);

    return $stmt->execute();
}

/**
 * Holt Artikelinhalt aus der Datenbank 
 */
function getArticle($con) {

    $sql = "SELECT * FROM article";
    //$stmt = mysqli_stmt_init($con);
    $stmt = $con->prepare($sql);
    
    /**
     * prüft Variablen verbindung und protokollieren Fehler
    */
    if (!$stmt) {
        header("Location: article.php?error=keineDatenbankVerbindung"); 
        return null; 
    }

    /**
     * Führt Abfrage aus
     * 
    */
    $stmt->execute();

    /**
     * Gibt das SQL resultat als assoziatives Array zurück und schließt statement.
     * 
    */
    $result = $stmt->get_result();
    $allArticles = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    /**
     * Gibt Articles zurück
     * 
    */
    return $allArticles;

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
function updateArticle($con, $articleId, $headline, $articleText, $tagNews, $tagReviews, $tagPlayer, $tagSocial, $file, $fileName, $fileActExt, $fileTempName, $imgName, $publish, $imgPath ) {
    
    // Sicherheitscheck: ID muss numerisch sein
    if (!is_numeric($articleId)) {
        header("Location: article.php?error=invalidId");
        exit();
    }

    $tagNews = intval($tagNews);
    $tagReviews = intval($tagReviews);
    $tagPlayer = intval($tagPlayer);
    $tagSocial = intval($tagSocial);
    $articleId = intval($articleId);
    $publish = intval($publish);

    /**
     * Datenbankverbindung herstellen
     */
    $stmt = mysqli_stmt_init($con);

    /** 
     * Prüfe, ob ein neues Bild übergeben wurde (also $fileActExt vorhanden) 
     * 
    */
    $hasNewImage = !empty($fileActExt) && !empty($imgPath);


    if ($hasNewImage) {
        // Update mit Bild
        $query = "UPDATE article  SET headline=?, copytext=?, tagNews=?, tagReviews=?, tagPlayer=?, tagSocial=?, imgName=?, imgPath=?, active=? WHERE id=?";
        mysqli_stmt_prepare($stmt, $query);
        mysqli_stmt_bind_param( $stmt, "ssiiiissii", $headline, $articleText, $tagNews, $tagReviews, $tagPlayer, $tagSocial, $fileName, $imgPath, $publish, $articleId );
    } else {
        // Update ohne Bild (Bildfelder bleiben wie sie sind)
        $query = "UPDATE article SET headline=?, copytext=?, tagNews=?, tagReviews=?, tagPlayer=?, tagSocial=?, active=? WHERE id=?";
        mysqli_stmt_prepare($stmt, $query);
        mysqli_stmt_bind_param( $stmt, "ssiiiiii", $headline, $articleText, $tagNews, $tagReviews, $tagPlayer, $tagSocial, $publish, $articleId );
    }
    
    /**
     * Ausführung
     */
    if(!mysqli_stmt_execute($stmt)) {
        header("location: article.php?error=updateArticleFailed");
        exit;
    }
    
    header("location: article.php?success=updateArticle");
    exit();
    
}

/**
 * Löscht Artikel von der Datenbank
 */
function deleteArticle($con, $articleId) {

    // Sicherheitscheck: ID muss numerisch sein
    if (!is_numeric($articleId)) {
        header("Location: article.php?error=invalidId");
        exit();
    }

    // Bildpfad sicher auslesen
    $stmt = mysqli_stmt_init($con);
    $query = "SELECT imgPath FROM article WHERE id = ?";
    mysqli_stmt_prepare($stmt, $query);
    mysqli_stmt_bind_param($stmt, "i", $articleId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result || mysqli_num_rows($result) === 0) {
        header("Location: article.php?error=articleNotFound");
        exit();
    }

    $row = mysqli_fetch_assoc($result);
    $image = $row["imgPath"];

    // Artikel löschen
    //$stmt = mysqli_stmt_init($con);
    $query = "DELETE FROM article WHERE id = ?";
    mysqli_stmt_prepare($stmt, $query);
    mysqli_stmt_bind_param($stmt, "i", $articleId);

    if (!mysqli_stmt_execute($stmt)) {
        header("Location: article.php?error=deleteArticleFailed");
        exit();
    }

    // Bild löschen
    $imagePath = "../img/article/" . $image;
    if (!empty($image) && file_exists($imagePath)) {
        unlink($imagePath);
    }

    // Weiterleitung nach erfolgreichem Löschen
    header("Location: article.php?success=deleteArticle");
    exit();
}

/**
 * Aktualisiert Artikelbild
 */
function updateArticleImage($con, $articleId, $file, $fileName, $fileActExt, $fileTempName, $imgName) {
    // Sicherheitsprüfung: ID muss numerisch sein
    if (!is_numeric($articleId)) {
        header("Location: article.php?error=invalidId");
        exit;
    }

    // Neuen Dateinamen generieren
    $imgNewName = $imgName . "." . uniqid("", true) . "." . $fileActExt;
    $uploadPath = "../img/article/" . $imgNewName;


    // Datei verschieben
    if (!move_uploaded_file($fileTempName, $uploadPath)) {
        header("Location: article.php?error=uploadFailed");
        exit;
    }

    $stmt = mysqli_stmt_init($con);
    $sql = "UPDATE article SET imgName=?, imgPath=? WHERE id=?";
    
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: article.php?error=prepareFailed");
        exit;
    }

    // Hier binden wir den Dateinamen und Pfad korrekt
    $imgPath = $imgNewName;
    mysqli_stmt_bind_param($stmt, "ssi", $fileName, $imgPath, $articleId);

    if (!mysqli_stmt_execute($stmt)) {
        header("Location: article.php?error=updateFailed");
        exit;
    }

    header("Location: article.php?updateImage=success");
    exit;
}

/**
 * Gallerie
 * Bilder in der Galerie verarbeiten
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

    header("location: index.ad.php");

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
        header("location: gallery.php?dekade=".$dekade." ");
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

        header("location: gallery.php?dekade=".$resData['dekade']."");
    }

}

/**
 * Gets Images from specific dekade and if they are active to be published
 */
function getDekadeImages($con, $dekade) {
    $query = "SELECT * FROM gallery WHERE dekade = ? AND active = 1 ORDER BY created DESC;";
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

/**
 * Admin index data
 * 
 */


/**
 * Gets last 10 created active images from gallery for admin index
 */
function getLastImages($con) {
    $sql = "SELECT * FROM gallery WHERE active = 1 ORDER BY created DESC LIMIT 10;";
    $stmt = $con->prepare($sql);

    /**
     * prüft Variablen verbindung und protokollieren Fehler
    */
    if (!$stmt) {
        error_log("SQL-Fehler: " . $con->error); 
        return null; 
    }

    /**
     * Führt Abfrage aus
     * 
    */
    $stmt->execute();

    /**
     * Gibt das SQL resultat als assoziatives Array zurück und schließt statement.
    */
    $result = $stmt->get_result();
    $lastArticles = $result->fetch_all(MYSQLI_ASSOC); 
    $stmt->close();

    return $lastArticles;
}

/**
 * Gets all players alphabetical order
 */
function getAllPlayers($con): ?array {

    /**
     * Prepared statement
    */
    $sql = "SELECT * FROM player ORDER BY Nachname ASC";
    $stmt = $con->prepare($sql);

    /**
     * prüft Variablen verbindung und protokollieren Fehler
    */
    if (!$stmt) {
        error_log("SQL-Fehler: " . $con->error); 
        return null; 
    }

    /**
     * Führt Abfrage aus
     * 
    */
    $stmt->execute();

    /**
     * Gibt das SQL resultat als assoziatives Array zurück und schließt statement.
    */
    $result = $stmt->get_result();
    $players = $result->fetch_all(MYSQLI_ASSOC); 
    $stmt->close();

    /*
        * Gibt players zurück
    */
    return $players;
}

