<?php

error_reporting(E_ALL);

include "connectdb.php";

if (isset($_POST["uid"])){

$uid = $_POST["uid"];

  if ($stmt = $mysqli->prepare("SELECT COUNT(*) FROM messages WHERE uid = ? AND status = 0")) {
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $stmt->bind_result($number);
    if ($stmt->fetch()) {
      echo trim($number);
    }
    else {
      echo "fail";
    }  
    $stmt->close();
    $mysqli->close();
  }
}
 
?>