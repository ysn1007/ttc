<?php
ob_start();
require_once 'includes/dbh.inc.php';

// 1. Modus ermitteln: Ist eine ID übergeben worden?
$playerId = (int)($_POST['player_id'] ?? $_GET['id'] ?? 0);
$isEdit   = ($playerId > 0);

// Standardwerte für "Neuen Spieler anlegen"
$player = [
    'Vorname'  => '',
    'Nachname' => '',
    'livePZ'   => 0,
    'team'     => 0,
    'position' => 0,
    'aktiv'    => 1
];

// Im EDIT-Modus: Daten aus DB laden
if ($isEdit) {
    $playerData = getPlayersId($con, $playerId);
    if (empty($playerData)) {
        die("Spieler wurde in der Datenbank nicht gefunden.");
    }
    $player = $playerData[0];
}

$pageTitle = $isEdit ? "Spieler bearbeiten" : "Neuen Spieler anlegen";

// 2. Formular-Verarbeitung (POST)
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name     = trim($_POST["name"] ?? '');
    $lastname = trim($_POST["lastname"] ?? '');
    $livePZ   = (int)($_POST["livePZ"] ?? 0);
    $team     = (int)($_POST["team"] ?? 0);
    $position = (int)($_POST["position"] ?? 0);
    $active   = (isset($_POST['active']) && $_POST['active'] === 'on') ? 1 : 0;

    // A) SPIELER ERSTELLEN
    if (isset($_POST["addPlayer"])) {
        if (!empty($name) && !empty($lastname)) {
            if (addPlayer($con, $name, $lastname, $livePZ, $team, $position, $active)) {
                header("Location: player.php?addPlayer=success");
                exit();
            }
        }
        header("Location: player-edit.php?error=emptyfields");
        exit();
    }

    // B) SPIELER AKTUALISIEREN
    if (isset($_POST["updatePlayer"]) && $isEdit) {
        updatePlayer($con, $playerId, $name, $lastname, $livePZ, $team, $position, $active);
        header("Location: player.php?update=success");
        exit();
    }

    // C) SPIELER LÖSCHEN
    if (isset($_POST["deletePlayer"]) && $isEdit) {
        if (deletePlayer($con, $playerId)) {
            header("Location: player.php?delete=success");
            exit();
        } else {
            header("Location: player.php?error=deleteFailed");
            exit();
        }
    }
}

// 3. HTML-Ausgabe
include('./components/header.php');

if (isset($_SESSION["admin"]) || isset($_SESSION["manager"])) :
    $isActive = ($player["aktiv"] == 1);
    $checkedAttribute = $isActive ? "checked" : "";
?>

<div class="col edit-player-section">
    <div class="card">
        <div class="card-header">
            <h4><?= $pageTitle; ?> 
                <a href="player.php" class="btn btn-danger float-end">Zurück</a>
            </h4>
        </div>
        
        <div class="card-body">
            <?php if (isset($_GET['error']) && $_GET['error'] === 'emptyfields'): ?>
                <div class="alert alert-danger">Bitte fülle Vorname und Nachname aus.</div>
            <?php endif; ?>

            <form action="<?= htmlspecialchars(basename($_SERVER['PHP_SELF'])) . ($isEdit ? '?id='.$playerId : ''); ?>" method="post">
                
                <?php if ($isEdit): ?>
                    <input type="hidden" name="player_id" value="<?= $playerId; ?>">
                <?php endif; ?>

                <div class="col-12 mb-3">
                    <div class="row">
                        <div class="col-4">
                            <label for="name" class="form-label">Vorname</label>
                            <input class="form-control" type="text" id="name" name="name" placeholder="Vorname" value="<?= htmlspecialchars($player["Vorname"]); ?>" required>
                        </div>

                        <div class="col-4">
                            <label for="lastname" class="form-label">Nachname</label>
                            <input class="form-control" type="text" id="lastname" name="lastname" placeholder="Nachname" value="<?= htmlspecialchars($player["Nachname"]); ?>" required>
                        </div>
                        
                        <div class="col-4">
                            <label for="livePZ" class="form-label">LivePZ</label>
                            <input class="form-control" type="number" id="livePZ" name="livePZ" placeholder="TT live Punkte" value="<?= htmlspecialchars($player["livePZ"]); ?>">
                        </div>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <div class="row">
                        <div class="col-4">
                            <label for="team" class="form-label">Team</label>
                            <input class="form-control" type="number" id="team" name="team" placeholder="Mannschaft" value="<?= htmlspecialchars($player["team"]); ?>">
                        </div>
                        <div class="col-4">
                            <label for="position" class="form-label">Position</label>
                            <input class="form-control" type="number" id="position" name="position" placeholder="Position" value="<?= htmlspecialchars($player["position"]); ?>">
                        </div>
                    </div>
                </div>

                <!-- Status Switcher -->
                <div class="col-12 mb-5 mt-4">
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label d-block">Spieler Status</label>
                            <div class="d-flex align-items-center gap-2">
                                <span class="notActive">Inaktiv</span>
                                
                                <div class="form-check form-switch mb-0 px-0">
                                    <input class="form-check-input ms-0" type="checkbox" id="active" name="active" role="switch" <?= $checkedAttribute; ?> style="cursor: pointer; width: 2.5em; height: 1.25em;">
                                </div>
                                
                                <span class="isActive">Aktiv</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 edit-actions">
                    <div class="row">
                        <div class="col">
                            <?php if ($isEdit): ?>
                                <button class="btn btn-primary" type="submit" name="updatePlayer">Aktualisieren</button>
                            <?php else: ?>
                                <button class="btn btn-success" type="submit" name="addPlayer">Spieler anlegen</button>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($isEdit): ?>
                            <div class="col text-end">
                                <button class="btn btn-danger" type="submit" name="deletePlayer" onclick="return confirm('Spieler wirklich löschen?');">Spieler löschen</button>
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