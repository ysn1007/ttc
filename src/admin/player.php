<?php
require_once 'includes/dbh.inc.php';
include('./components/header.php');


$allPlayerData = getPlayers($con);
$anzahl = count($allPlayerData);
$isAdmin = isset($_SESSION["admin"]);
$isManager = isset($_SESSION["manager"]);
?>

<?php if ($isAdmin || $isManager) : ?>
    <div class="accordion" id="accordionExample-2">
        <section class="img-group-header d-flex justify-content-between align-items-center mb-3 p-4">
            <div class="dekades"><?= $anzahl ?> Spieler vorhanden</div>
            <div class="add-img add-item">
                <a class="btn btn-primary" href="player-edit.php">Spieler hinzufügen</a>
            </div>
        </section>

        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                    Alle Spieler
                </button>
            </h2>

            <div id="collapseTwo" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <?php if (!empty($allPlayerData)) : ?>
                        <form action="editPlayer.php" method="post" enctype="multipart/form-data">
                            <div class="table-responsive">
                                <table class="table tbl table-light table-striped"> 
                                    <thead>
                                        <tr>
                                            <th scope="col">Nr.</th>
                                            <th scope="col">Nachname, Vorname</th>
                                            <th scope="col">LivePZ</th>
                                            <th scope="col">Team</th>
                                            <th scope="col">Position</th>
                                            <th scope="col">Aktiv</th>
                                            
                                            <?php if ($isAdmin) : ?>
                                                <th scope="col">Aktion</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($allPlayerData as $player) : ?>
                                            <tr id="<?= htmlspecialchars($player["id"]) ?>">
                                                <th scope="row"><?= htmlspecialchars($player["id"]) ?></th>
                                                <td><input type="text" name="name" value="<?= htmlspecialchars(($player["Nachname"] ?? '') . ', ' . ($player["Vorname"] ?? '')) ?>"></td>
                                                <td><input type="text" name="livepz" value="<?= htmlspecialchars($player["livePZ"] ?? '') ?>"></td>
                                                <td><input type="text" name="team" value="<?= htmlspecialchars($player["team"] ?? '') ?>"></td>
                                                <td><input type="text" name="position" value="<?= htmlspecialchars($player["position"] ?? '') ?>"></td>
                                                <td><input type="text" name="aktiv" value="<?= htmlspecialchars($player["aktiv"] ?? '') ?>"></td>
                                                
                                                
                                                <?php if ($isAdmin) : ?>
                                                <td><a href="player-edit.php?id=<?= urlencode($player["id"]) ?>" class="btn btn-success">Bearbeiten</a></td>
                                                <?php endif; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    <?php else : ?>
                        <p class="text-danger">Keine Spielerdaten vorhanden.</p>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php
include './components/footer.php';
?>