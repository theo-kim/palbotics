<?php

error_reporting(E_ALL);

include "connectdb.php";

if (isset($_GET["vid"])){
  $pid = $_GET["vid"];
  if ($stmt = $mysqli->prepare("SELECT gid FROM group_members WHERE uid = ? AND role = 'mentor'")) {
    if ($stmt->bind_param("i", $vid)) {
      $stmt->execute();
      $stmt->bind_result($gid);
      $finArr = array();

      if ($stmt->fetch()) {
          echo $gid;
      }

      $stmt->close();

  } else {
    echo $mysqli->error;
    $mysqli->close();
  }
} else {
  echo "No Variables";
}
?>