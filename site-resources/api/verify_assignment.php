<?php

error_reporting(E_ALL);

include "connectdb.php";

$id = $_POST["vid"];

if ($stmt = $mysqli->prepare("UPDATE volunteer SET status = 2 WHERE vid = ?")) {
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $a = $mysqli->affected_rows;
  
  if ($a > 0) {
    echo "success";
  }
  else {
    echo "fail";
  }  
  $stmt->close();
  $mysqli->close();
}
 
?>