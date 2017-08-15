<?php

error_reporting(E_ALL);

require "count.php";

if ($role == 0) {
    if ($stmt = $mysqli->prepare("SELECT programs.*, locations.* FROM programs JOIN locations ON (programs.location = locations.lid) WHERE programs.end >= NOW()")) {
        $stmt->execute();
        $stmt->bind_result($pid, $name, $start, $end, $age, $size, $registered, $location, $lid, $lname, $lstreeto, $lstreett, $lcity, $lstate, $lzip);

        $arr = array();
        $finArr = array();

        while ($stmt->fetch()) {
            $arr["pid"] = $pid;
            $arr["name"] = $name;
            $arr["start"] = date("m/d/Y", strtotime($start));
            $arr["end"] = date("m/d/Y", strtotime($end));
            $arr["age"] = $age;
            $arr["size"] = $size;
            $arr["registered"] = $registered;
            $arr["location"] = array();
            $arr["location"]["name"] = $lname;
            $arr["location"]["id"] = $lid;
            $arr["location"]["streetone"] = $lstreeto;
            $arr["location"]["streettwo"] = $lstreett;
            $arr["location"]["city"] = $lcity;
            $arr["location"]["state"] = $lstate;
            $arr["location"]["zip"] = $lzip;

            $finArr[] = $arr;
        }

        $stmt->close();
        $mysqli->close();
    }
} else {
    echo "No Variables";
}
echo json_encode($finArr);
