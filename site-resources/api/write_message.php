<?php

error_reporting(E_ALL);

include "connectdb.php";
include "mail.php";

if (isset($_POST["destination"]) && isset($_POST["source"]) && isset($_POST["subject"]) && isset($_POST["message"])) {
  
  $dest = $_POST["destination"];
  $src = $_POST["source"];
  $subj = $_POST["subject"];
  $mess = nl2br($_POST["message"]);

  if ($stmt = $mysqli->prepare("INSERT INTO messages (uid, message, subject, source) VALUES (?, ?, ?, ?)")) {
    $stmt->bind_param("issi", $dest, $mess, $subj, $src);
    $stmt->execute();
    $a = $mysqli->affected_rows;
    $stmt->close();
    if ($a > 0) {
      if ($stmt = $mysqli->prepare("SELECT email, first_name, last_name FROM users WHERE user_id = ?")) {
        $stmt->bind_param("i", $dest);
        $stmt->execute();
        $stmt->bind_result($email, $first, $last);
        if ($stmt->fetch()){
          $stmt->close();
          $message = "<p>Hello,</p><p>This is an email alert that you have a new message in your myPALBOTICS portal account.  Please login to your account on our site: <a href = 'http://my.robotics.yonkers-pal.org'>my.robotics.yonkers-pal.org</a>.</p><p>Thank you and have a nice day.</p>" ;
        
          if (email($email, $first, $last, "You have a new myPALBOTICS message", $message)) {
            echo "success";
          } else {
            echo "Email Failure";
          }
        } else {
          echo "Bondage Failure";
        }
      }
      else {
        echo "fail";
      }  
      $mysqli->close();
    } else {
      echo "Bondage Failure";
    }
  }
} else {
  echo "Missing Information";
}
 
?>