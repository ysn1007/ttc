<?php

if($_POST["name"] != "" && $_POST["pwd"] != "" ) {
    $_POST["submit"] = 1;
} else {
    header("location: ../login.php?error=invalidFields");
    echo "Gebe bitte valide Daten ein";
    exit();
}

if($_POST["submit"] == 1) {
    $username = $_POST["name"];
    $pwd = $_POST["pwd"];
    
    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    if(emptyInputLogin($username, $pwd) === true ) {
        header("location: ../login.php?error=emptyinput");
        exit();
    }

    loginUser($con, $username, $pwd);
    
} else {
    header("location: ../login.php");
    exit();
}