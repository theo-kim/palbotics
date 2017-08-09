<?php

error_reporting(E_ALL);

include "connectdb.php";

if (isset($_POST["pass"]) && isset($_POST["hash"])) {
  $hash = md5($_POST["hash"]);
  $new = $_POST["pass"];
  $new_hashed = md5($new);
  if ($stmt = $mysqli->prepare("UPDATE users SET password_hash = ?, password_reset = '' WHERE password_reset = ?")) {
    $stmt->bind_param("ss", $new_hashed, $hash);
    $stmt->execute();
    $a = $mysqli->affected_rows;
  
    if ($a > 0) {
      echo "success"; 
    } 
    else {
      echo "Could not update your password, check your code";
    }  
    $stmt->close();
    $mysqli->close();
  } else {
    echo "Something went wrong, try again later";
  }
} else {
  echo "Please specify a new password";
}
 
?>