<div class="container-fluid">
    <!-- hidden mobile menu  -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <div class="header-brand">
            <a class="navbar-brand" href="index.php">
                <img src="../src/img/logo/logo.svg"  width="80" alt=""> TTC RAMSHARDE
            </a>
        </div>
    </div>

    <!-- main menu  -->
    
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav navbar-right">
            <li class="navi <?= ((basename($_SERVER['PHP_SELF']) == "index.php") ? "active" : "") ?>" id="Startseite"><a href="index.php">Startseite<span class="sr-only">(current)</span></a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Mannschaften <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li class="<?= ((basename($_SERVER['PHP_SELF']) == "herren1.php") ? "active" : "") ?>"><a href="herren1.php">1. Herren</a></li>
                    <li class="<?= ((basename($_SERVER['PHP_SELF']) == "herren2.php") ? "active" : "") ?>"><a href="herren2.php">2. Herren</a></li>
                    <li class="<?= ((basename($_SERVER['PHP_SELF']) == "herren3.php") ? "active" : "") ?>"><a href="herren3.php">3. Herren</a></li>
                    <li class="<?= ((basename($_SERVER['PHP_SELF']) == "herren4.php") ? "active" : "") ?>"><a href="herren4.php">4. Herren</a></li>
                    <li class="<?= ((basename($_SERVER['PHP_SELF']) == "herren5.php") ? "active" : "") ?>"><a href="herren5.php">5. Herren</a></li>
                    <li role="separator" class="divider"></li>
                    <li class="<?= ((basename($_SERVER['PHP_SELF']) == "jungen1.php") ? "active" : "") ?>"><a href="jungen1.php">1. Jungen</a></li>
                    <li class="<?= ((basename($_SERVER['PHP_SELF']) == "jungen2.php") ? "active" : "") ?>"><a href="jungen2.php">2. Jungen</a></li>
                    <li role="separator" class="divider"></li>
                    <li class="<?= ((basename($_SERVER['PHP_SELF']) == "schueler.php") ? "active" : "") ?>"><a href="schueler.php">Schüler</a></li>
                </ul>
            </li>
            <li class="navi <?= ((basename($_SERVER['PHP_SELF']) == "historie.php") ? "active" : "") ?>" id="historie"><a href="historie.php">Historie</a></li>
            <li class="navi <?= ((basename($_SERVER['PHP_SELF']) == "galerie.php") ? "active" : "") ?>" id="galerie"><a href="galerie.php">Galerie</a></li>
            
            <!--li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Tuniere<span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li class="<?= ((basename($_SERVER['PHP_SELF']) == "kreis_ms.php") ? "active" : "") ?>"><a href="kreis_ms.php">Kreismeisterschaften</a></li>
                    <li class="<?= ((basename($_SERVER['PHP_SELF']) == "bezirk_ms.php") ? "active" : "") ?>"><a href="bezirk_ms.php">Bezirksmeisternschaft</a></li>
                    <li class="<?= ((basename($_SERVER['PHP_SELF']) == "landes_ms.php") ? "active" : "") ?>"><a href="landes_ms.php">Landesmeisterschaft</a></li>
                    <li role="separator" class="divider"></li>
                    <li class="<?= ((basename($_SERVER['PHP_SELF']) == "ostercup.php") ? "active" : "") ?>"><a href="ostercup.php">Ostercup</a></li>
                </ul>
            </li-->
            <li class="navi <?= ((basename($_SERVER['PHP_SELF']) == "sponsoren.php") ? "active" : "") ?>" id="sponsoren"><a href="sponsoren.php">Sponsoren</a></li>
            <li class="navi <?= ((basename($_SERVER['PHP_SELF']) == "kontakt.php") ? "active" : "") ?>" id="kontakt"><a href="kontakt.php">kontakt</a></li>
        </ul>
        <!--hier kommt logout hin-->
    </div>
    
</div>
