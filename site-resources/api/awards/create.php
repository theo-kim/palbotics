<?php

error_reporting(E_ALL);

require "../connectdb.php";
require "../auth/get_permissions.php";

$uid = $_POST["uid"];
$name = $_POST["name"];
$explain = $_POST["explain"];

if (isset($uid) && isset($explain) && isset($name) && $role == 2) {
    if (isset($_POST["special"])) {
        $special = $_POST["special"];
    } else {
        $special = "N";
    }

    if ($stmt = $mysqli->prepare("INSERT INTO awards(uid, name, explaination, palbotics_essay) VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE name = ?, explaination = ?, palbotics_essay = ?")) {
        $stmt->bind_param("issssss", $uid, $name, $explain, $special, $name, $explain, $special);
        $stmt->execute();
        $a = $mysqli->affected_rows;

        if ($a > 0) {
            echo "success";
        } else {
            echo "fail";
        }
        $stmt->close();
        $mysqli->close();
    } else {
        echo $mysqli->error;
    }
} else {
    echo "No variables";
}
