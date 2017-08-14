<?php

error_reporting(E_ALL);

require "../../connectdb.php";
require "../../auth/get_permissions.php";

$capacity = $_POST["size"];
$program = $_POST["program"];

if (isset($capacity) && isset($program) && $role == 0) {
    if ($stmt = $mysqli->prepare("INSERT INTO programs (name, start, end, age, size) VALUES (?, ?, ?, ?, ?)")) {
        $stmt->bind_param("sssss", $type, $start, $end, $age, $capacity);
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
