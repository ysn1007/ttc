<?php
ob_start();
require_once 'dbh.inc.php';

// ID flexibel abfangen (aus POST beim Speichern ODER aus GET beim Aufrufen)
$playerId = (int)($_POST['player_id'] ?? $_GET['id'] ?? 0);

if ($playerId <= 0) {
    die("Ungültige Spieler ID");
}

// 1. Formular-Verarbeitung (POST)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // Spieler-Daten aktualisieren
    if (isset($_POST["updatePlayer"])) {
        $name     = trim($_POST["name"] ?? '');
        $lastname = trim($_POST["lastname"] ?? '');
        $livePZ   = (int)($_POST["livePZ"] ?? 0);
        $team     = (int)($_POST["team"] ?? 0);
        $position = (int)($_POST["position"] ?? 0);
        $active   = (isset($_POST['active']) && $_POST['active'] === 'on') ? 1 : 0;
        
        updatePlayer($con, $playerId, $name, $lastname, $livePZ, $team, $position, $active);

        header("Location: player.php?update=success");
        exit();
    }

    // Spieler löschen
    if (isset($_POST["deletePlayer"])) {
        if (deletePlayer($con, $playerId)) {
            header("Location: player.php?delete=success");
            exit();
        } else {
            header("Location: player.php?error=deletePlayerFailed");
            exit();
        }
    }
}

// 2. HTML-Ausgabe
include('./components/header.php');

if (isset($_SESSION["admin"]) || isset($_SESSION["manager"])) :
    
    // Holt Spielerdaten mit bestimmter id
    $playerData = getPlayersId($con, $playerId);

    // Falls kein Spieler ermittelt wurde
    if (empty($playerData)) {
        die("Spieler wurde nicht gefunden.");
    }

    // Spieler setzen
    $player = $playerData[0];
    // isActive setzen
    $isActive = ($player["aktiv"] == 1);
    // isActiveChecked Prüfung
    $checkedAttribute = $isActive ? "checked" : "";
?>

<div class="col edit-player-section">
    <div class="card">
        <div class="card-header">
            <h4>Spieler bearbeiten 
                <a href="javascript:history.go(-1)" class="btn btn-danger float-end">Zurück</a>
            </h4>
        </div>

        <div class="card-body">
            <form action="<?= htmlspecialchars(basename($_SERVER['PHP_SELF'])); ?>" method="post">
                <input type="hidden" name="player_id" value="<?= $playerId; ?>">

                <div class="col-12 mb-3">
                    <div class="row">
                        <div class="col-4">
                            <label class="form-label" for="name">Vorname</label>
                            <input class="form-control" type="text" id="name" name="name" placeholder="Vorname" value="<?= htmlspecialchars($player["Vorname"]); ?>" required>
                        </div>

                        <div class="col-4">
                            <label class="form-label" for="lastname">Nachname</label>
                            <input class="form-control" type="text" id="lastname" name="lastname" placeholder="Nachname" value="<?= htmlspecialchars($player["Nachname"]); ?>" required>
                        </div>

                        <div class="col-4">
                            <label class="form-label" for="livePZ">LivePZ</label>
                            <input class="form-control" type="number" id="livePZ" name="livePZ" placeholder="TT live Punkte" value="<?= htmlspecialchars($player["livePZ"]); ?>">
                        </div>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <div class="row">
                        <div class="col-4">
                            <label class="form-label" for="team">Team</label>
                            <input class="form-control" type="number" id="team" name="team" placeholder="Mannschaft" value="<?= htmlspecialchars($player["team"]); ?>">
                        </div>
                        <div class="col-4">
                            <label class="form-label" for="position">Position</label>
                            <input class="form-control" type="number" id="position" name="position" placeholder="Position" value="<?= htmlspecialchars($player["position"]); ?>">
                        </div>
                    </div>
                </div>

                
                <div class="col-12 mb-5 mt-4">
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label d-block">Spieler Status</label>
                            <div class="d-flex align-items-center gap-2">
                                <span class="">Inaktiv</span>
                                
                                <div class="form-check form-switch mb-0 px-0">
                                    <input class="form-check-input ms-0" type="checkbox" id="active" name="active" role="switch" <?= $checkedAttribute; ?> style="cursor: pointer; width: 2.5em; height: 1.25em; background-color: #9daba9; border-color: #7a8583;">
                                </div>
                                
                                <span class="active">Aktiv</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 edit-actions">
                    <div class="row">
                        <div class="col">
                            <button class="btn btn-primary" type="submit" name="updatePlayer">Aktualisieren</button>
                        </div>
                        <div class="col text-end">
                            <button class="btn btn-danger" type="submit" name="deletePlayer" onclick="return confirm('Spieler wirklich löschen?');">Spieler löschen</button>
                        </div>
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