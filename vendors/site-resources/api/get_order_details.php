<?php 

error_reporting(E_ALL);
include("connectdb.php");

if (isset($_POST["oid"])){
  $oid = $_POST["oid"];
  if ($stmt = $mysqli->prepare("SELECT o.prid, o.quantity, p.name, p.pn, p.price FROM order_products o JOIN products p ON p.prid = o.prid WHERE o.oid = ?")){
    $stmt->bind_param("i", $oid);
    $stmt->execute();
    $stmt->bind_result($prid, $quantity, $name, $pn, $price);
    $arr = array();
    $finArr = array();
    while($stmt->fetch()) {
      $arr["prid"] = $prid;
      $arr["quantity"] = $quantity;
      $arr["name"] = $name;
      $arr["pn"] = $pn;
      $arr["price"] = $price;
      
      $finArr[] = $arr;
    }
    $stmt->close();
    echo json_encode($finArr);
  } else {
    echo $mysqli->error;
  }
} else {
  echo "No vars";
}

?>