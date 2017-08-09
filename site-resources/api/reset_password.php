<?php

function crypto_rand_secure($min, $max) {
        $range = $max - $min;
        if ($range < 0) return $min; // not so random...
        $log = log($range, 2);
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
}

function getToken($length){
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    for($i=0;$i<$length;$i++){
        $token .= $codeAlphabet[crypto_rand_secure(0,strlen($codeAlphabet))];
    }
    return $token;
}

error_reporting(E_ALL);

include "connectdb.php";
include "mail.php";

$id = $_POST["username"];
$reset_code = getToken(10);
$reset = md5($reset_code);
$email = "";

if ($stmt = $mysqli->prepare("SELECT email FROM users WHERE username = ?")) {
  $stmt->bind_param("s", $id);
  $stmt->execute();
  $stmt->bind_result($email);
  if ($stmt->fetch()){
    $message = "<p>Hello,</p><p>This email is being sent because you requested to reset your password to be reset for your myPALBOTICS account. To reset your password, please follow the link below and enter the reset hash provided as well.</p><br><p><b>Reset Code: </b>".$reset_code."</p><p>Reset you password here: <a href  = 'http://my.robotics.yonkers-pal.org/reset_password.php'>http://my.robotics.yonkers-pal.org/reset_password.php</a></p>";
    
    $stmt->close();
    
    if (email($email, "", "", "Password Reset for your myPALBOTICS Account", $message)){
      if ($stmt = $mysqli->prepare("UPDATE users SET password_reset = ? WHERE username = ?")) {
        $stmt->bind_param("ss", $reset, $id);
        $stmt->execute();
        $a = $mysqli->affected_rows;
  
        if ($a > 0) {
          echo "success"; 
        } 
        else {
          echo "Could not update your reset code ".$mysqli->error;
        }  
        $stmt->close();
        $mysqli->close();
      } else {
        echo "Could not create statement ".$mysqli->error;
      }
    } else {
      echo "Failed to send the email with the password reset";
    }
    //$stmt->close();
  }
} else {  
  echo "You must be a ghost, you don't exist in our database";
}

?>