<?php

error_reporting(E_ALL);

require "../../connectdb.php";
require "../../auth/get_permissions.php";

$gid = $_GET["gid"];

if (isset($gid) && $role == 1) {
    $gid = $_GET["gid"];
    $query = "SELECT moid, start, minimum, completed, value, objective, progression FROM mission WHERE progression = (SELECT IFNULL((SELECT MAX(progression) + 1 FROM group_mission WHERE gid = ?), 1)) AND pid = (SELECT pid FROM `group` WHERE gid = ?)";
    //echo $gid;
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("ii", $gid, $gid);
        $stmt->execute();
        $stmt->bind_result($moid, $start, $minimum, $completed, $value, $objective, $progression);

        $arr = array();
        $finArr = array();
        //echo "mark2";
        while ($stmt->fetch()) {
            //echo "mark1";
            $arr['gid'] = $gid;
            $arr["moid"] = $moid;
            $arr["value"] = $value;
            $arr["start"] = $start;
            $arr["min"] = $minimum;
            $arr["completed"] = $completed;
            $arr["objective"] = $objective;
            $arr["progression"] = $progression;
            $finArr[] = $arr;
        }

        $stmt->close();
        echo json_encode($finArr);

        //echo "mark3";
    } else {
        echo "Database Error: ".$mysqi->error;
    }
} else {
    echo "No Variables";
}
