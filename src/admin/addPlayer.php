<?php
ob_start();
require_once 'dbh.inc.php';
// session_start(); // Falls Sessions genutzt werden, hier aktivieren

// 1. Formular-Verarbeitung (POST)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {

    // Inputs einlesen & säubern
    $name     = trim($_POST['name'] ?? $_POST['vorname'] ?? '');
    $lastname = trim($_POST['lastname'] ?? $_POST['nachname'] ?? '');
    $livePZ   = (int)($_POST['livePZ'] ?? 0);
    $team     = (int)($_POST['team'] ?? 0);
    $position = (int)($_POST['position'] ?? 0);
    
    // Checkbox-Auswertung
    $active   = (isset($_POST['active']) && $_POST['active'] === 'on') ? 1 : 0;

    // Validierung
    if (empty($name) || empty($lastname)) {
        header("Location: addPlayer.php?error=emptyfields");
        exit();
    }

    // Spieler in DB speichern
    if (addPlayer($con, $name, $lastname, $livePZ, $team, $position, $active)) {
        header("Location: player.php?addPlayer=success");
        exit();
    } else {
        header("Location: addPlayer.php?error=addPlayerFailed");
        exit();
    }
}

// 2. HTML-Ausgabe (Erst HIER werden Header & Layout eingebunden)
include('./components/header.php');

if (isset($_SESSION["admin"]) || isset($_SESSION["manager"])) :
?>

<div class="col add-player-section">
    <div class="card">
        <div class="card-header">
            <h4>Neuen Spieler anlegen 
                <a href="player.php" class="btn btn-danger float-end">Zurück</a>
            </h4>
        </div>
        
        <div class="card-body">
            <?php if (isset($_GET['error']) && $_GET['error'] === 'emptyfields'): ?>
                <div class="alert alert-danger">Bitte fülle alle Pflichtfelder (Vorname und Nachname) aus.</div>
            <?php elseif (isset($_GET['error']) && $_GET['error'] === 'addPlayerFailed'): ?>
                <div class="alert alert-danger">Fehler beim Speichern des Spielers.</div>
            <?php endif; ?>

            <form action="<?= htmlspecialchars(basename($_SERVER['PHP_SELF'])); ?>" method="post">
                
                <div class="col-12 mb-3">
                    <div class="row">
                        <div class="col-4">
                            <label for="name" class="form-label">Vorname</label>
                            <input class="form-control" type="text" id="name" name="name" placeholder="Vorname" required>
                        </div>

                        <div class="col-4">
                            <label for="lastname" class="form-label">Nachname</label>
                            <input class="form-control" type="text" id="lastname" name="lastname" placeholder="Nachname" required>
                        </div>
                        
                        <div class="col-4">
                            <label for="livePZ" class="form-label">LivePZ</label>
                            <input class="form-control" type="number" id="livePZ" name="livePZ" placeholder="TT live Punkte" value="0">
                        </div>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <div class="row">
                        <div class="col-4">
                            <label for="team" class="form-label">Team</label>
                            <input class="form-control" type="number" id="team" name="team" placeholder="Mannschaft" value="0">
                        </div>
                        <div class="col-4">
                            <label for="position" class="form-label">Position</label>
                            <input class="form-control" type="number" id="position" name="position" placeholder="Position" value="0">
                        </div>
                    </div>
                </div>
                    
                <div class="col-12 mb-3">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="active" name="active" checked>
                                <label class="form-check-label" for="active">Spieler ist aktiv</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 edit-actions">
                    <button class="btn btn-primary" type="submit" name="submit">Spieler anlegen</button>
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