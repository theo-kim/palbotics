<?php

error_reporting(E_ALL);

require "../../connectdb.php";
require "../../auth/get_permissions.php";

$uid = $identity;

if (isset($uid) && isset($role)) {
    if ($role > 1) {
        $query = "SELECT `user_id`, `first_name`, `last_name`, `role`
                    FROM `users`
                    WHERE `role` = 'Lead Mentor'";
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->execute();
            $stmt->bind_result($user_id, $first, $last, $role);
            $arr = array();
            $finArr = array();
            while ($stmt->fetch()) {
                $arr["uid"] = $user_id;
                $arr["first"] = $first;
                $arr["last"] = $last;
                $arr["role"] = $role;
                $finArr[] = $arr;
            }
            echo json_encode($finArr);
        } else {
            echo "MySQL Error: ". $mysqli->error;
        }
    }
} else {
    echo "failure";
}