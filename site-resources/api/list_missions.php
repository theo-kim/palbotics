<?php

error_reporting(E_ALL);

include "connectdb.php";

if (isset($_GET["pid"])){
    if ($stmt = $mysqli->prepare("SELECT mission.* FROM mission  WHERE pid = ?")) {
        $stmt->bind_param("i", $_GET["pid"]);
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
}
echo json_encode($finArr);

?>