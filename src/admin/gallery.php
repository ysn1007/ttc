<?php
require_once 'includes/dbh.inc.php';
include('./components/header.php');

$dekade = $_GET['dekade'] ?? '';
$res = getDekadeImages($con, $dekade);
$count = ($res && $res instanceof mysqli_result) ? $res->num_rows : 0;
?>

<div class="accordion gal" id="gal-data-group">
    <section class="img-group-header d-flex justify-content-between align-items-center mb-3 p-4">
        <div class="dekades">
            <?= htmlspecialchars($count); ?> Bilder aus <?= htmlspecialchars($dekade ? $dekade : 'allen Dekaden'); ?>
        </div>
        <div class="add-img add-item">
            <a class="btn btn-primary" href="gallery-edit.php">Bild hinzufügen</a>
        </div>
    </section>

    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Alle Bilder dieses Jahrzehnts
            </button>
        </h2>
        
        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#gal-data-group">
            <div class="accordion-body">
                <div class="table-responsive">
                    <table class="table table-light table-striped align-middle"> 
                        <thead>
                            <tr>
                                <th scope="col" style="width: 5%;">Nr.</th>
                                <th scope="col" style="width: 20%;">Titel</th>
                                <th scope="col" style="width: 35%;">Beschreibung</th>
                                <th scope="col" style="width: 10%;">Jahr</th>
                                <th scope="col" style="width: 15%;">Bild</th>
                                <?php if (isset($_SESSION["admin"]) || isset($_SESSION["manager"]) || isset($_SESSION["author"])): ?>
                                    <th scope="col" style="width: 15%;" class="text-end">Aktion</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($count > 0): ?>
                                <?php while ($row = $res->fetch_assoc()): ?>
                                    <tr id="<?php echo $row["id"] ?>">
                                        <th scope="row"><?= htmlspecialchars($row['id']); ?></th>
                                        <td class="fw-regular"><?= htmlspecialchars($row['title']); ?></td>
                                        <td class="text-truncate" style="max-width: 300px;" title="<?= htmlspecialchars($row['descript']); ?>">
                                            <?= htmlspecialchars($row['descript']); ?>
                                        </td>
                                        <td><?= htmlspecialchars($row['imageYear']); ?></td>
                                        <td>
                                            <?php if (!empty($row['imagePath'])): ?>
                                                <img src="<?= htmlspecialchars($row['imagePath']); ?>" alt="Galeriebild" width="60" height="60" style="object-fit: cover; border-radius: 4px;" />
                                            <?php else: ?>
                                                <span class="text-muted fs-7">Kein Bild</span>
                                            <?php endif; ?>
                                        </td>
                                        <?php if (isset($_SESSION["admin"]) || isset($_SESSION["manager"]) || isset($_SESSION["author"])): ?>
                                            <td class="text-end">
                                                <a href="gallery-edit.php?id=<?= $row["id"]; ?>" class="btn btn-success">Bearbeiten</a>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Keine Bilder für dieses Jahrzehnt vorhanden.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('./components/footer.php'); ?>