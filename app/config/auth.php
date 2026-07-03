<?php

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . "/../../");
$dotenv->load();
return
    [
        "jwt_secret" => $_ENV['JWT_SECRET'] ?? '/9y+evL4elOakyppRGzBuIZyM3D1MGTB6OYeB3IJYnE=',
        "jwt_expiration" => 3600,
        "jwt_algorithm" => 'HS256'
    ];
