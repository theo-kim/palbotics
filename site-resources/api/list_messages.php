<?php

error_reporting(E_ALL);

include "connectdb.php";

if (isset($_POST["uid"])){
  $uid = $_POST["uid"];

  if ($stmt = $mysqli->prepare("SELECT messages.uid, messages.status, messages.timestamp, messages.mid, messages.message, messages.subject, messages.source, users.first_name, users.last_name FROM messages, users WHERE messages.uid = ? AND users.user_id = messages.source ORDER BY mid DESC")) {
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $stmt->bind_result($uuid, $status, $timestamp, $mid, $message, $subject, $source, $first, $last);
    
    $arr = array();
    $finArr = array();
      
    while ($stmt->fetch()) {
      $arr["uid"] = $uuid;
      
      switch ($status) {
        case 0:
          $arr["status"] = "Unread";
          break;
        case 1:
          $arr["status"] = "Read";
          break;
      }
      
      $arr["timestamp"] = $timestamp;
      $arr["mid"] = $mid;
      $arr["message"] = $message;
      $arr["subject"] = $subject;
      $arr["source"] = $first." ".$last;
      $arr["sourceid"] = $source;
      $finArr[] = $arr;
    }
    
    $stmt->close();
    $mysqli->close();
  } 
} 
echo json_encode($finArr);

?>