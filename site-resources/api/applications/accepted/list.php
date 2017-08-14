<?php
error_reporting(E_ALL);

require "../../connectdb.php";
require "../../auth/get_permissions.php";

$uid = $_GET["uid"];
if ($uid == -1 && $role == 0) {
    if ($stmt = $mysqli->prepare("SELECT registration.*, programs.* FROM registration JOIN programs ON registration.pid=programs.pid WHERE registration.status > 0 AND programs.end >= NOW() ORDER BY id ASC")) {
        //$stmt->bind_param("s", $username);
        $stmt->execute();
        if ($stmt->bind_result($id, $first, $last, $age, $gender, $grade, $school, $shirt, $additional_info, $program, $program_time, $first_parent, $last_parent, $street_1, $street_2, $city, $state, $zip, $phone, $email, $emergency_name, $emergency_phone, $timestamp, $status, $uid, $pid, $pid2, $puid, $name, $start, $end, $group, $size, $registered, $location)) {
            $arr = array();
            $finArr = array();

            while ($stmt->fetch()) {
                $arr["id"] = $id;
                $arr["first"] = $first;
                $arr["last"] = $last;
                $arr["age"] = $group;
                $arr["gender"] = $gender;
                $arr["grade"] = $grade;
                $arr["school"] = $school;
                $arr["shirt"] = $shirt;
                $arr["additional_info"] = $additional_info;
                $arr["program"] = $name;
                $arr["program_time"] = date("m/d/Y", strtotime($start))." - ".date("m/d/Y", strtotime($end));
                $arr["first_parent"] = $first_parent;
                $arr["last_parent"] = $last_parent;
                $arr["street_1"] = $street_1;
                $arr["street_2"] = $street_2;
                $arr["city"] = $city;
                $arr["state"] = $state;
                $arr["zip"] = $zip;
                $arr["phone"] = $phone;
                $arr["email"] = $email;
                $arr["emergency_name"] = $emergency_name;
                $arr["emergency_phone"] = $emergency_phone;
                $arr["timestamp"] = $timestamp;
                switch ($status) {
                  case 0:
                    $arr["status"] = "Pending";
                    break;
                  case 1:
                    $arr["status"] = "Unverified (Accepted)";
                    break;
                  case 2:
                    $arr["status"] = "Material Fee Unpaid";
                    break;
                  case 3:
                    $arr["status"] = "Missing Information";
                    break;
                  default:
                    $arr["status"] = "ERROR";
                    break;
                }
                $arr["uid"] = $uid;

                $finArr[] = $arr;
                echo json_encode($finArr);
            }
        } else {
            http_response_code(500);
            echo "SQL Error: ".$mysqli->error;
        }

        $stmt->close();
        $mysqli->close();
    } else {
        http_response_code(500);
        echo "SQL Error: ".$mysqli->error;
    }
} elseif (isset($uid) && $uid > 0 && $role == 1) {
    if ($stmt = $mysqli->prepare("SELECT registration.*, programs.* FROM registration JOIN programs ON registration.pid=programs.pid WHERE registration.status > 0 AND uid = ? AND programs.end >= NOW() ORDER BY id ASC")) {
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $stmt->bind_result($id, $first, $last, $age, $gender, $grade, $school, $shirt, $additional_info, $program, $program_time, $first_parent, $last_parent, $street_1, $street_2, $city, $state, $zip, $phone, $email, $emergency_name, $emergency_phone, $timestamp, $status, $uid, $pid, $pid2, $puid, $name, $start, $end, $group, $size, $registered, $location);

        $arr = array();
        $finArr = array();

        while ($stmt->fetch()) {
            $arr["id"] = $id;
            $arr["first"] = $first;
            $arr["last"] = $last;
            $arr["age"] = $group;
            $arr["gender"] = $gender;
            $arr["grade"] = $grade;
            $arr["school"] = $school;
            $arr["shirt"] = $shirt;
            $arr["additional_info"] = $additional_info;
            $arr["program"] = $name;
            $arr["program_time"] = date("m/d/Y", strtotime($start))." - ".date("m/d/Y", strtotime($end));
            $arr["first_parent"] = $first_parent;
            $arr["last_parent"] = $last_parent;
            $arr["street_1"] = $street_1;
            $arr["street_2"] = $street_2;
            $arr["city"] = $city;
            $arr["state"] = $state;
            $arr["zip"] = $zip;
            $arr["phone"] = $phone;
            $arr["email"] = $email;
            $arr["emergency_name"] = $emergency_name;
            $arr["emergency_phone"] = $emergency_phone;
            $arr["timestamp"] = $timestamp;
            switch ($status) {
                case 0:
                  $arr["status"] = "Pending";
                  break;
                case 1:
                  $arr["status"] = "Unverified (Accepted)";
                  break;
                case 2:
                  $arr["status"] = "Deposit Unpaid";
                  break;
                case 3:
                  $arr["status"] = "Missing Information";
                  break;
                default:
                  $arr["status"] = "ERROR";
                  break;
            }
            $arr["uid"] = $uid;
            $arr["pid"] = $pid;
            $finArr[] = $arr;
            echo json_encode($finArr);
        }

        $stmt->close();
        $mysqli->close();
    } else {
        echo "state";
    }
} else {
    http_response_code(400);
}
