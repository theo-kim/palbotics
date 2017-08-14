<?php

error_reporting(E_ALL);

require "../connectdb.php";
require "../auth/get_permissions.php";

$uid = $identity;
$pid = $_GET['pid'];

if (isset($pid) && isset($uid) && isset($role)) {
    $query = "SELECT g.gid, r.first, r.last
                FROM group_members g
                JOIN registration r ON r.id = g.uid
                WHERE r.uid = ? AND r.pid = ? AND g.role = 'participant'";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("ii", $uid, $pid);
        $stmt->execute();
        $stmt->bind_result($gid, $first, $last);
        $groupArray = array();
        $tempArray = array();
        while ($stmt->fetch()) {
            $tempArray["gid"] = $gid;
            $tempArray["name"] = $first." ".$last;
            $groupArray[] = $tempArray;
        }
        $stmt->close();
        if (count($groupArray) > 0) {
            $arr = array();
            $finArr = array();
            foreach ($groupArray as $key => $g) {
                if ($stmt = $mysqli->prepare("SELECT w.moid, m.objective FROM group_mission w JOIN mission m ON m.moid = w.moid WHERE w.gid = ?")) {
                    $stmt->bind_param("i", $g["gid"]);
                    $stmt->execute();
                    $stmt->bind_result($moid, $objective);
                    while ($stmt->fetch()) {
                        $arr["moid"] = $moid;
                        $arr["objective"] = $objective;
                        $finArr[] = $arr;
                    }
                    if (count($finArr) > 0) {
                        $groupArray[$key]["mission"] = $finArr;
                    }
                } else {
                    echo $mysqli->error;
                }
            }
            echo json_encode($groupArray);
        } else {
            echo "No Groups Found";
        }
    } else {
        echo $mysqli->error;
    }
} else {
    echo "No variables";
}
