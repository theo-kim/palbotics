<?php

error_reporting(E_ALL);

require "../../connectdb.php";
require "../../auth/get_permissions.php";

$pid = $_POST["pid"];

if (isset($pid) && $role == 0) {
    $count = 0;
    $query = "SELECT registration.*, programs.*
                FROM registration
                JOIN programs ON registration.pid=programs.pid
                WHERE registration.status > 0 AND registration.pid = ?
                ORDER BY id ASC";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("i", $pid);
        $stmt->execute();
        if ($stmt->bind_result($id, $first, $last, $age, $gender, $grade, $school, $shirt, $additional_info, $program, $program_time, $first_parent, $last_parent, $street_1, $street_2, $city, $state, $zip, $phone, $email, $emergency_name, $emergency_phone, $timestamp, $status, $uid, $pid, $pid2, $puid, $name, $start, $end, $group, $size, $registered, $location)) {
            $arr = array();
            $finArr = array();

            while ($stmt->fetch()) {
                $arr["uid"] = $id;
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
                $arr["usid"] = $uid;
                $count++;
                $arr["role"] = "participant";
                $finArr[] = $arr;
            }
        }

        $stmt->close();
        if ($stmt = $mysqli->prepare("SELECT vid, first, last FROM volunteer WHERE pid = ? AND status > 0")) {
            $stmt->bind_param("i", $pid);
            $stmt->execute();
            $stmt->bind_result($uid, $first, $last);

            $arr = array();

            while ($stmt->fetch()) {
                $arr["uid"] = $uid;
                $arr["first"] = $first;
                $arr["last"] = $last;
                $arr["role"] = "mentor";
                $finArr[] = $arr;
                $count++;
            }
            $stmt->close();
            if ($stmt = $mysqli->prepare("SELECT MAX(section) FROM `group` WHERE pid = ?")) {
                $stmt->bind_param("i", $pid);
                $stmt->execute();
                $stmt->bind_result($ghetto);

                if ($stmt->fetch()) {
                    $finArr[0]["groupings"] = $ghetto;
                    $finArr["lengthy"] = $count;
                    $finArr["length"] = 1;
                } else {
                    echo $mysqli->error;
                }
                $stmt->close();

                echo json_encode($finArr);
            } else {
                echo $mysqli->error;
            }
        } else {
            echo $mysqli->error;
        }
        $mysqli->close();
    } else {
        echo $mysqli->error;
        $mysqli->close();
    }
} else {
    echo "No variables";
}
