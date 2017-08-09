<?php 

error_reporting(E_ALL);

function get_products($pridd, $mysqli, $categorie) {
  if ($pridd == -1) {
    if ($stmt = $mysqli->prepare("SELECT * FROM products WHERE category = ?")){
      $stmt->bind_param("s",$categorie);
      $stmt->execute();
      $stmt->bind_result($prid, $name, $description, $pn, $price, $quantity, $available, $category);
      
      $arr = array();
      $finArr = array();
      
      while($stmt->fetch()){
        $arr["prid"] = $prid;
        $arr["name"] = $name;
        $arr["description"] = $description;
        $arr["pn"] = $pn;
        $arr["price"] = $price;
        $arr["quantity"] = $quantity;
        $arr["available"] = $available;
        $arr["category"] = $category;
        
        $finArr[] = $arr;
      }
      return $finArr;
    }
  } else {
    if ($stmt = $mysqli->prepare("SELECT * FROM products WHERE category = ? AND prid = ?")){
      $stmt->bind_param("si", $categorie, $pridd);
      $stmt->execute();
      $stmt->bind_result($prid, $name, $description, $pn, $price, $quantity, $available, $category);
      
      $arr = array();
      $finArr = array();
      
      while($stmt->fetch()){
        $arr["prid"] = $prid;
        $arr["name"] = $name;
        $arr["description"] = str_replace(chr(169),"&reg;",nl2br($description));
        $arr["pn"] = $pn;
        $arr["price"] = $price;
        $arr["quantity"] = $quantity;
        $arr["available"] = $available;
        $arr["category"] = $category;
        
        $finArr[] = $arr;
      }
      return $finArr;
    }
  }
}

?>