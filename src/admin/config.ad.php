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