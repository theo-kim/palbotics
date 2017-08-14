<?php

error_reporting(E_ALL);

require "../../connectdb.php";
require "../../auth/get_permissions.php";

$pid = $_GET["pid"];

if (isset($pid) && $role == 0) {
    if ($stmt = $mysqli->prepare("SELECT mission.* FROM mission  WHERE pid = ?")) {
        $stmt->bind_param("i", $pid);
        $stmt->execute();
        $stmt->bind_result($moid, $start, $minimum, $completed, $value, $objective, $age, $progression, $pid);

        $arr = array();
        $finArr = array();

        while ($stmt->fetch()) {
            $arr["moid"] = $moid;
            $arr["start"] = $start;
            $arr["min"] = $min;
            $arr["completed"] = $completed;
            $arr["value"] = $value;
            $arr["objective"] = $objective;
            $arr["age"] = $age;
            $arr["progression"] = $progression;
            $arr["pid"] = $pid;

            $finArr[] = $arr;
        }

        $stmt->close();
        $mysqli->close();
    }
} else {
    echo "No Variables";
}
echo json_encode($finArr);
