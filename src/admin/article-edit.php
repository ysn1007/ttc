<?php
ob_start();
session_start();
require_once 'includes/dbh.inc.php';

// 1. Modus ermitteln: Ist eine Artikel-ID übergeben worden?
$articleId = (int)($_POST['article_id'] ?? $_GET['id'] ?? 0);
$isEdit    = ($articleId > 0);
$editableData = [];

// Standardwerte für "Neuen Artikel anlegen"
$article = [
    'headline'   => '',
    'copytext'   => '',
    'imgPath'    => '',
    'active'     => 1,
    'tagNews'    => 0,
    'tagPlayer'  => 0,
    'tagReviews' => 0,
    'tagSocial'  => 0
];

// Im EDIT-Modus: Daten aus DB laden
if ($isEdit) {
    $dbArticle = getArticleId($con, $articleId);
   
    if ($dbArticle) {
        $article = $dbArticle; // Überschreibt die Standardwerte mit den DB-Daten
    } else {
        header("Location: article.php?error=articleNotFound");
        exit();
    }
}

$pageTitle = $isEdit ? "Artikel bearbeiten" : "Artikel hinzufügen";

// 2. Formular-Verarbeitung (POST)
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Artikel löschen
    if (isset($_POST["deleteArticle"]) && $isEdit) {
        // Altes Bild löschen
        $oldImage = $_POST["imgPath"] ?? '';
        if (!empty($oldImage) && file_exists("../img/article/" . $oldImage)) {
            unlink("../img/article/" . $oldImage);
        }
        
        deleteArticle($con, $articleId);
        header("Location: article.php?delete=success");
        exit();
    }

    // Artikel erstellen (speichern / aktualisieren)
    if (isset($_POST["addArticle"]) || isset($_POST["updateArticle"])) {
        
        $headline    = trim($_POST["headline"] ?? '');
        $articleText = trim($_POST["text"] ?? '');
        $publish     = isset($_POST["publish"]) ? 1 : 0;
        
        $tagNews    = isset($_POST["tagNews"]) ? 1 : 0;
        $tagPlayer  = isset($_POST["tagPlayer"]) ? 1 : 0;
        $tagReviews = isset($_POST["tagReviews"]) ? 1 : 0;
        $tagSocial  = isset($_POST["tagSocial"]) ? 1 : 0;


        // social Media data
        $socialMediaPlatform = $_POST["social_platform"] ?? [];
        $socialMediaURL = $_POST["social_url"] ?? [];

        // Pflichtfeld-Prüfung beim Erstellen
        if (empty($headline) || empty($articleText)) {
            header("Location: article-edit.php?error=emptyfields");
            exit();
        }

        $imgName     = "artImg";
        $hasNewImage = isset($_FILES["fileName"]) && $_FILES["fileName"]["error"] === 0;

        //$file         = null;
        $fileName     = $_FILES["fileName"]["name"] ?? ($_POST["imgName"] ?? '');
        $fileTempName = $_FILES["fileName"]["tmp_name"] ?? '';
        $imgPath      = $_POST["imgPath"] ?? '';
        $fileActExt   = '';
        $articleDate = $_POST['article_date'] ?? date('Y-m-d H:i:s');

        // Bild hochladen
        if ($hasNewImage) {
            $fileError = $_FILES["fileName"]["error"];
            $fileSize  = $_FILES["fileName"]["size"];

            // Dateigröße prüfen (max 2MB)
            if ($fileError === 1 || $fileSize > 2000000) {
                header('Location: article-edit.php?' . ($isEdit ? 'id='.$articleId.'&' : '') . 'error=tooBig');
                exit();
            }

            // Gültige Endung prüfen
            $fileExt    = explode(".", $fileName);
            $fileActExt = strtolower(end($fileExt));
            $allowed    = ["jpg", "jpeg", "png"];

            if (!in_array($fileActExt, $allowed) || !getimagesize($fileTempName)) {
                header("Location: article-edit.php?" . ($isEdit ? 'id='.$articleId.'&' : '') . "error=invalidFileType");
                exit();
            }

            // Altes Bild bei Update löschen
            if ($isEdit && !empty($imgPath) && file_exists("../img/article/" . $imgPath)) {
                unlink("../img/article/" . $imgPath);
            }

           // Neues Bild hochladen & Zielpfad festlegen (Sowohl bei Edit als auch bei Neu)
            $imgNewName      = $imgName . "." . uniqid("", true) . "." . $fileActExt;
            $fileDestination = "../img/article/" . $imgNewName;

            if (!move_uploaded_file($fileTempName, $fileDestination)) {
                header("Location: article-edit.php?" . ($isEdit ? 'id='.$articleId.'&' : '') . "error=uploadFailed");
                exit();
            }

            // Neuer Bild-Dateiname für das Array & DB
            $imgPath = $imgNewName;
        }

        $articleData = [
            'article_id'  => $articleId ?? null,
            'headline'    => $headline,
            'copytext'    => $articleText,
            'tagNews'     => $tagNews,
            'tagReviews'  => $tagReviews,
            'tagPlayer'   => $tagPlayer,
            'tagSocial'   => $tagSocial,
            'articleDate' => $articleDate,  
            'publish'     => $publish,
            'fileName'    => $fileName,
            'fileActExt'  => $fileActExt,
            'fileTempName'=> $fileTempName,
            'imgName'     => $imgName,
            'imgPath'     => $imgPath,
            'fileRaw'     => $_FILES["fileName"] ?? null,
            'smPlatforms' => $socialMediaPlatform ?? null,
            'smURL'       => $socialMediaURL ?? null  
        ];

        

        // Hauptartikel speichern oder aktualisieren
        if ($isEdit) {
            updateArticle( $con, $articleData );
        } else {
            $fileDestination = !empty($imgPath) ? "../img/article/" . $imgPath : "";
            $articleId = addArticle($con, $articleData, $fileDestination);
        }

        // Social Media lInks speichern (Nur wenn eine gültige $articleId existiert)
        if (!empty($articleId) && $articleId > 0) {

            // Bei Bearbeiten (Edit): Alte Verknüpfungen vorher bereinigen
            if ($isEdit) {
                $stmtDel = $con->prepare("DELETE FROM article_social_media WHERE article_id = ?");
                if ($stmtDel) {
                    $stmtDel->bind_param("i", $articleId);
                    $stmtDel->execute();
                    $stmtDel->close();
                }
            }

            // Neue Links einfügen
            if (isset($_POST['social_platform']) && isset($_POST['social_url']) && is_array($_POST['social_platform'])) {
                var_dump("Neue LInks einfügen"."<br>");
                $platforms = $_POST['social_platform'];
                $urls      = $_POST['social_url'];
                var_dump($platforms . "<br>" . $urls);
                $sqlSocial  = "INSERT INTO article_social_media (article_id, platform, url) VALUES (?, ?, ?)";
                $stmtSocial = $con->prepare($sqlSocial);

                if ($stmtSocial) {
                    $platform = '';
                    $url = '';

                    $stmtSocial->bind_param("iss", $articleId, $platform, $url);

                    for ($i = 0; $i < count($platforms); $i++) {
                        $platform = trim($platforms[$i]);
                        $url = trim($urls[$i]);

                        if (!empty($platform) && !empty($url)) {
                            $stmtSocial->execute();
                        }
                    }
                    $stmtSocial->close();
                }
            }
        }
        

        // WEiterleitug nach Erfolg
        if ($isEdit) {
            header("Location: article.php?success=updateArticle");
        } else {
            header("Location: article.php?success=addArticle");
        }
        exit();
        
    }
}

