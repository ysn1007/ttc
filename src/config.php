<?php
if (!defined('__ROOT__')) {
    define('__ROOT__', dirname(dirname(__FILE__)));
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cfg = [
    "header" => [
        "kontakt" => 1,
    ],
    "index-section" => [
        "reviews" => [
            "active"    => true,
            "limit"     => 6,
        ],
        
    ],
    "social-media" => [
        "active"    => true,
        "channels" => [
            "facebook"  => true,
            "instagram" => true,
            "youtube"   => true,
            "tiktok"    => true,
            
        ],
        "index-section" => [
          "active" => true,
          "limit" => 8,  
        ],
        
    ]
];

return $cfg;

?>