<?php

error_reporting(E_ALL);

include "connectdb.php";

$id = $_POST["id"];
$old = $_POST["old"];
$new = $_POST["new"];
$hash_old = md5($old);
$hash_new = md5($new);

if ($stmt = $mysqli->prepare("SELECT password_hash FROM users WHERE user_id = ?")) {
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $stmt->bind_result($cookie);
  if ($stmt->fetch()){
    if ($cookie == $hash_old) {
      $stmt->close();
      if ($stmt = $mysqli->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?")) {
        $stmt->bind_param("si", $hash_new, $id);
        $stmt->execute();
        $a = $mysqli->affected_rows;
  
        if ($a > 0) {
          echo "success"; 
        } 
        else {
          echo "Could not update your password";
        }  
        $stmt->close();
        $mysqli->close();
      }
    } else {
      echo "Your original password does not match the existing one we have in our records";
    }
  } else {
    echo "You must be a ghost, you don't exist in our database";
  }
} else {
  echo "Something went wrong, try again later";
}
 
?>