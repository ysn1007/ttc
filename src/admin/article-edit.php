<?php
ob_start();
session_start();
require_once 'dbh.inc.php';

// 1. Modus ermitteln: Ist eine Artikel-ID übergeben worden?
$articleId = (int)($_POST['article_id'] ?? $_GET['id'] ?? 0);
$isEdit    = ($articleId > 0);

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

    // A) ARTIKEL LÖSCHEN
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

    // B) ARTIKEL SPEICHERN (ERSTELLEN / UPDATEN)
    if (isset($_POST["addArticle"]) || isset($_POST["updateArticle"])) {
        var_dump($_POST);
        $headline    = trim($_POST["headline"] ?? '');
        $articleText = trim($_POST["text"] ?? '');
        $publish     = isset($_POST["publish"]) ? 1 : 0;
        
        $tagNews    = isset($_POST["tagNews"]) ? 1 : 0;
        $tagPlayer  = isset($_POST["tagPlayer"]) ? 1 : 0;
        $tagReviews = isset($_POST["tagReviews"]) ? 1 : 0;
        $tagSocial  = isset($_POST["tagSocial"]) ? 1 : 0;

        $imgName     = "artImg";
        $hasNewImage = isset($_FILES["fileName"]) && $_FILES["fileName"]["error"] === 0;

        $file         = null;
        $fileName     = $_FILES["fileName"]["name"] ?? ($_POST["imgName"] ?? '');
        $fileActExt   = '';
        $fileTempName = $_FILES["fileName"]["tmp_name"] ?? '';
        $imgPath      = $_POST["imgPath"] ?? '';

        // BILD-UPLOAD LOGIK
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

            // Neues Bild speichern
            $imgNewName      = $imgName . "." . uniqid("", true) . "." . $fileActExt;
            $fileDestination = "../img/article/" . $imgNewName;

            if ($isEdit) {
                if (!move_uploaded_file($fileTempName, $fileDestination)) {
                    header("Location: article-edit.php?id=" . $articleId . "&error=uploadFailed");
                    exit();
                }
            }

            $imgPath = $imgNewName;
        }

        // --- EXECUTE ADD OR UPDATE ---
        if ($isEdit) {
            updateArticle(
                $con,
                $articleId,
                $headline,
                $articleText,
                $tagNews,
                $tagReviews,
                $tagPlayer,
                $tagSocial,
                $_FILES["fileName"] ?? null,
                $fileName,
                $fileActExt,
                $fileTempName,
                $imgName,
                $publish,
                $imgPath
            );
            header("Location: article.php?update=success");
            exit();
        } else {
            if (empty($headline) || empty($articleText)) {
                header("Location: article-edit.php?error=emptyfields");
                exit();
            }

            $fileDestination = !empty($imgPath) ? "../img/article/" . $imgPath : "";
            
            addArticle($con, $headline, $articleText, $fileName, $imgPath, $publish, $tagNews, $tagPlayer, $tagReviews, $tagSocial, $fileTempName, $fileDestination);
            header("Location: article.php?addArticle=success");
            exit();
        }
    }
}

// 3. HTML-Ausgabe
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
                    <div class="alert alert-danger">Das Bild darf nicht größer als 2MB sein.</div>
                <?php elseif ($_GET['error'] === 'invalidFileType'): ?>
                    <div class="alert alert-danger">Nur JPG, JPEG und PNG Dateien sind erlaubt.</div>
                <?php elseif ($_GET['error'] === 'emptyfields'): ?>
                    <div class="alert alert-danger">Bitte fülle Titel und Artikeltext aus.</div>
                <?php endif; ?>
            <?php endif; ?>

            <form action="<?= htmlspecialchars(basename($_SERVER['PHP_SELF'])) . ($isEdit ? '?id='.$articleId : ''); ?>" method="post" enctype="multipart/form-data">
                
                <?php if ($isEdit): ?>
                    <input type="hidden" name="article_id" value="<?= $articleId; ?>">
                    <input type="hidden" name="imgPath" value="<?= htmlspecialchars($article["imgPath"]); ?>">
                <?php endif; ?>

                <!-- Bild Vorschau & Upload -->
                <div class="col-6 mb-3">
                    <label class="form-label d-block">Artikelbild</label>
                    <?php if ($isEdit && !empty($article["imgPath"]) && file_exists("../img/article/" . $article["imgPath"])): ?>
                        <div class="article-bg-img mb-3" style="width: 270px; height: 180px; background-image: url('../img/article/<?= htmlspecialchars($article["imgPath"]); ?>'); background-size: cover; background-position: center; border-radius: 6px;"></div>
                    <?php endif; ?>
                    <input class="form-control" type="file" name="fileName" accept="image/jpeg,image/png,image/jpg">
                </div>

                <!-- Überschrift -->
                <div class="col-12 mb-3">
                    <label for="headline" class="form-label">Überschrift</label>
                    <input class="form-control" type="text" id="headline" name="headline" placeholder="Überschrift" value="<?= htmlspecialchars($article["headline"]); ?>" required>
                </div>

                <!-- Text -->
                <div class="col-12 mb-3">
                    <label for="text" class="form-label">Artikeltext</label>
                    <textarea class="form-control" id="text" name="text" rows="10" placeholder="Artikeltext..." required><?= htmlspecialchars($article["copytext"]); ?></textarea>
                </div>

                <!-- Tags -->
                <div class="col-12 mb-3">
                    <label class="form-label d-block">Tags</label>
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

                <!-- Status Switcher -->
                <div class="col-12 mb-4">
                    <label class="form-label d-block">Artikel Status</label>
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