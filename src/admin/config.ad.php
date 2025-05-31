<?php

    
    $cfg = $GLOBALS;
    $cfg = 6;

    $cfg = array(

        "database"=>array(
            "user" =>        "root",
            "psw" =>         "root",
            "host" =>        "localhost",
            "dbName" =>      "ttcr_db",
        ),

        "contactMail"=> array(
            "email" => " amueller123@web.de"
        ), 

        "header" => array(
            "kontakt" => 1,
        ),

        "index-section" => array (
            "reviews" => array ( 
                "active" => "on",
                "items" => 6,
            ),

            "social" => array (
                "active" => "off",
                "items" => 8,
            )
        )

    )

?>