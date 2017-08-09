<?php

error_reporting(E_ALL);

//include "count_program.php";
include "connectdb.php";

if (isset($_GET["gid"])){
  $gid = $_GET["gid"];
  if ($stmt = $mysqli->prepare("SELECT p.name, p.start, p.age, g.gid, i.name, i.sponsors, i.logo_path, i.slogan FROM `group` AS g JOIN programs AS p ON p.pid = g.pid LEFT JOIN group_info AS i ON g.gid = i.gid WHERE g.gid = ?")) {
    if ($stmt->bind_param("i", $gid)) {
      $stmt->execute();
      $stmt->bind_result($name, $start, $age, $gid, $team_name, $sponsors, $logo, $slogan);

      $arr = array();
      $finArr = array();

      while ($stmt->fetch()) {
        $arr["name"] = $name;
        $arr["start"] = date("M j", strtotime($start));
        $arr["age"] = $age;
        $arr["gid"] = $gid;
        if ($team_name == null) {
          $arr["team_name"] = "Not Specified";
        } else {
          $arr["team_name"] = $team_name;
        }
        if ($sponsors == null) {
          $arr["sponsors"] = "Not Specified";
        } else {
          $arr["sponsors"] = $sponsors;
        }
        if ($logo == null) {
          $arr["logo"] = "";
        } else {
          $arr["logo"] = $logo;
        }
        if ($slogan == null) {
          $arr["slogan"] = "Not Specified";
        } else {
          $arr["slogan"] = $slogan;
        }
        $finArr[] = $arr;
      }

      $stmt->close();

      foreach ($finArr as $key=>$value){
        if ($stmt = $mysqli->prepare("SELECT r.first, r.last, r.gender, r.grade, g.uid FROM registration r JOIN group_members g ON r.id = g.uid WHERE g.role = 'participant' AND g.gid = ?")) {
          $stmt->bind_param("i", $gid);
          $stmt->execute();
          $stmt->bind_result($first, $last, $gender, $grade, $uid);

          $darr = array();
          $count = 0;

          while ($stmt->fetch()) {
            $darr["first"] = $first;
            $darr["last"] = $last;
            $darr["gender"] = $gender;
            $darr["grade"] = $grade;
            $darr["uid"] = $uid;
            $finArr[$key]["participants"][] = $darr;
            $count++;
          }
          $finArr[$key]["participants"]["count"] = $count;

          $stmt->close();
        } else {
          echo $mysqli->error;
          break;
        }
      }
    } else {
      echo $mysqli->error;
    }
    $mysqli->close();
  } else {
    echo $mysqli->error;
    $mysqli->close();
  }
  if (empty($finArr)){
    echo "No Groups have been defined";
  } else {
    echo json_encode($finArr);
  }
} else {
  echo "No Variables";
}
?>