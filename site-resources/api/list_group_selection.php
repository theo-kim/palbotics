<?php

error_reporting(E_ALL);

include "count_program.php";
//include "connectdb.php";

$id = $_GET["id"];
$role = $_GET["role"];

if (isset($id) && isset($role)){
    $query = "";
    if ($role == "mentor") {
        $query = "SELECT *, COUNT() FROM group"
    }
  if ($stmt = $mysqli->prepare("SELECT ")) {
    $stmt->execute();
    $stmt->bind_result($pid);

    $arr = array();
    $finArr = array();

    while ($stmt->fetch()) {
      $arr["pid"] = $pid;
      $arr["name"] = $name;
      $arr["start"] = date("m/d/Y", strtotime($start));
      $arr["end"] = date("m/d/Y", strtotime($end));
      $arr["age"] = $age;
      $arr["size"] = $size;
      $arr["registered"] = $registered;
      $arr["location"] = array();
      $arr["location"]["name"] = $lname;
      $arr["location"]["id"] = $lid;
      $arr["location"]["streetone"] = $lstreeto;
      $arr["location"]["streettwo"] = $lstreett;
      $arr["location"]["city"] = $lcity;
      $arr["location"]["state"] = $lstate;
      $arr["location"]["zip"] = $lzip;

      $finArr[] = $arr;
    }

    $stmt->close();
    $mysqli->close();
  }
}
echo json_encode($finArr);

?>