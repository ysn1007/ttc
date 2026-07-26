<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="UTF-8">
        <meta name="TTC Ramsharde - seit 1955">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <!--includes-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700&display=swap" rel="stylesheet">
        
        <!--styles-->
        <link rel="stylesheet" type="text/css" href="../styles/style.min.css">
        
        <title>TTC RAMSHARDE</title>
    </head>
    <body class="body-wrapper col-xs-12 col-sm-12" id="<?php echo pathinfo($_SERVER['PHP_SELF'])['filename']; ?>">
        <div class="row">
            <header id="header">
                <nav class="navbar navbar-default">

                    <!--login navi-->
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
                                    <img src="../../dest/img/logo.svg"  width="60" alt=""> TTC RAMSHARDE
                                </a>
                            </div>
                        </div>

                        
                    </div>  
                    <!-- end login navi -->
                </nav>
            </header>
            <div id="content-wrapper" class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
                <div class="row">
                    <div class="hero col-xs-12 col-md-12 col-lg-12 ">
                        
                        <section class="login-form">
                            <form action="includes/login.inc.php" method="post">
                                <h4 style="margin-bottom: 30px;">Anmelden</h4>
                                <div class="login-data-panel">
                                    <input type="text" name="name" placeholder="Dein Name">
                                    <input type="password" name="pwd" placeholder="Passwort">
                                </div>
                                <button type="submit" name="submit">Login</button>
                            </form>

                            <?php 
                                if(isset($_GET["error"])) {
                                    if($_GET["error"] == "emptyinput") {
                                        echo "<p>Gib deine Logindaten ein.</p>";
                                    } else if($_GET["error"] == "wronglogin") {
                                        echo "<p>Falsche login eingabe</p>";
                                    }
                                }
                            ?>
                        </section>
                    </div>
                </div>
            </div>
        </row>
        <footer class="col-xs-12 col-md-12 footer">
            <div class="col-xs-12 col-md-12 footer-links">
                <div class="row">
                    <div class="pull-right copyright">
                        <span>TTC RAMSHARDE © 2021</span>
                    </div>
                </div>
            </div>
        </footer>
    </body>

    <!--   third party sources    -->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

    <!--   js sources    -->
    <!--script src="../dest/js/global.min.js"></script-->

 </html>

