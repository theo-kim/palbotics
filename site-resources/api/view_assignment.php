<?php

error_reporting(E_ALL);

//include "count_program.php";
include "connectdb.php";

if (isset($_POST["uid"])){
  $uid = $_POST["uid"];
  if ($stmt = $mysqli->prepare("SELECT v.vid, v.pid, v.status, p.name, p.start, p.age FROM volunteer v JOIN programs p ON v.pid = p.pid WHERE v.uid = ? AND v.status > 0")) {
    if ($stmt->bind_param("i", $uid)) {
      $stmt->execute();
      $stmt->bind_result($vid, $pid, $status, $name, $start, $age);

      $arr = array();
      $finArr = array();

      while ($stmt->fetch()) {
        $arr["pid"] = $pid;  
        $arr["vid"] = $vid;  
        $arr["name"] = $name;  
        $arr["start"] = date("M j", strtotime($start));  
        $arr["age"] = $age;  
        $finArr[] = $arr;
      }

      $stmt->close();
      
      foreach ($finArr as $key=>$value){
        if ($stmt = $mysqli->prepare("SELECT r.first, r.last, r.gender, r.grade, g.uid FROM registration r JOIN group_members g ON r.id = g.uid WHERE g.role = 'participant' AND g.gid = (SELECT d.gid FROM group_members d JOIN `group` q ON q.gid = d.gid WHERE d.uid = ? AND q.pid = ? AND d.role = 'mentor')")) {
          $stmt->bind_param("ii",$value["vid"], $value["pid"]);
          $stmt->execute();
          $stmt->bind_result($first, $last, $gender, $grade, $uid);
          
          $darr = array();
          $count = 0;
          
          while ($stmt->fetch()) {  
            $darr["first"] = $first;  
            $darr["last"] = $last;
            $darr["gender"] = $gender;
            $darr["grade"] = $grade;
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