<?php

error_reporting(E_ALL);

include "connectdb.php";

$flag = false;

$id = $_POST["id"];
$first = $_POST['first'];
$last = $_POST['last'];
$age = $_POST['age-group'];
$gender = $_POST['gender'];
$grade = $_POST['grade'];
$school = $_POST['school'];
$program = $_POST['program'];
$program_time = $_POST['session'];
$first_parent = $_POST['first_parent'];
$last_parent = $_POST['last_parent'];
$street_1 = $_POST['street_1'];
if (isset($_POST['street_2'])) {
  $street_2 = $_POST['street_2'];    
} else {  
  $street_2 = "";
}
$city = $_POST['city'];
$state = $_POST['state'];
$zip = $_POST['zip'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$emergency_name = $_POST['emergency_name'];
$emergency_phone = $_POST['emergency_phone'];

$_POST['street_2'] = " ";

$key = array_search("", $_POST);
if ($key != "") {
  $flag = true;
  echo "Please fill out field for ".$key;
}

if ($_POST["program"] == "Click to reveal choices") {
  echo "Please specify a program";
} else if ($_POST["session"] == "Click to reveal choices") {
  echo "Please specify a program session";
} else if ($_POST["age-group"] == "Click to reveal choices") {
  echo "Please specify an age group";
} else if ($flag == false) {
  if ($stmt = $mysqli->prepare("UPDATE registration SET first = ?, last = ?, age = ?, gender = ?, grade = ?, school = ?, program = ?, program_time = ?, first_parent = ?, last_parent = ?, street_1 = ?, street_2 = ?, city = ?, state = ?, zip = ?, phone = ?, email = ?, emergency_name = ?, emergency_phone = ? WHERE id = ?")) {
    $stmt->bind_param("sssssssssssssssssssi", $first, $last, $age, $gender, $grade, $school, $program, $program_time, $first_parent, $last_parent, $street_1, $street_2, $city, $state, $zip, $phone, $email, $emergency_name, $emergency_phone, $id);
    $stmt->execute();
    $a = $mysqli->affected_rows;
  
    if ($a > 0) {
      echo "success";
    }
    else {
      echo "The information you entered is identical to what we already have on file";
    }  
    $stmt->close();
    $mysqli->close();
  }
}
 
?>