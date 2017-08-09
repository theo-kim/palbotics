<?php

error_reporting(E_ALL);

include "connectdb.php";

$username = $_GET["username"];

if ($username) {
    if ($stmt = $mysqli->prepare("SELECT username, email, first_name, last_name, user_id, role, joined FROM users WHERE username = ?")) {
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $stmt->bind_result($username, $email, $first, $last, $id, $role, $joined);
      $arr = array();
      $finalArr = array();

      while ($stmt->fetch()) {
        $arr["role"] = $role;
        $arr["email"] = $email;
        $arr["username"] = $username;
        $arr["last"] = $last;
        $arr["first"] = $first;
        $arr["joined"] = $joined;
        $arr["id"] = $id;
      }

      $stmt->close();
      $mysqli->close();

      echo json_encode($arr);

    }
} else {
    echo "failure";
}
?>