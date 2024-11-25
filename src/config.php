<?php
define('__ROOT__', dirname(dirname(__FILE__)));
//require_once(__ROOT__.'\admin\config.ad.php');
    //$dir = __DIR__;
    session_start();
    $cfg = $GLOBALS;
    //$cfg = 6;
    //global $cfg;

    $cfg = array(
        "reviews" => array ( 
            "active" => "on",
            "items" => 6,
        ),

        "social" => array (
            "active" => "off",
            "items" => 8,
        )

    )

?>