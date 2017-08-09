<?php

error_reporting(E_ALL);

include "count_program.php";
//include "connectdb.php";

$id = $_GET["id"];
$role = $_GET["role"];

if (isset($id) && isset($role)){
    $query = "";
    if ($role == "mentor") {
        $query = "SELECT pid FROM volunteer WHERE vid = ?";
    } else {
        $query = "SELECT pid FROM registration WHERE id = ?";
    }
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($pid);

        if ($stmt->fetch()) {
            echo $pid;
        } else {
            echo "fail";
        }
        $stmt->close();
        $mysqli->close();
    }
}

?>