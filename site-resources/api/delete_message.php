<?php

error_reporting(E_ALL);

include "connectdb.php";

$id = $_POST["mid"];

if ($stmt = $mysqli->prepare("DELETE FROM messages WHERE mid = ?")) {
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