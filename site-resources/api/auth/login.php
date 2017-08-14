<?php
error_reporting(E_ALL);

require "../connectdb.php";
require "create_auth.php";

$username = $_POST["username"];

if (isset($username)) {
    $query = "SELECT user_id, password_hash, role
                FROM users
                WHERE username = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($uid, $password, $role);
        if ($stmt->fetch()) {
            if (md5($_POST["password"]) == $password) {
                create_auth_cookie($uid, $role);
                echo "success";
            } else {
                sleep(1);
                echo "fail";
            }
        } else {
            echo "fail";
        }
        $stmt->close();
        $mysqli->close();
    }
} else {
    echo "fail";
}
