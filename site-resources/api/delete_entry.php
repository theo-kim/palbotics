<?php

error_reporting(E_ALL);

include "connectdb.php";

$id = $_POST["id"];

if ($stmt = $mysqli->prepare("DELETE FROM registration WHERE id = ?")) {
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