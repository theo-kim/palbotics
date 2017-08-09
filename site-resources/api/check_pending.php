<?php

error_reporting(E_ALL);

include "connectdb.php";

$uid = $_POST["uid"];

if ($uid == -1) {
  if ($stmt = $mysqli->prepare("SELECT r.status, r.pid, p.start, p.end FROM registration AS r JOIN programs AS p ON r.pid = p.pid WHERE r.status = 0 AND p.end > CURDATE()")) {
    //$stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($gen);
    if ($stmt->fetch()) {
      echo trim("success");
    }
    else {
      echo "fail";
    }  
    $stmt->close();
    $mysqli->close();
  }
} else {
  if ($stmt = $mysqli->prepare("SELECT status FROM registration WHERE status = 0 AND uid = ?")) {
    $stmt->bind_param("s", $uid);
    $stmt->execute();
    $stmt->bind_result($gen);
    if ($stmt->fetch()) {
      echo trim("success");
    }
    else {
      echo "fail";
    }  
    $stmt->close();
    $mysqli->close();
  }
}
 
?>