<?php

error_reporting(E_ALL);

require "../connectdb.php";
require "../auth/get_permissions.php";

$uid = $identity;

if ($uid) {
    $query = "SELECT username, email, first_name, last_name, user_id, role, joined
                FROM users
                WHERE user_id = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $stmt->bind_result($username, $email, $first, $last, $id, $role, $joined);
        $arr = array();
        $finalArr = array();

        while ($stmt->fetch()) {
            $arr["role"] = $role;
            $arr["email"] = $email;
            $arr["username"] = $username;
            $arr["last"] = $last;
            $arr["first"] = $first;
            $arr["joined"] = $joined;
            $arr["id"] = $id;
        }

        $stmt->close();
        $mysqli->close();

        echo json_encode($arr);
    }
} else {
    echo "failure";
}
