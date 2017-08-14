<?php

error_reporting(E_ALL);

require "../../connectdb.php";
require "../../auth/get_permissions.php";

$gid = $_POST["gid"];
$id = $_POST["id"];

if (isset($id) && isset($gid) && $role == 0) {
    $query = "DELETE FROM `group_members` WHERE gid = ? AND uid = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("ii", $gid, $id);
        $stmt->execute();
        $a = $mysqli->affected_rows;
        if ($a > 0) {
            echo "success";
        } else {
            echo "fail";
        }
        $stmt->close();
    } else {
        echo "SQL Error: ".$mysqli->error;
    }
} else {
    echo "No Variables";
}
