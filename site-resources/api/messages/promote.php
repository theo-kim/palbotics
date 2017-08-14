<?php

error_reporting(E_ALL);

require "../connectdb.php";
require "../auth/get_permissions.php";

$id = $_POST["mid"];

if (isset($id) && isset($role)) {
    if ($stmt = $mysqli->prepare("UPDATE messages SET status = 1 WHERE mid = ?")) {
        $stmt->bind_param("i", $id);
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
    echo "failure";
}
