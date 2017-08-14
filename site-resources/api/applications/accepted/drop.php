<?php

error_reporting(E_ALL);

require "../../connectdb.php";
require "../../auth/get_permissions.php";

$id = $_POST["id"];

if (isset($id) && $role == 0) {
    if ($stmt = $mysqli->prepare("UPDATE registration SET status = 0 WHERE id = ?")) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $a = $mysqli->affected_rows;

        if ($a > 0) {
            http_response_code(200);
            echo "success";
        } else {
            http_response_code(400);
        }
        $stmt->close();
        $mysqli->close();
    }
} else {
    http_response_code(400);
}
