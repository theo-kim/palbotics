<?php

error_reporting(E_ALL);

require "../connectdb.php";

  $hash = md5("password");
  if ($stmt = $mysqli->prepare("UPDATE users u JOIN registration r ON u.user_id = r.uid SET u.password_hash = ? WHERE r.status = 1")) {
      $stmt->bind_param("s", $hash);
      $stmt->execute();
      $a = $mysqli->affected_rows;

      if ($a > 0) {
          echo "success";
      } else {
          echo "Could not update your password, check your code";
      }
      $stmt->close();
      $mysqli->close();
  } else {
      echo "Something went wrong, try again later";
  }
