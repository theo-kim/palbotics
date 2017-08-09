<?php

error_reporting(E_ALL);

include "connectdb.php";

$id = $_POST["id"];
$username = $_POST["username"];
$first = $_POST["first"];
$last = $_POST["last"];
$email = $_POST["email"];

if ($stmt = $mysqli->prepare("UPDATE users SET username = ?, first_name = ?, last_name = ?, email = ? WHERE user_id = ?")) {
  $stmt->bind_param("ssssi", $username, $first, $last, $email, $id);
  $stmt->execute();
  $a = $mysqli->affected_rows;
  
  if ($a > 0) {
    echo "success";
  }
  else {
    echo "No changes could be made";
  }  
  $stmt->close();
  $mysqli->close();
} else {
  echo "Database error: ".$mysqli->error;
}
 
?>