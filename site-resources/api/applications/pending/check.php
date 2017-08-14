<?php

error_reporting(E_ALL);

require "../../connectdb.php";
require "../../auth/get_permissions.php";

$uid = $_POST["uid"];

if (isset($uid)) {
    if ($uid == -1 && $role == 0) {
        $query = "SELECT r.status, r.pid, p.start, p.end
                    FROM registration AS r
                    JOIN programs AS p ON r.pid = p.pid
                    WHERE r.status = 0 AND p.end > CURDATE()";
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->execute();
            $stmt->bind_result($gen);
            if ($stmt->fetch()) {
                echo trim("success");
            } else {
                http_response_code(400);
                echo "fail";
            }
            $stmt->close();
            $mysqli->close();
        } else {
            http_response_code(400);
            echo $mysqli->error;
        }
    } elseif ($uid > 0 && $role == 1) {
        $query = "SELECT status
                    FROM registration
                    WHERE status = 0 AND uid = ?";
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param("s", $uid);
            $stmt->execute();
            $stmt->bind_result($gen);
            if ($stmt->fetch()) {
                http_response_code(400);
                echo trim("success");
            } else {
                http_response_code(400);
                echo "fail";
            }
            $stmt->close();
            $mysqli->close();
        } else {
            http_response_code(500);
            echo $mysqli->error;
        }
    }
} else {
    http_response_code(400);
    echo "No Variables";
}
