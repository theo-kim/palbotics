<?php

error_reporting(E_ALL);

require "../connectdb.php";
require "../auth/get_permissions.php";

$uid = $identity;

if (isset($uid)) {
    if ($uid > 0) {
        if ($stmt = $mysqli->prepare("SELECT p.* FROM programs p JOIN registration r ON r.pid = p.pid WHERE r.uid = ? AND p.start <= NOW()")) {
            $stmt->bind_param("i", $uid);
            $stmt->execute();
            $stmt->bind_result($pid, $name, $start, $end, $age, $size, $registered, $location);
            $count = 0;
            $indi_count = 0;
            $arr = array();
            $finArr = array();
            while ($stmt->fetch()) {
                if (isset($arr["id"]) && $arr["id"] == $id) {
                    $finArr[$indi_count-1]["count"]++;
                } else {
                    $arr["id"] = $pid;
                    $arr["name"] = $name;
                    $arr["start"] = date("F j, Y", strtotime($start));
                    $arr["end"] = date("F j, Y", strtotime($end));
                    $arr["age"] = $age;
                    $arr["count"] = 1;
                    $indi_count++;

                    $finArr[$indi_count] = $arr;
                }

                $count++;
            }
            if ($count < 1) {
                echo "No completed programs found";
            } else {
                $finArr["length"] = count($finArr);
                echo json_encode($finArr);
            }
        } else {
            echo "Database Error: ".$mysqli->error;
        }
    } else {
        echo "New";
    }
} else {
    echo "No variables";
}
