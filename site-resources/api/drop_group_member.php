<?php

error_reporting(E_ALL);

include "connectdb.php";

$gid = $_POST["gid"];
$id = $_POST["id"];

if (isset($id) && isset($gid)) {
    $query = "DELETE FROM `group_members` WHERE gid = ? AND uid = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("ii", $gid, $id);
        $stmt->execute();
        $a = $mysqli->affected_rows;
        if ($a > 0) {
            echo "success";
        }
        else {
            echo "fail";
        }
        $stmt->close();

    } else {
        echo "SQL Error: ".$mysqli->error;
    }
} else {
    echo "fail";
}

?>