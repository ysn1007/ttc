<?php
require_once 'dbh.inc.php';
/**
 * Prüft ob admin vorhanden ist.
 */
function uidExists($con, $username) {
    /**
     * Prepared statement
    */
    $sql = "SELECT * FROM users WHERE uid = ?;";
    $stmt = mysqli_stmt_init($con);

    /**
     * prüft SQL verbindung
    */
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        header("location: login.php?error=stmtfailed");
    }

    /**
     * Prüft ob user vorhanden ist
     * und führt statement aus.
    */
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    /**
     * Gibt das SQL resultat zurück.
    */
    $resultData = mysqli_stmt_get_result($stmt);
    
    /** 
     * Gibt die abgefragten Daten aus.
    */
    if($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        $result = false;
    }
    mysqli_stmt_close($stmt);
}

/**
 * überprüft ob die Felder gefüllt sind
 */
function emptyInputLogin($username, $pwd) {

    if(empty($username) || empty($pwd) ) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

/**
 * prüft ob user die richtigen logindaten eingegeben hat 
 * und meldet den User an.
 */
function loginUser($con, $username, $pwd) {

    $uidExists = uidExists($con, $username );

    if($uidExists === false) {
       header("location: login.php?error=wronglogin");
       exit();
    }

    $pwdHashed = $uidExists["passwort"];
    $checkPwd = password_verify($pwd, $pwdHashed);
    
    if($checkPwd == 0) {
        header("location: login.php?error=wronglogin");
        exit();
    } else if($checkPwd == 1 ) {
        session_start();
        $_SESSION["userid"] = $uidExists["id"];
        $_SESSION["admin"] = $uidExists["admin"];
        $_SESSION["manager"] = $uidExists["manager"];
        $_SESSION["author"] = $uidExists["author"];
        $_SESSION["useruid"] = $uidExists["uid"];

        header("location: index.ad.php");
        exit(); 
    }
}