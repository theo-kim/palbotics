<?php

require_once("jwt.php");

use \Firebase\JWT\JWT;

if (!isset($_COOKIE["usertoken"])) {
    http_response_code(400);
    echo "No user token\n";
    $identity = null;
    $role = null;
} else {
    $secret = "kutydvcilsjblicyhuacjba75i8vew6c";
    $decoded = JWT::decode($_COOKIE["usertoken"], $secret, array('HS256'));
    $role = $decoded->role;
    $identity = $decoded->uid;
    var_dump($decoded);
}
