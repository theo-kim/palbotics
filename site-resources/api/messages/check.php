<?php

error_reporting(E_ALL);

require "../connectdb.php";
require "../auth/get_permissions.php";

$uid = $identity;

if (isset($uid) && isset($role)) {
    if ($stmt = $mysqli->prepare("SELECT COUNT(*) FROM messages WHERE uid = ? AND status = 0")) {
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $stmt->bind_result($number);
        if ($stmt->fetch()) {
            echo trim($number);
        } else {
            echo "fail";
        }
        $stmt->close();
        $mysqli->close();
    }
} else {
    echo "fail";
}
