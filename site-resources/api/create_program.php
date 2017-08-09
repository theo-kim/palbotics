<?php

error_reporting(E_ALL);

include "connectdb.php";

if (isset($_POST)) {
  
  $type = $_POST["type"];
  $age = $_POST["group"];
  $capacity = $_POST["capacity"];
  $start =  date('Y-m-d H:i:s', strtotime($_POST["starting"]));;
  $end = date('Y-m-d H:i:s', strtotime($_POST["ending"]));

  if ($stmt = $mysqli->prepare("INSERT INTO programs (name, start, end, age, size) VALUES (?, ?, ?, ?, ?)")) {
    $stmt->bind_param("sssss", $type, $start, $end, $age, $capacity);
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
}
 
?>