<?php

error_reporting(E_ALL);

function get_order_total($order){
  $running = 0;
  for($i = 0; $i < $order["length"]; $i++) {
    if (!isset($order[$i])) {
      continue;
    }
    $running += $order[$i]["quantity"] * $order[$i]["price"];
  }
  return $running;
}

include("connectdb.php");
$count = 0;
$flag  = false;
if (isset($_POST)) {

  $username = $_POST["user"];
  $order = $_POST["order"];
  $vendor = $_POST["vendor"];
  $shipping = $_POST["shipping"];
  $total = get_order_total($order) * $shipping;

  if ($stmt = $mysqli->prepare("INSERT INTO orders(quantity, cost, vendor, shipping) VALUES(?, ?, ?, ?)")){
    $stmt->bind_param("iiii",$order["l"], $total, $vendor, $shipping);
    $stmt->execute();
    $com = $mysqli->affected_rows;
    if ($com > 0) {
      $oid = $mysqli->insert_id;
      $stmt->close();
      for($i = 0; $i < $order["length"]; $i++) {
        if (!isset($order[$i])) {
          continue;
        }
        if ($stmt = $mysqli->prepare("INSERT INTO order_products(oid, prid, quantity) VALUES(?, ?, ?)")) {
          $stmt->bind_param("iii", $oid, $order[$i]["prid"], $order[$i]["quantity"]);
          $stmt->execute();

          if ($mysqli->affected_rows > 0) {
            $count++;
          } else {
            echo "failure to add product";
            $flag = true;
          }
          $stmt->close();
        } else {
          echo $mysqli->error;
        }
      }
      if ($flag == false) {
        if ($stmt = $mysqli->prepare("INSERT INTO group_orders (gid, oid, delivered) VALUES ((SELECT g.gid FROM group_members g JOIN registration r ON r.id = g.uid JOIN users u ON u.user_id = r.puid WHERE g.role = 'participant' AND u.username = ?),?,0)")){
          $stmt->bind_param("si", $username, $oid);
          $stmt->execute();

          if($mysqli->affected_rows > 0) {
            $count++;
          } else {
            echo "fail";
            $flag = true;
          }
          $stmt->close();
        } else {
          echo $mysqli->error;
        }
        if ($flag == false) {
          $total = get_order_total($order);
          if ($stmt = $mysqli->prepare("UPDATE group_budget SET budget = budget - ? WHERE gid  = (SELECT g.gid FROM group_members g JOIN registration r ON r.id = g.uid JOIN users u ON u.user_id = r.puid WHERE g.role = 'participant' AND u.username = ?)")){
            $stmt->bind_param("is", $total, $username);
            $stmt->execute();

            if($mysqli->affected_rows > 0) {
              $count++;
            } else {
              echo "fail update". $total;
              $flag = true;
            }
            $stmt->close();
          } else {
            echo $mysqli->error;
          }
        }
      }
      $mysqli->close();
    } else {
      echo $mysqli->error;
    }
  } else {
    echo $mysqli->error;
  }
} else {
  echo "No variables";
}

if ($count > 0 && $flag == false) {
  echo "success";
}
?>