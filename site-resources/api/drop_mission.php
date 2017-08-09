<?php

error_reporting(E_ALL);

include "connectdb.php";

$id = $_POST["id"];

if ($id) {
    if ($stmt = $mysqli->prepare("DELETE FROM mission WHERE moid = ?")) {
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
} else {
    echo "fail";
}

?>