<?php

error_reporting(E_ALL);

require "../../connectdb.php";
require "../../auth/get_permissions.php";

$value = $_POST["value"];
$objective = $_POST["objective"];
$pid = $_POST["pid"];
$progression = $_POST["progression"];

if (isset($pid) && isset($objective) && isset($value) && isset($progression) && $role == 0) {
    $query = "INSERT INTO mission (value, objective, pid, progression)
                VALUES (?, ?, ?, ?)";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("isii", $value, $objective, $pid, $progression);
        $stmt->execute();
        $a = $mysqli->affected_rows;
        $id = $mysqli->insert_id;

        if ($a > 0) {
            echo $id;
        } else {
            echo "fail";
        }
        $stmt->close();
        $mysqli->close();
    }
} else {
    echo "fail";
}
