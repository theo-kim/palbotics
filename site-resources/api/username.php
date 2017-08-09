<?php

error_reporting(E_ALL);

include "connectdb.php";

$username = $_GET["username"];

if ($username) {
    if ($stmt = $mysqli->prepare("SELECT username FROM users WHERE username = ?")) {
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $stmt->bind_result($gen);
      if ($stmt->fetch()) {
        echo trim("success");
      }
      else {
        echo "fail";
      }
      $stmt->close();
      $mysqli->close();
    }
}

else {
    echo "fail";
}

?>