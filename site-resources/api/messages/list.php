<?php

error_reporting(E_ALL);

require "../connectdb.php";
require "../auth/get_permissions.php";

$uid = $identity;

if (isset($uid) && isset($role)) {
    "SELECT m.uid, m.status, m.timestamp, m.mid, m.message, m.subject, m.source, u.first_name, u.last_name
        FROM messages AS m
        JOIN users AS u ON u.user_id = m.source
        WHERE m.uid = ?
        ORDER BY mid DESC";
    if ($stmt = $mysqli->prepare()) {
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $stmt->bind_result($uuid, $status, $timestamp, $mid, $message, $subject, $source, $first, $last);

        $arr = array();
        $finArr = array();

        while ($stmt->fetch()) {
            $arr["uid"] = $uuid;

            switch ($status) {
        case 0:
          $arr["status"] = "Unread";
          break;
        case 1:
          $arr["status"] = "Read";
          break;
      }

            $arr["timestamp"] = $timestamp;
            $arr["mid"] = $mid;
            $arr["message"] = $message;
            $arr["subject"] = $subject;
            $arr["source"] = $first." ".$last;
            $arr["sourceid"] = $source;
            $finArr[] = $arr;
        }

        $stmt->close();
        $mysqli->close();
    }
}
echo json_encode($finArr);
