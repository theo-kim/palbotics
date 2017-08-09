<?php

error_reporting(E_ALL);

function get_account_info($username, $mysqli, $vendord) {
  $flagged = false;
  if ($stmt = $mysqli->prepare("SELECT role FROM users WHERE username = ?")) {
    $stmt->bind_param("s",$username);
    $stmt->execute();
    $stmt->bind_result($role);
    while($stmt->fetch()){
      $roled = $role;
    }
    $finArr = array();
    $stmt->close();
    if ($roled == "Lead Mentor") {
      if ($stmt = $mysqli->prepare("SELECT u.first_name, u.last_name, o.oid, o.delivered, m.timestamp, b.budget, m.quantity, m.cost FROM users u JOIN volunteer r ON u.user_id = r.uid JOIN group_members g ON g.uid = r.vid JOIN group_budget b ON b.gid = g.gid JOIN group_orders o ON b.gid = o.gid JOIN orders m ON m.oid = o.oid WHERE u.username = ? AND m.vendor = ? AND g.role = 'mentor'")){
          $stmt->bind_param("ss",$username, $vendord);
          $stmt->execute();
          $stmt->bind_result($first, $last, $oid, $status, $timestamp, $budget, $quantity, $cost);

          $arr = array();

          while($stmt->fetch()){
            $arr["first"] = $first;
            $arr["last"] = $last;
            $arr["oid"] = $oid;
            $arr["status"] = $status;
            $arr["timestamp"] = $timestamp;
            $arr["budget"] = $budget;
            $arr["quantity"] = $quantity;
            $arr["cost"] = $cost;
            $flagged = true;
            $finArr[] = $arr;
          }
          $stmt->close();
          if ($flagged == false){
            if ($stmt = $mysqli->prepare("SELECT u.first_name, u.last_name, b.budget FROM users u JOIN volunteer r ON u.user_id = r.uid JOIN group_members g ON g.uid = r.vid JOIN group_budget b ON b.gid = g.gid WHERE u.username = ? AND g.role = 'mentor'")){
              $stmt->bind_param("s",$username);
              $stmt->execute();
              $stmt->bind_result($first, $last, $budget);

              $arr = array();

              while($stmt->fetch()){
                $arr["first"] = $first;
                $arr["last"] = $last;
                $arr["budget"] = $budget;
                $flagged = true;
                $finArr[] = $arr;
              }
              $stmt->close();
              if ($flagged == false){
                return false;
              }
              $finArr[0]["role"] = $roled;
              return $finArr;
            } else {
              echo $mysqli->error;
            }
          }
          $finArr[0]["role"] = $roled;
          return $finArr;
        } else {
          echo $mysqli->error;
        }
    } else if ($roled == "participant" || $roled == "mentor") {
        if ($stmt = $mysqli->prepare("SELECT u.first_name, u.last_name, o.oid, o.delivered, m.timestamp, b.budget, m.quantity, m.cost FROM users u JOIN registration r ON u.user_id = r.puid JOIN group_members g ON g.uid = r.id JOIN group_budget b ON b.gid = g.gid JOIN group_orders o ON b.gid = o.gid JOIN orders m ON m.oid = o.oid WHERE u.username = ? AND m.vendor = ? AND g.role = 'participant'")){
          $stmt->bind_param("ss",$username, $vendord);
          $stmt->execute();
          $stmt->bind_result($first, $last, $oid, $status, $timestamp, $budget, $quantity, $cost);

          $arr = array();

          while($stmt->fetch()){
            $arr["first"] = $first;
            $arr["last"] = $last;
            $arr["oid"] = $oid;
            $arr["status"] = $status;
            $arr["timestamp"] = $timestamp;
            $arr["budget"] = $budget;
            $arr["quantity"] = $quantity;
            $arr["cost"] = $cost;
            $flagged = true;
            $finArr[] = $arr;
          }
          $stmt->close();
          if ($flagged == false){
            if ($stmt = $mysqli->prepare("SELECT u.first_name, u.last_name, b.budget FROM users u JOIN registration r ON u.user_id = r.puid JOIN group_members g ON g.uid = r.id JOIN group_budget b ON b.gid = g.gid WHERE u.username = ? AND g.role = 'participant'")){
              $stmt->bind_param("s",$username);
              $stmt->execute();
              $stmt->bind_result($first, $last, $budget);

              $arr = array();

              while($stmt->fetch()){
                $arr["first"] = $first;
                $arr["last"] = $last;
                $arr["budget"] = $budget;
                $flagged = true;
                $finArr[] = $arr;
              }
              $stmt->close();
              if ($flagged == false){
                return false;
              }
              $finArr[0]["role"] = $roled;
              return $finArr;
            } else {
              echo $mysqli->error;
            }
          }
          $finArr[0]["role"] = $roled;
          return $finArr;
        } else {
          echo $mysqli->error;
        }
    } else if ($roled == "Lead Mentor") {
      $first = "";
      $last = "";
      if ($stmt = $mysqli->prepare("SELECT first_name, last_name FROM users WHERE username = ?")){
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($tfirst, $tlast);
        if ($stmt->fetch()) {
          $firstte = $tfirst;
          $lastte = $tlast;
        }
        $stmt->close();
      } else {
        echo $mysqli->error;
      }
        if ($stmt = $mysqli->prepare("SELECT o.oid, g.gid, o.timestamp, o.shipping FROM orders o JOIN group_orders g ON o.oid = g.oid WHERE o.vendor = ? AND g.delivered = 0 AND timestamp >= DATE_SUB(NOW(), INTERVAL 1 DAY)")){
          $stmt->bind_param("i", $vendord);
          $stmt->execute();
          $stmt->bind_result($oid, $gid, $time, $shipping);
          $arr = array();
          while($stmt->fetch()) {
            $arr["oid"] = $oid;
            $arr["gid"] = $gid;
            $arr["timestamp"] = $time;
            $arr["shipping"] = $shipping;
            $finArr[] = $arr;
          }
          $stmt->close();
          $mysqli->close();
          $finArr[0]["role"] = $roled;
          $finArr[0]["first"] = $firstte;
          $finArr[0]["last"] = $lastte;
          return $finArr;
        } else {
          echo $mysqli->error;
        }
    }
  }
}

?>