
<?php include('./includes/headContent.php');?>    

<body class="body-wrapper col-xs-12 col-sm-12 " id="<?= pathinfo($_SERVER['PHP_SELF'])['filename'] ?>">
    <div class="row">
        <header id="header">
            <nav class="navbar navbar-default">
                <?#php include('./includes/navi.php')?>
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
                                <img src="img/logo/logo.svg"  width="60" alt=""> TTC RAMSHARDE
                            </a>
                        </div>
                    </div>

                    <!-- main menu  -->
                    
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav navbar-right">
                            <li class="navi <?= ((basename($_SERVER['PHP_SELF']) == "index.php") ? "active" : "") ?>" id="Startseite"><a href="index.php">Startseite<span class="sr-only">(current)</span></a></li>
                            <li class="navi dropdown">
                                <a href="#" class="navi-item <?= ((basename($_SERVER['PHP_SELF']) == "mannschaften.php") ? "active" : "") ?> dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Mannschaften <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li class="<?= ((basename($_SERVER['PHP_SELF']) == "mannschaften.php?id=herren1") ? "active" : "") ?>"><a href="mannschaften.php?id=1herren">1. Herren</a></li>
                                    <li class="<?= ((basename($_SERVER['PHP_SELF']) == "mannschaften.php?id=herren2") ? "active" : "") ?>"><a href="mannschaften.php?id=2herren">2. Herren</a></li>
                                    <li class="<?= ((basename($_SERVER['PHP_SELF']) == "mannschaften.php?id=herren3") ? "active" : "") ?>"><a href="mannschaften.php?id=3herren">3. Herren</a></li>
                                    <li class="<?= ((basename($_SERVER['PHP_SELF']) == "mannschaften.php?id=herren4") ? "active" : "") ?>"><a href="mannschaften.php?id=4herren">4. Herren</a></li>
                                    <li class="<?= ((basename($_SERVER['PHP_SELF']) == "mannschaften.php?id=herren5") ? "active" : "") ?>"><a href="mannschaften.php?id=5herren">5. Herren</a></li>
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
            </nav>
        </header>
        <?PHP ((basename($_SERVER['PHP_SELF']) == "index.php") ? require_once('hero.img.slider.php') : "") ?>
        <div id="content-wrapper" class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
            <div class="row">
                

            