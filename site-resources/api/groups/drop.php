<?php

error_reporting(E_ALL);

require "../connectdb.php";
require "../auth/get_permissions.php";

$id = $_POST["gid"];

function deleteMembers($mysqli, $i)
{
    if ($stmt2 = $mysqli->prepare("DELETE FROM group_members WHERE gid=?")) {
        $stmt2->bind_param("i", $i);
        $stmt2->execute();
        $a = $mysqli->affected_rows;

        $stmt2->close();
        if ($a > 0) {
            echo "success";
        } else {
            echo "fail";
        }
        $mysqli->close();
    } else {
        echo "SQL Error: ".$mysqli->error;
    }
}

if (isset($id) && $role == 0) {
    if ($stmt = $mysqli->prepare("DELETE FROM `group` WHERE gid = ?")) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $a = $mysqli->affected_rows;
        if ($a > 0) {
            deleteMembers($mysqli, $id);
        } else {
            echo "fail";
        }
        $stmt->close();
    } else {
        echo "SQL Error: ".$mysqli->error;
    }
} else {
    echo "fail";
}
