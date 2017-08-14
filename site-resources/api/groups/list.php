<?php

error_reporting(E_ALL);

require "../connectdb.php";
require "../auth/get_permissions.php";

$pid = $_GET["pid"];
if (isset($pid) && $role == 0) {
    if ($stmt = $mysqli->prepare("SELECT registration.id, registration.first, registration.last, group_members.*, group.gid, group.section, group_info.name, group_info.slogan, group_info.logo_path FROM registration JOIN group_members ON registration.id = group_members.uid JOIN `group` ON `group`.`gid` = `group_members`.`gid` LEFT OUTER JOIN `group_info` ON `group_info`.`gid`=`group`.`gid` WHERE registration.pid = ? AND status > 0 AND group_members.role = 'participant'")) {
        if ($stmt->bind_param("i", $pid)) {
            $stmt->execute();
            $stmt->bind_result($uid, $first, $last, $mgid, $gid, $uid, $role, $gid2, $section, $name, $slogan, $logo);

            $arr = array();
            $finArr = array();

            while ($stmt->fetch()) {
                $arr["uid"] = $uid;
                $arr["first"] = $first;
                $arr["last"] = $last;
                $arr["gid"] = $gid;
                $arr["section"] = $section;
                $arr["role"] = "participant";
                $arr["name"] = $name;
                $arr["slogan"] = $name;
                $arr["logo"] = $name;
                $finArr[] = $arr;
            }

            $stmt->close();

            if ($stmt = $mysqli->prepare("SELECT volunteer.vid, volunteer.first, volunteer.last, group_members.*, group.gid, group.section FROM volunteer JOIN group_members ON group_members.uid = volunteer.vid JOIN `group` ON group.gid = group_members.gid WHERE volunteer.pid = ? AND status > 0 AND group_members.role = 'mentor'")) {
                $stmt->bind_param("i", $pid);
                $stmt->execute();
                $stmt->bind_result($uid, $first, $last, $mgid, $gid, $uid, $role, $gid2, $section);

                $arr = array();

                while ($stmt->fetch()) {
                    $arr["uid"] = $uid;
                    $arr["first"] = $first;
                    $arr["last"] = $last;
                    $arr["gid"] = $gid;
                    $arr["section"] = $section;
                    $arr["role"] = "mentor";
                    $finArr[] = $arr;
                }
                $stmt->close();
                if ($stmt = $mysqli->prepare("SELECT `group`.* FROM `group` LEFT OUTER JOIN `group_members` ON `group_members`.gid = `group`.gid WHERE pid = ? AND `group_members`.mgid IS NULL")) {
                    $stmt->bind_param("i", $pid);
                    $stmt->execute();
                    $stmt->bind_result($gid, $pid, $section, $size);
                    $arr = array();
                    if ($stmt->fetch()) {
                        $arr["gid"] = $gid;
                        $arr["pid"] = $pid;
                        $arr["section"] = $section;
                        $arr["size"] = $size;
                        $finArr[] = $arr;
                    } else {
                        echo $mysqli->error;
                    }
                    $stmt->close();
                } else {
                    echo $mysqli->error;
                }
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
    if (empty($finArr)) {
        echo "No Groups have been defined";
    } else {
        echo json_encode($finArr);
    }
} else {
    echo "No Variables";
}
