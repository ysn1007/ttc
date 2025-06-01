
<!DOCTYPE html>
    <html lang="de">
        <head>
            <meta charset="UTF-8">
            <meta name="TTC Ramsharde - seit 1955">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <!--includes-->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
            <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700&display=swap" rel="stylesheet">

            <!--styles-->
            <link rel="stylesheet" type="text/css" href="../styles/style.min.css">

            <title>TTC RAMSHARDE</title>
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
                           
                                <ul class='navbar-nav'>
                                    <li class='nav-item'>Welcher admin ist online</li>
                                </ul> 
                                
                            <ul class="navbar-nav">
                                <li class="nav-item"><a href="index.ad.php">Start</a></li>
                                <li class="nav-item"><a href="galerie.php">Galerie</a></li>
                                <li class="nav-item"><a href="player.php">Spieler</a></li>
                                <li class="nav-item"><a href="artikel.php">Artikel</a></li>
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
                <div id="content-wrapper" class="col-xs-12 col-sm-12 col-md-10 offset-md-1">
                    <div class="row">
