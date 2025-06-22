
<!DOCTYPE html>
    <html lang="de">
        <head>
            <meta charset="UTF-8">
            <meta name="TTC Ramsharde - seit 1955">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <!--includes-->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
            <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700&display=swap" rel="stylesheet">

            <!--styles-->
            <link rel="stylesheet" type="text/css" href="../styles/style.min.css">

            <title>TTC RAMSHARDE - ADMIN</title>
        </head>
        <body class="body-wrapper col" id="loggedin">
            <div class="rwl">
                <header id="header">
                    <nav class="navbar navbar-expand-lg bg-body-tertiary">
                        <div class="container-fluid">
                            
                            <a class="navbar-brand" href="#">TTC RAMSHARDE</a>
                            
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse justify-content-md-end" id="navbarNav">
                           
                                <ul class='nav'>
                                    <li class='nav-item'>Welcher admin ist online</li>
                                </ul> 
                                
                            <ul class="nav">
                                <li class="nav-item <?php ((basename($_SERVER['PHP_SELF']) == "index.ad.php") ? "active" : "") ?>">
                                    <a href="index.ad.php" class="nav-link">Start</a>
                                </li>
                                <li class="nav-item <?php ((basename($_SERVER['PHP_SELF']) == "gallery.php") ? "active" : "") ?> dropdown">
                                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="gallery.php" role="button" aria-expanded="false">Galerie</a>
                                    <ul class="dropdown-menu ">
                                        <li><a class="dropdown-item" href="gallery.php?dekade=1950-1959">1950-1959</a></li>
                                        <li><a class="dropdown-item" href="gallery.php?dekade=1960-1969">1960-1969</a></li>
                                        <li><a class="dropdown-item" href="gallery.php?dekade=1970-1979">1970-1979</a></li>
                                        <li><a class="dropdown-item" href="gallery.php?dekade=1980-1989">1980-1989</a></li>
                                        <li><a class="dropdown-item" href="gallery.php?dekade=1990-1999">1990-1999</a></li>
                                        <li><a class="dropdown-item" href="gallery.php?dekade=2000-2009">2000-2009</a></li>
                                        <li><a class="dropdown-item" href="gallery.php?dekade=2010-2019">2010-2019</a></li>
                                        <li><a class="dropdown-item" href="gallery.php?dekade=2020-2029">2020-2029</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item <?php ((basename($_SERVER['PHP_SELF']) == "player.php") ? "active" : "") ?>">
                                    <a href="player.php" class="nav-link">Spieler</a>
                                </li>
                                <li class="nav-item <?php ((basename($_SERVER['PHP_SELF']) == "article.php") ? "active" : "") ?>">
                                    <a href="article.php" class="nav-link">Artikel</a>
                                </li>
                                <li class="nav-item">
                                    <?php echo "Hallo " .  $_SESSION["useruid"] ?>!
                                </li>
                                
                                <li class="nav-item">
                                    <a class="nav-link" href="./logout.php">logout</a>
                                </li>
                            </ul>
                            </div>
                        </div>
                    </nav>
                </header>
                <div id="content-wrapper" class="container-fluid">
                    <div class="row">
