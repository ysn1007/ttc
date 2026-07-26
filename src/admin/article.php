<?php
require_once 'includes/dbh.inc.php';
include('./components/header.php');

$allArticles = getArticle($con);

if (isset($_SESSION["admin"]) || isset($_SESSION["manager"]) || isset($_SESSION["author"])) :
?>

<div class="accordion" id="accordionExample-3">
    <section class="img-group-header d-flex justify-content-between align-items-center mb-3 p-4">
        <div class="dekades">Anzahl Artikel gespeichert: <?= is_array($allArticles) || $allArticles instanceof Countable ? count($allArticles) : 0; ?></div>
        <div class="add-img add-item">
            <a class="btn btn-primary" href="article-edit.php">Artikel hinzufügen</a>
        </div>
    </section>    

    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                Alle Artikel 
            </button>
        </h2>
        
        <div id="collapseThree" class="accordion-collapse collapse show" data-bs-parent="#accordionExample-3">
            <div class="accordion-body">
                <div class="table-responsive">
                    <table class="tbl table table-light table-striped align-middle"> 
                        <thead>
                            <tr>
                                <th scope="col">Nr.</th>
                                <th scope="col">Überschrift</th>
                                <th scope="col">Text</th>
                                <th scope="col">Artikelart</th>
                                <th scope="col">Bild</th>
                                <th scope="col">Erstellungsdatum</th>
                                <th scope="col">Social Media</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-end">Aktion</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php if ($allArticles): ?>
                                <?php foreach ($allArticles as $article): ?>
                                    <?php 
                                        $isOnline = ($article["active"] == 1);
                                        
                                        $tagMap = [
                                            'tagNews'    => 'Neues',
                                            'tagReviews' => 'Bericht',
                                            'tagPlayer'  => 'Neuzugang',
                                            'tagSocial'  => 'Soziale Medien'
                                        ];

                                        $articleTypes = [];
                                        foreach ($tagMap as $tag => $label) {
                                            if (!empty($article[$tag])) {
                                                $articleTypes[] = $label;
                                            }
                                        }
                                        $articleTypeString = !empty($articleTypes) ? implode(', ', $articleTypes) : '-';
                                    ?>
                                    <tr>
                                        <th scope="row"><?= htmlspecialchars($article["id"]); ?></th>
                                        <td class="fw-regular"><?= htmlspecialchars($article["headline"]); ?></td>
                                        <td style="max-width: 250px;" class="text-truncate" title="<?= htmlspecialchars($article["copytext"]); ?>">
                                            <?= htmlspecialchars($article["copytext"]); ?>
                                        </td>
                                        <td><?= htmlspecialchars($articleTypeString); ?></td>
                                        <td>
                                            <?php if (!empty($article["imgPath"]) && file_exists("../img/article/" . $article["imgPath"])): ?>
                                                <img src="../img/article/<?= htmlspecialchars($article["imgPath"]); ?>" alt="Artikelbild" width="60" height="60" style="object-fit: cover; border-radius: 4px;" />
                                            <?php else: ?>
                                                <span class="text-muted fs-7">Kein Bild</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            Datum
                                        </td>
                                        <td>
                                            Social Media Tags
                                        </td>
                                        <td style="width: 10%;">
                                            <span class="badge <?= $isOnline ? 'bg-success' : 'bg-secondary'; ?>">
                                                <?= $isOnline ? 'Online' : 'Offline'; ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <a class="btn btn-success" href="article-edit.php?id=<?= $article["id"]; ?>">Bearbeiten</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Keine Artikel vorhanden.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
endif;

include('./components/footer.php');
?>