<?php

require "../connectdb.php";
require "../auth/get_permissions.php";

$pid = $_POST["pid"];

if (isset($pid) && $role == 0) {
    $query = "SELECT COUNT(*)
                FROM `registration`
                WHERE `pid` = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("i", $pid);
        $stmt->execute();
        $stmt->bind_result($count);
        if ($stmt->fetch()) {
            if ($count > 0) {
                echo "Dependencies";
                $stmt->close();
            } else {
                $stmt->close();
                $query = "DELETE FROM programs WHERE pid = ?";
                if ($stmt = $mysqli->prepare($query)) {
                    $stmt->bind_param("i", $pid);
                    $stmt->execute();
                    $a = $mysqli->affected_rows;
                    if ($a > 0) {
                        echo "success";
                    } else {
                        echo "fail";
                    }
                    $stmt->close();
                }
            }
        } else {
            echo "failure";
            $stmt->close();
        }
    } else {
        echo $mysqli->error;
    }
} else {
    echo "No Variables";
}

$mysqli->close();
