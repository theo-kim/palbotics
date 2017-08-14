<?php

require_once "../connectdb.php";
require "cookies.php";
require "jwt.php";
use \Firebase\JWT\JWT;

function create_auth_cookie($uid, $role)
{
    switch ($role) {
        case "Lead Mentor":
            $role = 0;
            break;
        case "parent":
            $role = 1;
            break;
        case "mentor":
            $role = 2;
            break;
        default:
            $role = 3;
            break;
    }

    $secret = "kutydvcilsjblicyhuacjba75i8vew6c";
    $token = array(
        "uid" => $uid,
        "role" => $role
    );

    $jwt = JWT::encode($token, $secret);

    createSecureCookie("usertoken", $jwt, time() + (86400 * 7));
}
