
<?php include('./includes/headContent.php');?>    

<body class="body-wrapper col col-xs-12 col-sm-12 " id="<?= pathinfo($_SERVER['PHP_SELF'])['filename'] ?>">
    <div class="row">
        <header id="header">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <div class="header-brand">
                        <a class="navbar-brand" href="index.php">
                            <img src="img/logo/logo.svg"  width="60" alt=""> TTC RAMSHARDE
                        </a>
                    </div>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item navi <?= ((basename($_SERVER['PHP_SELF']) == "index.php") ? "active" : "") ?>" id="Startseite"><a class="nav-link" href="index.php">Startseite<span class="sr-only"></span></a></li>
                        <li class="nav-item navi dropdown">
                            <a href="#" class="nav-link <?= ((basename($_SERVER['PHP_SELF']) == "mannschaften.php") ? "active" : "") ?> dropdown-toggle" id="navbarDropdownMenuLink" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Mannschaften <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li class="<?= ((basename($_SERVER['PHP_SELF']) == "mannschaften.php?id=herren1") ? "active" : "") ?>"><a class="nav-link" href="mannschaften.php?id=1herren">1. Herren</a></li>
                                <li class="<?= ((basename($_SERVER['PHP_SELF']) == "mannschaften.php?id=herren2") ? "active" : "") ?>"><a class="nav-link" href="mannschaften.php?id=2herren">2. Herren</a></li>
                                <li class="<?= ((basename($_SERVER['PHP_SELF']) == "mannschaften.php?id=herren3") ? "active" : "") ?>"><a class="nav-link" href="mannschaften.php?id=3herren">3. Herren</a></li>
                                <li class="<?= ((basename($_SERVER['PHP_SELF']) == "mannschaften.php?id=herren4") ? "active" : "") ?>"><a class="nav-link" href="mannschaften.php?id=4herren">4. Herren</a></li>
                                <li class="<?= ((basename($_SERVER['PHP_SELF']) == "mannschaften.php?id=herren5") ? "active" : "") ?>"><a class="nav-link" href="mannschaften.php?id=5herren">5. Herren</a></li>
                            </ul>
                        </li>
                        <li class="nav-item navi <?= ((basename($_SERVER['PHP_SELF']) == "historie.php") ? "active" : "") ?>" id="historie"><a class="nav-link" href="historie.php">Historie</a></li>
                        <li class="nav-item navi <?= ((basename($_SERVER['PHP_SELF']) == "galerie.php") ? "active" : "") ?>" id="galerie"><a class="nav-link" href="galerie.php">Galerie</a></li>
                        
                        
                        <li class="nav-item <?= ((basename($_SERVER['PHP_SELF']) == "sponsoren.php") ? "active" : "") ?>" id="sponsoren"><a class="nav-link" href="sponsoren.php">Sponsoren</a></li>
                        <li class="nav-item <?= ((basename($_SERVER['PHP_SELF']) == "kontakt.php") ? "active" : "") ?>" id="kontakt"><a class="nav-link" href="kontakt.php">kontakt</a></li>
                    </ul>
                    </div>
                </div>
            </nav>
            
        </header>
        <?PHP ((basename($_SERVER['PHP_SELF']) == "index.php") ? require_once('hero.img.slider.php') : "") ?>
        <div id="content-wrapper" class="cw">
            <div class="row">
                

            