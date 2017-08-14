<?php

error_reporting(E_ALL);

require "../../connectdb.php";
require "../../auth/get_permissions.php";

$vid = $_GET["vid"];
if (isset($vid) && $role == 1){
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