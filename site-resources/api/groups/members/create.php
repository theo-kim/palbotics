<?php

error_reporting(E_ALL);

require "../../connectdb.php";
require "../../auth/get_permissions.php";

$pid = $_POST["pid"];
$uid = $_POST["uid"];
$section = $_POST["section"];
$role = $_POST["role"];

if (isset($pid) && isset($uid) && isset($section) && isset($role) && $role == 0) {
    if ($stmt = $mysqli->prepare("SELECT mgid FROM group_members WHERE uid = ? AND role = ?")) {
        $stmt->bind_param("is", $uid, $role);
        $stmt->execute();
        $stmt->bind_result($mgid);
        if ($stmt->fetch()) {
            $stmt->close();
            $query = "UPDATE group_members SET group_members.gid = (SELECT gid FROM `group` WHERE pid = ? AND section = ?) WHERE mgid = ?";
            if ($stmtt = $mysqli->prepare($query)) {
                $stmtt->bind_param("iii", $pid, $section, $mgid);
                $stmtt->execute();
                $af = $mysqli->affected_rows;
                if ($af > 0) {
                    echo "success";
                } else {
                    echo "fail";
                }
            } else {
                echo "MySQL Error: ". $mysqli->error;
            }
            $stmt->close();
            $mysqli->close();
        } else {
            if ($stmt = $mysqli->prepare("INSERT INTO group_members(gid, uid, role) VALUES((SELECT gid FROM `group` WHERE pid = ? AND section = ?), ?, ?)")) {
                $stmt->bind_param("iiis", $pid, $section, $uid, $role);
                $stmt->execute();
                $ai = $mysqli->affected_rows;
                if ($ai > 0) {
                    echo "success";
                } else {
                    echo "Failure to assign group";
                }
                $stmt->close();
                $mysqli->close();
            } else {
                echo "MySQLi Error 1: ".$mysqli->error;
                $mysqli->close();
            }
        }
    } else {
        echo "MySQLi Error 2: ".$mysqli->error;
        $mysqli->close();
    }
} else {
    echo "No variables";
}
