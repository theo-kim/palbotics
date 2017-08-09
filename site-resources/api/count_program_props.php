<?php

error_reporting(E_ALL);

include "connectdb.php";

$pid = $_GET["pid"];
if (isset($pid)) {
    if ($stmt = $mysqli->prepare("SELECT (SELECT COUNT(*) FROM `mission` WHERE pid = ?), (SELECT COUNT(*) FROM `group` WHERE pid = ?), (SELECT COUNT(*) FROM `registration` WHERE pid = ?)")) {
        $stmt->bind_param("iii", $pid, $pid, $pid);

        $stmt->execute();

        if ($stmt->bind_result($mission, $group, $registered)){
            $arr = array();

            while ($stmt->fetch()) {
                $arr["mission"] = $mission;
                $arr["registered"] = $registered;
                $arr["group"] = $group;
            }

            echo json_encode($arr);
        } else {
            echo "SQL Error: ".$mysqli->error;
        }

        $stmt->close();
        $mysqli->close();
    } else {
    echo "SQL Error: ".$mysqli->error;
    }
} else {
    echo "fail";
}

?>