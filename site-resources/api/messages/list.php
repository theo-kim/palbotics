<?php

error_reporting(E_ALL);

require "../connectdb.php";
require "../auth/get_permissions.php";

$uid = $identity;

if (isset($uid) && isset($role)) {
    $query = "SELECT m.uid, m.status, m.timestamp, m.mid, m.message, m.subject, m.source, u.first_name, u.last_name
                FROM messages AS m
                JOIN users AS u ON u.user_id = m.source
                WHERE m.uid = ? OR m.source = ?
                ORDER BY mid DESC";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("ii", $uid, $uid);
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

            $arr["timestamp"] = date("M j, Y", strtotime($timestamp));
            $arr["mid"] = $mid;
            $arr["message"] = $message;
            $arr["subject"] = $subject;
            $arr["source"] = $first." ".$last;
            $arr["sourceid"] = $source;

            if ($source != $identity) {
                if (!isset($finArr[$source])) {
                    $finArr[$source] = array();
                }
                $arr["self"] = false;
                $finArr[$source][] = $arr;
            } else {
                if (!isset($finArr[$uuid])) {
                    $finArr[$uuid] = array();
                }
                $arr["self"] = true;
                $finArr[$uuid][] = $arr;
            }
        }

        $donArr = array();
        $temp = "";
        foreach ($finArr as $key => $val) {
            foreach ($val as $sub) {
                if ($sub["sourceid"] == $key) {
                    $temp = $sub["source"];
                    break;
                }
            }
            $donArr[] = array(
                from => $key,
                name => $temp,
                body => $val
            );
        }

        $stmt->close();
        $mysqli->close();
    } else {
        echo "MySQL Error: ". $mysqli->error;
    }
} else {
    echo "No Variables";
}
echo json_encode($donArr);
