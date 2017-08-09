<?php

error_reporting(E_ALL);

include "connectdb.php";

$pid = $_POST["pid"];
$max = $_POST["max"];
$section = $_POST["section"];

if (isset($pid) && isset($section) && isset($max)) {


  if ($stmt = $mysqli->prepare("INSERT INTO `group`(`pid`, `section`, `size`) VALUES (?,?,?)")) {
    $stmt->bind_param("iii", $pid, $section, $max);
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
  } else {
    echo $mysqli->error;
  }
} else {
  echo "No vars";
}

?>