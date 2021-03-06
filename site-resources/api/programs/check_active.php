<?php

error_reporting(E_ALL);

require "../connectdb.php";
require "../auth/get_permissions.php";

$uid = $_POST["uid"];
$date = $_POST["date"];

if (isset($uid) && isset($date) && isset($role)) {
    $flag = false;

    if ($date == 1) {
        $query = "SELECT p.start, p.end, v.pid FROM programs p JOIN volunteer v ON v.pid = p.pid WHERE v.uid = ? AND p.start < NOW() AND p.end > NOW()";
    } else {
        $query = "SELECT p.start, p.end, v.pid FROM programs p JOIN volunteer v ON v.pid = p.pid WHERE v.uid = ? AND p.start > NOW() AND p.end > NOW()";
    }
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $stmt->bind_result($start, $end, $pid);

        $arr = array();
        $finArr = array();

        while ($stmt->fetch()) {
            $arr[] = $pid;
        }

        echo json_encode($arr);
    } else {
        echo $mysqli->error;
    }
} else {
    echo "No variable";
}
