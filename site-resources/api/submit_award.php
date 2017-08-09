<?php

error_reporting(E_ALL);

include "connectdb.php";

if (isset($_POST["name"]) && isset($_POST["explain"]) && isset($_POST["uid"])){

    $uid = $_POST["uid"];
    $name = $_POST["name"];
    if (isset($special)) {
        $special = $_POST["special"];
    } else {
        $special = "N";
    }
    $explain = $_POST["explain"];

    if ($stmt = $mysqli->prepare("INSERT INTO awards(uid, name, explaination, palbotics_essay) VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE name = ?, explaination = ?, palbotics_essay = ?")) {
        $stmt->bind_param("issssss", $uid, $name, $explain, $special, $name, $explain, $special);
        $stmt->execute();
        $a = $mysqli->affected_rows;

        if ($a > 0) {
            echo "success";
        }
        else {
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

?>