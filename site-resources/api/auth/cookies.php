<?php

function createSecureCookie($name, $content, $expiration)
{
    setcookie($name, $content, $expiration, "/site-resources/", $_SERVER["HTTP_HOST"], true, true);
}
function destroyCookie($name)
{
    if (isset($_COOKIE[$name])) {
        //unset($_COOKIE[$name]);
        setcookie($name, "", time() - 1000, "/site-resources/", $_SERVER["HTTP_HOST"], true, true);
        return true;
    } else {
        return false;
    }
}
