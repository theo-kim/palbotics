<?php

error_reporting(E_ALL);

require "../connectdb.php";
require "../auth/get_permissions.php";

$mid = $_POST["mid"];
$uid = $identity;

if (isset($mid) && isset($role)) {
    $query = "DELETE FROM messages
                WHERE mid = ? AND uid = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("ii", $mid, $uid);
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
