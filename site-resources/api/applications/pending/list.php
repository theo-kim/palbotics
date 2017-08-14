<?php

error_reporting(E_ALL);

include "../../connectdb.php";
require "../../auth/get_permissions.php";

$uid = $_GET["uid"];

if ($uid == -1 && $role == 0) {
    $query = "SELECT registration.*, programs.*
                FROM registration
                JOIN programs ON registration.pid=programs.pid
                WHERE registration.status < 1
                ORDER BY registration.id ASC";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->execute();
        $stmt->bind_result($id, $first, $last, $age, $gender, $grade, $school, $shirt, $additional_info, $program, $program_time, $first_parent, $last_parent, $street_1, $street_2, $city, $state, $zip, $phone, $email, $emergency_name, $emergency_phone, $timestamp, $status, $uid, $pid, $puid, $pid2, $name, $start, $end, $group, $size, $registered, $location);

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
            $arr["status"] = $status;
            $arr["uid"] = $uid;

            $finArr[] = $arr;
        }

        $stmt->close();
        $mysqli->close();
    } else {
        echo $mysqli->error;
    }
} elseif ($uid > 0 && $role == 1) {
    $query = "SELECT registration.*, programs.*
                FROM registration
                JOIN programs ON registration.pid=programs.pid
                WHERE registration.status < 1 AND uid = ?
                ORDER BY id ASC";
    if ($stmt = $mysqli->prepare($query)) {
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
            $arr["status"] = $status;
            $arr["uid"] = $uid;

            $finArr[] = $arr;
        }

        $stmt->close();
        $mysqli->close();
    } else {
        echo "state";
    }
} else {
    echo "failure";
}

echo json_encode($finArr);
