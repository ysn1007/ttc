<?php
require_once 'dbh.inc.php';
session_start();

// Dekaden-Zuordnung
$dekadenMap = [
    1 => "1950-1959",
    2 => "1960-1969",
    3 => "1970-1979",
    4 => "1980-1989",
    5 => "1990-1999",
    6 => "2000-2009",
    7 => "2010-2019",
    8 => "2020-2029"
];

// POST-Aktionen verarbeiten
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // --- BILD NEU ERSTELLEN ---
    if (isset($_POST["submit"])) {
        $headline = $_POST["headline"] ?? '';
        $year     = !empty($_POST["year"]) ? $_POST["year"] : "0";
        $imgText  = $_POST["text"] ?? '';
        $dekadeId = (int)($_POST["dekade"] ?? 0);
        $dekade   = $dekadenMap[$dekadeId] ?? '';
        $active   = isset($_POST["publish"]) ? 1 : 0;

        if (empty($headline) || empty($imgText)) {
            header("Location: gallery-edit.php?upload=empty");
            exit();
        }

        if (empty($dekade)) {
            header("Location: gallery-edit.php?dekade=empty");
            exit();
        }

        if (!empty($_FILES["imgName"]["name"][0])) {
            $allowed = ["jpg", "jpeg", "png"];
            
            foreach ($_FILES["imgName"]["name"] as $key => $file) {
                $fileError = $_FILES["imgName"]["error"][$key];
                $fileSize  = $_FILES["imgName"]["size"][$key];
                $tmpName   = $_FILES["imgName"]["tmp_name"][$key];

                $fileExt    = explode('.', $file);
                $fileActExt = strtolower(end($fileExt));

                if (in_array($fileActExt, $allowed)) {
                    if ($fileSize > 2000000) {
                        header("Location: gallery-edit.php?upload=imagesToBig");
                        exit();
                    }

                    if ($fileError === 0) {
                        $uniq = bin2hex(random_bytes(2));
                        $imgNewName = "img-" . $year . "-" . $dekade . "-" . $uniq . "." . $fileActExt;
                        $targetDir  = "../img/gallery/" . $dekade . "/";

                        if (!file_exists($targetDir)) {
                            mkdir($targetDir, 0777, true);
                        }

                        $fileDestination = $targetDir . $imgNewName;
                        move_uploaded_file($tmpName, $fileDestination);

                        addImage($con, $headline, $imgText, $year, $dekade, $file, $imgNewName, $active, $tmpName, $fileDestination);
                    }
                }
            }
        }
        header("Location: gallery.php?dekade=" . urlencode($dekade));
        exit();
    }

    // --- BILD UPDATE ---
    if (isset($_POST["updateImage"])) {
        $imageId  = $_POST["imageId"];
        $title    = $_POST["headline"];
        $descript = $_POST["text"];
        $year     = $_POST["year"];
        $dekadeId = (int)$_POST["dekade"];
        $dekade   = $dekadenMap[$dekadeId] ?? '';
        $active   = isset($_POST["publish"]) ? 1 : 0;

        updateImage($con, $imageId, $title, $descript, $year, $dekade, $active);
        header("Location: gallery.php?dekade=" . urlencode($dekade));
        exit();
    }

    // --- BILD LÖSCHEN ---
    if (isset($_POST["deleteImage"])) {
        $imageId = $_POST["imageId"];
        $dekadeId = (int)($_POST["dekade"] ?? 0);
        $dekade   = $dekadenMap[$dekadeId] ?? '';
        deleteImage($con, $imageId);
        // Weiterleitung zurück in die Ursprungs-Dekade
        if (!empty($dekade)) {
            header("Location: gallery.php?dekade=" . urlencode($dekade));
        } else {
            header("Location: gallery.php");
        }
        exit();
    }
}

// Daten fürs Bearbeiten abrufen
$isEdit = false;
$image = [
    'id'        => '',
    'title'     => '',
    'descript'  => '',
    'imageYear' => '',
    'dekade'    => '',
    'imagePath' => '',
    'active'    => 1
];

if (isset($_GET["id"])) {
    $res = getImageId($con, $_GET["id"]);
    if ($res && $row = mysqli_fetch_assoc($res)) {
        $image  = $row;
        $isEdit = true;
    }
}

include('./components/header.php');

if (isset($_SESSION["admin"]) || isset($_SESSION["manager"]) || isset($_SESSION["author"])) :
?>

<div class="col edit-article-section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><?= $isEdit ? 'Bild bearbeiten' : 'Bild hinzufügen'; ?></h4>
            <a href="javascript:history.go(-1)" class="btn btn-danger">Zurück</a>
        </div>
        <div class="card-body">
            <form action="gallery-edit.php" method="post" enctype="multipart/form-data">
                <?php if ($isEdit): ?>
                    <input type="hidden" name="imageId" value="<?= htmlspecialchars($image["id"]); ?>">
                    <!-- Sicherstellen, dass die Dekade beim Löschen erhalten bleibt -->
                    <input type="hidden" name="currentDekade" value="<?= htmlspecialchars($image["dekade"]); ?>">

                    <?php if (!empty($image["imagePath"])): ?>
                        <div class="col-6 mb-3">
                            <div class="article-bg-img" style="width: 270px; height: 200px; background-image: url('<?= htmlspecialchars($image["imagePath"]); ?>'); background-size: cover; background-position: center; border-radius: 6px;"></div>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="col-6 mb-3">
                        <label class="form-label fw-bold">Bild(er) auswählen</label>
                        <input class="form-control" type="file" name="imgName[]" multiple required>
                    </div>
                <?php endif; ?>

                <div class="row mb-3">
                    <div class="col-12 col-md-4 mb-3 mb-md-0">
                        <label class="form-label">Jahr</label>
                        <input class="form-control" type="text" name="year" placeholder="z.B. 1984" value="<?= htmlspecialchars($image["imageYear"]); ?>">
                    </div> 
                    <div class="col-12 col-md-4 mb-3 mb-md-0">
                        <label class="form-label">Überschrift / Titel *</label>
                        <input class="form-control" type="text" name="headline" placeholder="Bildüberschrift" value="<?= htmlspecialchars($image["title"]); ?>" required>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Jahrzehnt *</label>
                        <select class="form-select" name="dekade" required>
                            <option value="" disabled <?= !$isEdit ? 'selected' : ''; ?>>* Jahrzehnt auswählen</option>
                            <?php foreach ($dekadenMap as $key => $val): ?>
                                <option value="<?= $key; ?>" <?= ($image['dekade'] == $val) ? 'selected' : ''; ?>>
                                    <?= $val; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label">Bildtext / Beschreibung *</label>
                    <textarea class="form-control" name="text" rows="4" placeholder="Bildtext" required><?= htmlspecialchars($image["descript"]); ?></textarea>
                </div>
                
                <div class="col-12 mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="publish" id="publish" <?= ($image["active"] == 1) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="publish">Bild Veröffentlichen</label>
                    </div>
                </div>

                <div class="col-12 d-flex justify-content-between align-items-center">
                    <?php if ($isEdit): ?>
                        <button class="btn btn-primary" type="submit" name="updateImage">Bild Aktualisieren</button>
                        <button class="btn btn-danger" type="submit" name="deleteImage" onclick="return confirm('Wirklich löschen?');">Bild löschen</button>
                    <?php else: ?>
                        <button class="btn btn-primary" type="submit" name="submit">Bild hochladen</button>
                    <?php endif; ?>
                </div>
            </form> 
        </div>
    </div>
</div>

<?php 
endif;

include('./components/footer.php');
?>