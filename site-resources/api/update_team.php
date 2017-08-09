<?php

error_reporting(E_ALL);

include "connectdb.php";

if (isset($_POST)) {
  $gid = $_POST["gid"];
  $name = $_POST["name"];
  $sponsor = $_POST["sponsors"];
  $slogan = $_POST["slogan"];
  $budget = $_POST["budget"];
  $flag = false;
  //$gid = $_POST["gid"];
  if (isset($_FILES) && !empty($_FILES)) {
    
    $flag = false;
    $path = "../images/teams/".$gid."/logo";
    $adjusted_path = "site-resources/images/teams/".$gid."/logo/";
    $path_small = "../images/teams/".$gid;
    if (file_exists($path)) {
      if(move_uploaded_file($_FILES[0]['tmp_name'], $path."/".basename($_FILES[0]['name']))) {
        $logo_path = $adjusted_path.basename($_FILES[0]['name']);
        //echo $path_logo;
      }
      else {
        $flag = true;
      }
    } else {
      if (file_exists($path_small)) {
        if (mkdir($path) == true){
          if(move_uploaded_file($_FILES[0]['tmp_name'], $path."/".basename($_FILES[0]['name']))) {
            $logo_path = $adjusted_path.basename($_FILES[0]['name']);          } 
        } else {
          echo "Could not make target directory";
          $flag = true;
        }
      } else {
        if (mkdir($path_small) == true){
          if (mkdir($path) == true){
            if(move_uploaded_file($_FILES[0]['tmp_name'], $path."/".basename($_FILES[0]['name']))) {
              $logo_path = $adjusted_path.basename($_FILES[0]['name']);            } 
          } else {
            echo "Could not make target directory";
            $flag = true;
          }
        }
      }
    }
  } else {
    if ($stmt = $mysqli->prepare("SELECT logo_path FROM group_info WHERE gid = ?")) {
      $stmt->bind_param("i", $gid);
      $stmt->execute();
      $stmt->bind_result($logos);
      if ($stmt->fetch()){
        if ($logos == null) {
          $logo_path = "a";
        } else {
          $logo_path = $logos;
        }
      } else {
        $logo_path = "a";
      }
      $stmt->close();
    } else {
      echo $mysqli->error;
    }
  }
  
  if ($flag == false) {
    
    if ($stmt = $mysqli->prepare("SELECT gid FROM group_info WHERE gid = ?")) {
      $stmt->bind_param("i", $gid);
      $stmt->execute();
      $stmt->bind_result($trash);
      if ($stmt->fetch()){
        $query = "UPDATE group_info SET name = ?, sponsors = ?, slogan = ?, logo_path = ? WHERE gid = ?";
      } else {
        $query = "INSERT INTO group_info(name, sponsors, slogan, logo_path, gid) VALUES(?,?,?,?,?)";
      }
      $stmt->close();
    } else {
      echo $mysqli->error;
    }
    if ($stmt = $mysqli->prepare($query)) {
      $stmt->bind_param("ssssi", $name, $sponsor, $slogan, $logo_path, $gid);
      $stmt->execute();
      $a = $mysqli->affected_rows;
      if ($a > 0) {
        $response = "success";
      } else if ($a < 0) {
        echo $mysqli->error;
      }
      else {
        $response = "No changes were made.";
      }  
      $stmt->close();
      //$mysqli->close();
    } else {
      echo "Database error 2: ".$mysqli->error;
    }
    $query = "INSERT INTO group_budget (gid, budget) VALUES (?,?) ON DUPLICATE KEY UPDATE budget = ?";
    if ($stmt = $mysqli->prepare($query)) {
      $stmt->bind_param("iss", $gid, $budget, $budget);
      $stmt->execute();
      $a = $mysqli->affected_rows;
      if ($a > 0) {
        echo "success";
      } else if ($a < 0) {
        echo $mysqli->error;
      }
      else {
        echo $response;
      }  
      $stmt->close();
      $mysqli->close();
    } else {
      echo "Database Error 3: ".$mysqli->error;
    }
  } else {
    echo "Flagged";
  }
} else {
  echo "No variables";
}
 
?>