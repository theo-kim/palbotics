<?php

error_reporting(E_ALL);

require "../connectdb.php";
require "../auth/get_permissions.php";

if ($stmt = $mysqli->prepare("UPDATE programs SET registered = (SELECT COUNT(registration.pid) FROM registration WHERE registration.pid = programs.pid AND registration.status > 0)")) {
    $stmt->execute();
    $a = $mysqli->affected_rows;

    if ($a > 0) {
        //echo "success";
    } else {
        //echo "fail";
    }
    $stmt->close();
    //$mysqli->close();
} else {
    echo $mysqli->error;
}
