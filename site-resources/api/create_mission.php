<?php

error_reporting(E_ALL);

include "connectdb.php";

if (isset($_POST["pid"]) && isset($_POST["objective"]) && isset($_POST["value"]) && isset($_POST["progression"])) {

    $value = $_POST["value"];
    $objective = $_POST["objective"];
    $pid = $_POST["pid"];
    $progression = $_POST["progression"];

    if ($stmt = $mysqli->prepare("INSERT INTO mission (value, objective, pid, progression) VALUES (?, ?, ?, ?)")) {
        $stmt->bind_param("isii", $value, $objective, $pid, $progression);
        $stmt->execute();
        $a = $mysqli->affected_rows;
        $id = $mysqli->insert_id;

        if ($a > 0) {
            echo $id;
        }
        else {
            echo "fail";
        }
        $stmt->close();
        $mysqli->close();
    }
} else {
    echo "fail";
}

?>