<?php

error_reporting(E_ALL);

require "../connectdb.php";
require "../auth/get_permissions.php";

$gid = $_GET["gid"];
if (isset($gid) && $role == 2) {
    if ($stmt = $mysqli->prepare("SELECT r.first, r.last, g.uid, a.name, a.explaination FROM registration r JOIN group_members g ON r.id = g.uid LEFT OUTER JOIN awards AS a ON a.uid = g.uid WHERE g.role = 'participant' AND g.gid = ?")) {
        if ($stmt->bind_param("i", $gid)) {
            $stmt->execute();
            $stmt->bind_result($first, $last, $id, $name, $explaination);

            $arr = array();
            $finArr = array();

            while ($stmt->fetch()) {
                $arr["first"] = $first;
                $arr["last"] = $last;
                $arr["id"] = $id;
                $arr["name"] = $name;
                $arr["explaination"] = $explaination;
                $finArr[] = $arr;
            }

            $stmt->close();
        } else {
            echo $mysqli->error;
        }
        $mysqli->close();
    } else {
        echo $mysqli->error;
        $mysqli->close();
    }
    echo json_encode($finArr);
} else {
    echo "No Variables";
}
