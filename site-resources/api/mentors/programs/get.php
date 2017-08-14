<?php

error_reporting(E_ALL);

require "../../connectdb.php";
require "../../auth/get_permissions.php";

$vid = $_GET["vid"];

if (isset($vid) && $role == 0) {
    $query = "SELECT pid FROM volunteer WHERE vid = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("i", $_GET["vid"]);
        $stmt->execute();
        $stmt->bind_result($pid);
        if ($stmt->fetch()) {
            echo $pid;
        } else {
            echo "fail";
        }
    } else {
        echo "fail";
    }
} else {
    echo "Missing Permissions";
}
