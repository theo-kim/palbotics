<?php

error_reporting(E_ALL);

include "connectdb.php";

if (isset($_GET["uid"])) {
    $query = "SELECT volunteer . * , programs . * , group_members.gid
                FROM volunteer
                JOIN programs ON programs.pid = volunteer.pid
                LEFT OUTER JOIN group_members ON group_members.uid = `volunteer`.vid AND group_members.role='mentor'
                LEFT OUTER JOIN `group` ON `group`.gid = group_members.gid
                LEFT OUTER JOIN programs AS pd ON pd.pid = `group`.pid
                WHERE programs.end > CURDATE( )
                AND volunteer.status > -1
                AND (pd.pid IS NULL OR pd.end > CURDATE( ))
                AND volunteer.uid = ?
                ORDER BY volunteer.vid ASC";

  if ($stmt = $mysqli->prepare($query)) {
      $stmt->bind_param("i", $_GET["uid"]);
    $stmt->execute();
    $stmt->bind_result($id, $first, $last, $grade, $shirt, $additional_info, $phone, $email, $pid, $uid, $status, $pid2, $name, $start, $end, $group, $size, $registered, $location, $gid);

    $arr = array();
    $finArr = array();

    while ($stmt->fetch()) {
      $arr["id"] = $id;
      $arr["first"] = $first;
      $arr["last"] = $last;
      $arr["grade"] = $grade;
      $arr["pid"] = $pid;
      switch ($status){
        case 0: $arr["status"] = "Unassigned";
          break;
        case 1: $arr["status"] = "Unconfirmed";
          break;
        case 2: $arr["status"] = "Good";
          break;
        default: break;
      }
      $arr["gid"] = $gid;
      $arr["shirt"] = $shirt;
      $arr["additional_info"] = $additional_info;
      $arr["program"] = $name;
      $arr["group"] = $group;
      $arr["program_time"] = date("m/d/Y", strtotime($start))." - ".date("m/d/Y", strtotime($end));
      $arr["phone"] = $phone;
      $arr["email"] = $email;
      $arr["uid"] = $uid;
    }

    $stmt->close();
    $mysqli->close();
  } else {
    echo $mysqli->error;
  }
  echo json_encode($arr);
}

?>