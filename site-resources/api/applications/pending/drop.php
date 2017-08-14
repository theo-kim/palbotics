<?php

error_reporting(E_ALL);

require "../../connectdb.php";
require "../../auth/get_permissions.php";

$id = $_POST["id"];

if (isset($id) && $role == 0) {
    if ($stmt = $mysqli->prepare("UPDATE registration SET status = -1 WHERE id = ?")) {
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
    echo "No Variables";
}
