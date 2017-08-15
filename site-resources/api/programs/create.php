<?php

error_reporting(E_ALL);

require "../connectdb.php";
require "../auth/get_permissions.php";

$type = $_POST["type"];
$age = $_POST["group"];
$capacity = $_POST["capacity"];
$start =  date('Y-m-d H:i:s', strtotime($_POST["starting"]) + 86399);
$end = date('Y-m-d H:i:s', strtotime($_POST["ending"]) + 86399);
$location = $_POST["location"];

if (isset($type) && isset($capacity) && isset($start) && isset($end) && isset($age)) {
    if ($stmt = $mysqli->prepare("INSERT INTO programs (name, start, end, age, size, location) VALUES (?, ?, ?, ?, ?, ?)")) {
        $stmt->bind_param("ssssss", $type, $start, $end, $age, $capacity, $location);
        $stmt->execute();
        $a = $mysqli->affected_rows;

        if ($a > 0) {
            echo "success";
        } else {
            echo "fail";
        }
        $stmt->close();
        $mysqli->close();
    }
} else {
    echo "No Variables";
}
