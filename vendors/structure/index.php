<?php
$name = "structure";
$css = "structure.css";
$title = "Macolyte-Trucc";
$email = "@macolyte.com";
$description = "Over 100 products with quality and quick service.";
$featured_title = "C-Channel, 1x2x1x15 hole";
$featured_description = "Give your robot a backbone with this staple of strucutre.  The VEX C-channel is made of thick aluminum and can withstand even the hardest hit. Arguably the most versatile part of your robotics part arsenal.";
$featured_pid = 1;
$vendor = 3;
$slogan = "<span style = 'display:none'>Welcome to MacolyteTruckk</span>";

if (isset($_GET["product"])) {
  $product = $_GET["product"];
  if ($product == "view_all") {
    include("../site-resources/api/connectdb.php");
    include("../site-resources/api/get_products.php");
    
    $products = array();
    $products = get_products(-1, $mysqli, $name);
    
    echo "<html>";
    include("../site-resources/page/head.php");
    echo "<body>";
    include("../site-resources/page/header.php");
    echo "<div class = 'product-content'>";
    echo "<div class = 's'><h2>All Products</h2><hr></div>";
    for($i = 0; $i < count($products); $i++) {
      if ($products[$i]["available"] != 0) {
        $status = "In stock";
      }
      echo "<div class = 'product-entry'><div class = 'left-float'><h2><a href = './index.php?product=".$products[$i]["prid"]."'>".$products[$i]["name"]."</a></h2><div class = 'des'>".$products[$i]["description"]."</div></div><div class ='right-float'><div class = 'info'><b>P/N: ".$products[$i]["pn"]."</b><br>Price: $".$products[$i]["price"]."<br><span style = 'color:green;font-weight:bold;'>".$status."</span></div><br><input type = 'number' id = '".$products[$i]["prid"]."' min = '0' style = 'width: 50px;height:30px;padding:5px;'/><button class = 'addcart' data-prid = '".$products[$i]["prid"]."' data-name = '".$products[$i]["name"]."' data-cost = '".$products[$i]["price"]."' data-pn = '".$products[$i]["pn"]."'>Add to Cart</button></div></div>";
    }
    echo "</div>";
    include("../site-resources/page/footer.php");
    echo "</body>";
    echo "</html>";
  } else {
    include("../site-resources/api/connectdb.php");
    include("../site-resources/api/get_products.php");
    
    $products = array();
    $products = get_products($product, $mysqli, $name);
    echo "<html>";
    include("../site-resources/page/head.php");
    echo "<body>";
    include("../site-resources/page/header.php");
    echo "<div class = 'product-content'>";
    
    if ($products[0]["available"] != 0) {
      $status = "In stock";
    } else {
      $status = "Out of stock";
    }
    
    echo "<div class = 'product-div'><h2>".$products[0]["name"]."</h2><hr><p>".$products[0]["description"]."</p><b>P/N: ".$products[0]["pn"]."</b><br>Price: $".$products[0]["price"]."<br><span style = 'color:green;font-weight:bold;'>".$status."</span><br><input type = 'number' id = '".$products[0]["prid"]."' min = '0' style = 'width: 100px;height:50px;padding:10px;font-size:18px;'/><button class = 'addcart' data-prid = '".$products[0]["prid"]."' data-name = '".$products[0]["name"]."' data-cost = '".$products[0]["price"]."' data-pn = '".$products[0]["pn"]."'>Add to Cart</button></div>";
    
    echo "</div>";
    include("../site-resources/page/footer.php");
    echo "</body>";
    echo "</html>";
  }
} else if (isset($_GET["account"])){
  include "../site-resources/api/connectdb.php";
  include "../site-resources/api/get_account_info.php";
  
  $account = get_account_info($_GET["account"], $mysqli, $vendor);
  
  echo "<html>";
  include "../site-resources/page/head.php";
  echo "<body>";
  include "../site-resources/page/header.php";
  
  echo "<div class = 'product-content'>";
  echo "<div class = 'product-div'><h2>Account Page: <b>".$account[0]["first"]." ".$account[0]["last"]."</b></h2>Username: <b>".$_GET["account"]."</b><br>Account Provider: <b>Yonkers PALBOTICS STEM Education (Yonkers, NY, USA)</b><br><p>Welcome to your account page with ".$title." we are glad to have you as a customer. Please use the options below to manage your available balance with us, your current and pending orders, and view your past orders as well.</p><hr>";
  
  if ($account[0]["role"] != 'Lead Mentor'){
    echo "<h3>Current Balance:</h3><span style = 'font-size:30px;display:inline-block;padding:10px; border:2px solid #000;'>$".$account[0]['budget']."</span><p>Please note that this balance is the balance we have retreived from your account provider.  This balance is subject to change based on other purchases made through your account provider.  All overdue balances should be paid out within a month of the last purchase made.</p><hr>";
  }
  
  echo "<h3>Order History</h3><p>This is a history of your past and pending orders.  For more information about each order, please click on the row of the specific order.</p>";
  
  if (isset($account[0]["oid"]) && $account[0]["role"] == "participant"){
    echo "<table style = 'font-size:20px'><tr class = 'headerr'><th>Order ID</th><th>Date Ordered</th><th>Total Items</th><th>Total Cost</th></tr>";
    foreach ($account as $a) {
      echo "<tr><td>".$a["oid"]."</td><td>".$a["timestamp"]."</td><td>".$a["quantity"]."</td> <td>$".$a["cost"]."</td></tr>";
    }
    echo "</table>";
  } else if (isset($account[0]["oid"]) && $account[0]["role"] == "Lead Mentor"){
    echo "<table style = 'font-size:20px'><tr class = 'headerr'><th>Order ID</th><th>Date Ordered</th><th>Group</th></tr>";
    foreach ($account as $a) {
      echo "<tr><td>".$a["oid"]."</td><td>".$a["timestamp"]."</td><td>".$a["gid"]."</td></tr>";
    }
    echo "</table>";
  } else {
    echo "<span style = 'font-size:20px;font-weight:bold;'>No orders have been made yet</span>";
  }
  echo "</table>";
  echo "</div></div>";
  include "../site-resources/page/footer.php";
  echo "</body></html>";
  
} else {
  include("../site-resources/page/home.php"); 
}

?>