// HTML-Ausgabe
include('./components/header.php');

if (isset($_SESSION["admin"]) || isset($_SESSION["manager"]) || isset($_SESSION["author"])) :
    $isActive = ($article["active"] == 1);
    $checkedAttribute = $isActive ? "checked" : "";
?>

<div class="col edit-article-section">
    <div class="card">
        <div class="card-header">
            <h4><?= $pageTitle; ?> 
                <a href="article.php" class="btn btn-danger float-end">Zurück</a>
            </h4>
        </div>
        
        <div class="card-body">
            <?php if (isset($_GET['error'])): ?>
                <?php if ($_GET['error'] === 'tooBig'): ?>
                    <div class="alert alert-danger d-flex justify-content-between">
                        Das Bild darf nicht größer als 2MB sein.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php elseif ($_GET['error'] === 'invalidFileType'): ?>
                    <div class="alert alert-danger d-flex justify-content-between">
                        Nur JPG, JPEG und PNG Dateien sind erlaubt.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php elseif ($_GET['error'] === 'emptyfields'): ?>
                    <div class="alert alert-danger d-flex justify-content-between">
                        Bitte fülle Titel und Artikeltext aus.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
            <?php endif; ?>    
            

            <form action="<?= htmlspecialchars(basename($_SERVER['PHP_SELF'])) . ($isEdit ? '?id='.$articleId : ''); ?>" method="post" enctype="multipart/form-data">
                
                <?php if ($isEdit): ?>
                    <input type="hidden" name="article_id" value="<?= $articleId; ?>">
                    <input type="hidden" name="imgPath" value="<?= htmlspecialchars($article["imgPath"]); ?>">
                <?php endif; ?>

                <!-- Bild Vorschau & Upload -->
                <div class="col-6 mb-3">
                    <label class="form-label d-block fw-bold">Artikelbild</label>
                    <?php if ($isEdit && !empty($article["imgPath"]) && file_exists("../img/article/" . $article["imgPath"])): ?>
                        <div class="article-bg-img mb-3" style="width: 270px; height: 180px; background-image: url('../img/article/<?= htmlspecialchars($article["imgPath"]); ?>'); background-size: cover; background-position: center; border-radius: 6px;"></div>
                    <?php endif; ?>
                    <input class="form-control" type="file" name="fileName" accept="image/jpeg,image/png,image/jpg">
                </div>

                <!-- Überschrift -->
                <div class="col-12 mb-3">
                    <label for="headline" class="form-label fw-bold">Überschrift</label>
                    <input class="form-control" type="text" id="headline" name="headline" placeholder="Überschrift" value="<?= htmlspecialchars($article["headline"]) ; ?>" required>
                </div>

                <!-- Text -->
                <div class="col-12 mb-3">
                    <label for="text" class="form-label fw-bold">Artikeltext</label>
                    <textarea class="form-control" id="text" name="text" rows="10" placeholder="Artikeltext..." required><?= htmlspecialchars($article["copytext"]); ?></textarea>
                </div>

                <!-- Tags -->
                <div class="col-12 mb-3">
                    <label class="form-label d-block fw-bold">Tags</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="tagNews" id="tagNews" <?= ($article["tagNews"] == 1) ? "checked" : ""; ?>>
                        <label class="form-check-label" for="tagNews">Meldung / Neuigkeiten</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="tagPlayer" id="tagPlayer" <?= ($article["tagPlayer"] == 1) ? "checked" : ""; ?>>
                        <label class="form-check-label" for="tagPlayer">Neuzugang</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="tagReviews" id="tagReviews" <?= ($article["tagReviews"] == 1) ? "checked" : ""; ?>>
                        <label class="form-check-label" for="tagReviews">Spielbericht</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="tagSocial" id="tagSocial" <?= ($article["tagSocial"] == 1) ? "checked" : ""; ?>>
                        <label class="form-check-label" for="tagSocial">Social Media</label>
                    </div>
                </div>

               <div class="social-media-container mb-4" id="social-wrapper" data-social="<?= htmlspecialchars(json_encode($article['social'] ?? [], JSON_UNESCAPED_SLASHES), ENT_QUOTES, 'UTF-8'); ?>">
                    <label class="form-label fw-bold">Social Media Links</label>
                    
                    <div class="d-flex align-items-center gap-2">
                        <select id="social-platform" class="form-select style-select">
                            <option value="FB">Facebook</option>
                            <option value="INS">Instagram</option>
                            <option value="TT">TikTok</option>
                            <option value="YT">YouTube</option>
                        </select>
                        
                        <input 
                            type="url" 
                            id="social-url" 
                            class="form-control style-input" 
                            placeholder="Social-Media Link eingeben"
                            autocomplete="off"
                        />
                        
                        <button type="button" id="social-action-btn" class="btn btn-success d-none" aria-label="Aktion ausführen">
                            <span id="btn-icon">+</span>
                        </button>
                    </div>

                    <div id="social-tags-container" class="d-flex flex-wrap gap-2 mt-2"></div>

                    <!-- Versteckte Inputs mit Null-Coalescing Operator (?? '') abgesichert -->
                    <input type="hidden" name="social_platform[]" value="FB">
                    <input type="hidden" name="social_url[]" id="hidden-FB" value="<?= htmlspecialchars($article['social']['FB'] ?? ''); ?>">

                    <input type="hidden" name="social_platform[]" value="INS">
                    <input type="hidden" name="social_url[]" id="hidden-INS" value="<?= htmlspecialchars($article['social']['INS'] ?? ''); ?>">

                    <input type="hidden" name="social_platform[]" value="TT">
                    <input type="hidden" name="social_url[]" id="hidden-TT" value="<?= htmlspecialchars($article['social']['TT'] ?? ''); ?>">

                    <input type="hidden" name="social_platform[]" value="YT">
                    <input type="hidden" name="social_url[]" id="hidden-YT" value="<?= htmlspecialchars($article['social']['YT'] ?? ''); ?>">
                </div>

                <!-- Status Switcher -->
                <div class="col-12 mb-4">
                    <label class="form-label d-block fw-bold">Artikel Status</label>
                    <div class="d-flex align-items-center gap-2">
                        <span class="notActive">Offline</span>
                        
                        <div class="form-check form-switch mb-0 px-0">
                            <input class="form-check-input ms-0" type="checkbox" id="publish" name="publish" role="switch" <?= $checkedAttribute; ?> style="cursor: pointer; width: 2.5em; height: 1.25em;">
                        </div>
                        
                        <span class="isActive">Veröffentlichen</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="col-12 edit-actions">
                    <div class="row">
                        <div class="col">
                            <?php if ($isEdit): ?>
                                <button class="btn btn-primary" type="submit" name="updateArticle">Artikel Aktualisieren</button>
                            <?php else: ?>
                                <button class="btn btn-success" type="submit" name="addArticle">Artikel Veröffentlichen</button>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($isEdit): ?>
                            <div class="col text-end">
                                <button class="btn btn-danger" type="submit" name="deleteArticle" onclick="return confirm('Artikel wirklich löschen?');">Artikel löschen</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<?php 
endif;

include('./components/footer.php');
ob_end_flush();
?>