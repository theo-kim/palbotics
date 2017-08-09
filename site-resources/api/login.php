<?php

error_reporting(E_ALL);

include "connectdb.php";

$username = $_POST["username"];

if ($username) {
    if ($stmt = $mysqli->prepare("SELECT password_hash FROM users WHERE username = ?")) {
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $stmt->bind_result($password);
      if ($stmt->fetch()) {
        // $password is the stored password, $_POST["password"] is the one entered by the user logging in
        if (md5($_POST["password"]) == $password) {
          echo trim("success");
        }
        else {
          sleep(1); //pause a bit to help prevent brute force attacks
          echo "fail";
        }
      }
      else {
        echo "fail";
      }
      $stmt->close();
      $mysqli->close();
    }
} else {
    echo "fail";
}
?>