<?php
error_reporting(E_ALL);

require "../connectdb.php";
require "../auth/get_permissions.php";

$username = $_GET["username"];

if (isset($username)) {
    if ($stmt = $mysqli->prepare("SELECT username FROM users WHERE username = ?")) {
        if ($stmt->bind_param("s", $username)) {
            if ($stmt->execute()) {
                $stmt->bind_result($username);
                if ($stmt->fetch()) {
                    echo "success";
                } else {
                    sleep(1);
                    echo "fail";
                }
            } else {
                echo "fail";
            }
        } else {
            echo "bad bind";
            printf("Errormessage: %s\n", $mysqli->error);
        }
        $stmt->close();
    } else {
        echo $mysqli->error;
    }
} else {
    echo "failure";
}
