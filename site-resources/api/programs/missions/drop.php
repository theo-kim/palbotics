<?php

error_reporting(E_ALL);

require "../../connectdb.php";
require "../../auth/get_permissions.php";

$id = $_POST["id"];

if ($id && $role == 0) {
    if ($stmt = $mysqli->prepare("DELETE FROM mission WHERE moid = ?")) {
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
    echo "fail";
}
