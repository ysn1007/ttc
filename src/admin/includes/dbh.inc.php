<?php

use Vtiful\Kernel\Format;
// Startet die Session nur dann, wenn noch keine aktive Session existiert (verhindert PHP-Warnungen)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/config.ad.php');

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
function getPlayers(mysqli $con, ?int $limit = null): array {
    
    $sql = "SELECT * FROM player ORDER BY aktiv DESC, Nachname ASC";

    if ($limit !== null) {
        $sql .= " LIMIT ?";
    }

    $stmt = $con->prepare($sql);
    
    // Prüft Verbindung/Statement und leitet bei Fehler weiter
    if (!$stmt) {
        //header("Location: index.ad.php?error=SpielerDataFehler"); 
        echo $con->error; exit();
        exit(); 
    }

    if ($limit != null) {
       $stmt->bind_param("i", $limit); 
    }
    // Führt Abfrage aus
    $stmt->execute();
    // Gibt das SQL-Resultat als assoziatives Array zurück und schließt das Statement
    $result = $stmt->get_result();
    $getPlayers = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $getPlayers;
}

/**
 * Holt einen einzelnen Spieler nach seiner ID
 */
function getPlayersId(mysqli $con, int $id): array {
    $stmt = $con->prepare("SELECT * FROM player WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    $player = $stmt->get_result()->fetch_assoc();
    // liefert ein Ergebnis in einem Array oder ein leeres Array zurück, da ein Array als Rückgabewert erwartet wird.
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
function getArticle(mysqli $con, ?int $limit = null ) :array {

    $sql = "SELECT * FROM article ORDER BY id DESC";
    
    // Falls ein Limit übergeben wurde, hängen wir LIMIT an
    if ($limit !== null) {
        $sql .= " LIMIT ?";
    }

    $stmt = $con->prepare($sql);
    
    // Prüft Verbindung/Statement und leitet bei Fehler weiter
    if (!$stmt) {
        header("Location: article.php?error=keineDatenbankVerbindung"); 
        exit(); 
    }

    // Falls ein Limit gesetzt ist, binden wir den Parameter als Integer ("i") ein
    if ($limit !== null) {
        $stmt->bind_param("i", $limit);
    }

    // Führt Abfrage aus
    $stmt->execute();
    // Gibt das SQL-Resultat als assoziatives Array zurück und schließt das Statement
    $result = $stmt->get_result();
    $allArticles = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $allArticles;
}

/**
 * Holt Artikelinhalt aus der Datenbank wenn diese Aktiv sind
 */
function getActiveArticle(mysqli $con) {
    $sql = "SELECT * FROM article WHERE active = 1";
    $stmt = $con->prepare($sql);
    
    if(!$stmt) {
        header("Location: article.php?error=loadingArticleFailed");
    }

    $stmt -> execute();
    // Gibt das SQL-Resultat als assoziatives Array zurück und schließt das Statement
    $result = $stmt->get_result();
    $articles = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $articles;

}

/**
 * Get all article with id
 */
function getArticleId(mysqli $con, $id) {

    if (!is_numeric($id)) {
        header("Location: article.php?error=invalidId");
        exit();
    }

    $id = (int)$id;

    // Hauptartikel abfragen
    $sql = "SELECT * FROM article WHERE id = ?";
    $stmtArticle = $con->prepare($sql);

    if (!$stmtArticle) {
        header("Location: article.php?error=loadingArticleWithIdFailed");
        exit();
    }

    $stmtArticle->bind_param("i", $id);
    $stmtArticle->execute();
    $result = $stmtArticle->get_result();
    $article = $result->fetch_assoc(); // Holt die Zeile direkt als assoziatives Array
    $stmtArticle->close();

    // Standardstruktur für Social-Media-Links anlegen
    $article['social'] = [
        'FB'  => '',
        'INS' => '',
        'TT'  => '',
        'YT'  => ''
    ];

    // 2. Social-Media-Links aus der Datenbank laden
    $sqlSocial = "SELECT platform, url FROM article_social_media WHERE article_id = ?";
    $stmtSocial = $con->prepare($sqlSocial);

    if ($stmtSocial) {
        $stmtSocial->bind_param("i", $id);
        $stmtSocial->execute();
        $resultSocial = $stmtSocial->get_result();

        while ($row = $resultSocial->fetch_assoc()) {
            $platform = $row['platform'];
            if (array_key_exists($platform, $article['social'])) {
                $article['social'][$platform] = $row['url'];
            }
        }
        $stmtSocial->close();
    }

    return $article; 
}

/**
 * Fügt Artikel zur Datenbank
 */
function addArticle( mysqli $con, array $articleData, string $fileDestination) {

    // Spalten-Reihenfolge passend zu bind_param:
    // headline(s), copytext(s), tagNews(i), tagPlayer(i), tagReviews(i), tagSocial(i), imgName(s), imgPath(s), active(i), article_date(s)
    $sql = "INSERT INTO article (headline, copytext, tagNews, tagReviews, tagPlayer, tagSocial, imgName, imgPath, active, article_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $con->prepare($sql);

    if (!$stmt) {
        // Error handling
        error_log($con->error); 
        header("Location: article.php?error=addArticleFailed");
    exit();
}

    $article_id     = $articleData['aritcle_id'] ?? '';     
    $headline       = $articleData['headline'] ?? '';
    $copyText       = $articleData['copytext'] ?? '';
    $tagNews        = (int)($articleData['tagNews'] ?? 0);
    $tagPlayer      = (int)($articleData['tagPlayer'] ?? 0);
    $tagReviews     = (int)($articleData['tagReviews'] ?? 0);
    $tagSocial      = (int)($articleData['tagSocial'] ?? 0);
    $imgName        = $articleData['fileName'] ?? '';
    $imgPath        = $articleData['imgPath'] ?? '';
    $active         = (int)($articleData['publish'] ?? 0);
    $article_date   = $articleData['articleDate'] ?? date('Y-m-d H:i:s');
    $fileTempName   = $articleData['fileTempName'] ?? '';

    //var_dump($imgPath);exit;
    // Bild-Handling
    if (empty($imgPath)) {
        $fileName = "Kein Bild";
        $imgNewName = "";
    } else {
        if (!empty($fileTempName) && !empty($fileDestination)) {
            move_uploaded_file($fileTempName, $fileDestination);
        }
    }

    // Bind-Typen exakt synchron zum SQL-String:
    // s (headline), s (articleText), i (tagNews), i (tagPlayer), i (tagReview), i (tagSocial), s (imgName), s (imgPath), i (active), s (article_date)
    $stmt->bind_param(
        "ssiiiissis", 
        $headline, 
        $copyText, 
        $tagNews, 
        $tagReviews, 
        $tagPlayer, 
        $tagSocial, 
        $imgName, 
        $imgPath, 
        $active,
        $article_date
    );

    $executed = $stmt->execute();

    if ($executed) {
        $newId = $stmt->insert_id; 
        $stmt->close();
        return $newId; 
    } else {
        $stmt->close();
        return false;
    }
}

/**
 * Bearbeitet ausgewählte Artikel in der Datenbank 
 */
function updateArticle(mysqli $con, array $articleData) {
    $articleId      = $articleData['article_id'] ?? null;
    $headline       = $articleData['headline'] ?? '';
    $articleText    = $articleData['copytext'] ?? '';
    $tagNews        = (int)($articleData['tagNews'] ?? 0);
    $tagPlayer      = (int)($articleData['tagPlayer'] ?? 0);
    $tagReviews     = (int)($articleData['tagReviews'] ?? 0);
    $tagSocial      = (int)($articleData['tagSocial'] ?? 0);
    $publish        = $articleData['publish'];
    $fileName       = $articleData['imgName'] ?? '';
    $imgPath        = $articleData['imgPath'] ?? '';
    $fileActExt     = $articleData['fileActExt'] ?? ''; 

    $article_date   = $articleData['articleDate'] ?? date('Y-m-d H:i:s');
    

    // Sicherheitscheck: ID muss numerisch sein
    if (!is_numeric($articleId)) {
        header("Location: article.php?error=invalidId");
        exit();
    }

    $articleId = (int)$articleId;

    // ----------------------------------------------------
    // Hauptartikel aktualisieren
    // ----------------------------------------------------
    $tagNews   = (int)$tagNews;
    $tagReviews= (int)$tagReviews;
    $tagPlayer = (int)$tagPlayer;
    $tagSocial = (int)$tagSocial;
    $articleId = (int)$articleId;
    $publish   = (int)$publish;

    $hasNewImage = !empty($fileActExt) && !empty($imgPath);

    if ($hasNewImage) {
        // Update mit neuem Bild
        $query = "UPDATE article SET headline=?, copytext=?, tagNews=?, tagReviews=?, tagPlayer=?, tagSocial=?, imgName=?, imgPath=?, active=? WHERE id=?";
        $stmt  = $con->prepare($query);
        if (!$stmt) {
            header("Location: article.php?error=updateArticleFailed");
            exit();
        }
        // Typen: "ssiiiissii" (2x String, 4x Int, 2x String, 2x Int)
        $stmt->bind_param("ssiiiissii", $headline, $articleText, $tagNews, $tagReviews, $tagPlayer, $tagSocial, $fileName, $imgPath, $publish, $articleId);
    } else {
        // Update ohne Bildänderung
        $query = "UPDATE article SET headline=?, copytext=?, tagNews=?, tagReviews=?, tagPlayer=?, tagSocial=?, active=? WHERE id=?";
        $stmt  = $con->prepare($query);
        if (!$stmt) {
            header("Location: article.php?error=updateArticleFailed");
            exit();
        }
        // Typen: "ssiiiiii" (2x String, 6x Int)
        $stmt->bind_param("ssiiiiii", $headline, $articleText, $tagNews, $tagReviews, $tagPlayer, $tagSocial, $publish, $articleId);
    }

    // ----------------------------------------------------
    // Social-Media aktualisieren
    // ----------------------------------------------------

    $smPlatforms = $articleData['smPlatforms'] ?? [];
    $smURLs      = $articleData['smURL'] ?? [];

    if (is_array($smPlatforms) && is_array($smURLs)) {

        // Bisherige Eintrags-Zeilen für diesen Artikel bereinigen
        $deleteQuery = "DELETE FROM article_social_media WHERE article_id = ?";
        $stmtDelete  = $con->prepare($deleteQuery);
        if ($stmtDelete) {
            $stmtDelete->bind_param("i", $articleId);
            $stmtDelete->execute();
            $stmtDelete->close();
        }

        // Nur die Plattformen mit tatsächlich vorhandener URL neu einfügen
        $insertQuery = "INSERT INTO article_social_media (article_id, platform, url) VALUES (?, ?, ?)";
        $stmtInsert  = $con->prepare($insertQuery);

        if ($stmtInsert) {
            foreach ($smPlatforms as $index => $platform) {
                $url = trim($smURLs[$index] ?? '');

                // Leere Felder überspringen
                if ($url !== '') {
                    $stmtInsert->bind_param("iss", $articleId, $platform, $url);
                    $stmtInsert->execute();
                }
            }
            $stmtInsert->close();
        }
    }

    if (!$stmt->execute()) {
        header("Location: article.php?error=updateArticleFailed");
        exit();
    }

    header("Location: article.php?success=updateArticle");
    exit();
}

/**
 * Löscht Artikel von der Datenbank
 */
function deleteArticle(mysqli $con, $articleId) {

    // Sicherheitscheck: ID muss numerisch sein
    if (!is_numeric($articleId)) {
        header("Location: article.php?error=invalidId");
        exit();
    }

    $articleId = (int)$articleId;

    // Bildpfad auslesen
    $stmt = $con->prepare("SELECT imgPath FROM article WHERE id = ?");
    if (!$stmt) {
        header("Location: article.php?error=deleteArticleFailed");
        exit();
    }

    $stmt->bind_param("i", $articleId);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result || $result->num_rows === 0) {
        header("Location: article.php?error=articleNotFound");
        exit();
    }

    $row   = $result->fetch_assoc();
    $image = $row["imgPath"];

    // Artikel aus DB löschen
    $stmtDelete = $con->prepare("DELETE FROM article WHERE id = ?");
    if (!$stmtDelete) {
        header("Location: article.php?error=deleteArticleFailed");
        exit();
    }

    $stmtDelete->bind_param("i", $articleId);

    if (!$stmtDelete->execute()) {
        header("Location: article.php?error=deleteArticleFailed");
        exit();
    }

    // Bilddatei vom Server entfernen
    if (!empty($image)) {
        $imagePath = "../img/article/" . $image;
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    // Erfolgreiche Weiterleitung
    header("Location: article.php?success=deleteArticle");
    exit();
}


/**
 * Gallerie
 * Bilder in der Galerie verarbeiten
 */


function getImages(mysqli $con, ?int $limit = null, bool $lastestFirst = false) :array {
    $sql = "SELECT * FROM gallery WHERE active = 1 ";
    
    if ($lastestFirst) {
        $sql .=" ORDER BY created DESC"; 
    } 

    if ($limit !== null ) {
        $sql .= " LIMIT ?";
    }

    $stmt = $con->prepare($sql);

    // Prüft Verbindung/Statement und leitet bei Fehler weiter
    if (!$stmt) {
        header("Location: index.ad.php?error=keineDatenbankVerbindung"); 
        exit(); 
    }

    // Falls ein Limit gesetzt ist, binden wir den Parameter als Integer ("i") ein
    if ($limit !== null) {
        $stmt->bind_param("i", $limit);
    }

    // Führt Abfrage aus
    $stmt->execute();

    // Gibt das SQL resultat als assoziatives Array zurück und schließt statement.
    $result = $stmt->get_result();
    $getImages = $result->fetch_all(MYSQLI_ASSOC); 
    $stmt->close();

    return $getImages;
}

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
 * Get image of an id 
 */
function getImageId(mysqli $con, int $id) {
    $stmt = $con->prepare("SELECT * FROM gallery WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result();
}

/**
 * Updates image data
 */
function updateImage(mysqli $con, int $imageId, string $title, string $descript, string $year, string $dekade, int $active) {
    $stmt = $con->prepare("UPDATE gallery SET title = ?, descript = ?, imageYear = ?, dekade = ?, active = ? WHERE id = ?");
    $stmt->bind_param("ssssii", $title, $descript, $year, $dekade, $active, $imageId);
    return $stmt->execute();
}

/**
 * Deletes image of parameter id
 */
function deleteImage(mysqli $con, int $imageId) {
    // Zuerst Pfad holen, um die Datei physikalisch zu löschen
    $res = getImageId($con, $imageId);
    if ($res && $row = $res->fetch_assoc()) {
        $filePath = $row['imagePath'];
        if (!empty($filePath) && file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // Eintrag aus der Datenbank entfernen
    $stmt = $con->prepare("DELETE FROM gallery WHERE id = ?");
    $stmt->bind_param("i", $imageId);
    return $stmt->execute();
}

/**
 * Gets Images from specific dekade and if they are active to be published
 */
function getDekadeImages(mysqli $con, string $dekade) {
    if (!empty($dekade)) {
        $stmt = $con->prepare("SELECT * FROM gallery WHERE dekade = ? ORDER BY id DESC");
        $stmt->bind_param("s", $dekade);
        $stmt->execute();
        return $stmt->get_result();
    } else {
        // Fallback: Alle Bilder laden
        return $con->query("SELECT * FROM gallery ORDER BY id DESC");
    }
}



