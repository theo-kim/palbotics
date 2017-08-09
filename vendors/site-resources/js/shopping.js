$(".cart").click(function() {
  createModal("Your Shopping Cart",viewCart());
  $(".trash").click(function(){
    removeItem($(this).attr("id"));
  });
  $('.checkout').click(function(){
    if (checkLogin() == true){
      $(".modal").remove();
      $(".filter").remove();
      createModal("Checkout Options", "<p>Please select your shipping preferences</p><input type = 'radio' id = 'ship-1'  name = 'radio' class = 'shipping' value = '1.00'><label for = 'ship-1'>Standard Shipping (24 hours, No additional cost)<span></span></label><br><input type = 'radio' id = 'ship-2' name = 'radio' class = 'shipping' value = '1.20'><label for = 'ship-2'>Rush Shipping (4 hours, +20% on total purchase)<span></span></label><br><input type = 'radio' id = 'ship-3' name = 'radio' class = 'shipping' value = '1.50'><label for = 'ship-3'>Super-Rush Shipping (30 minutes, +50% on total purchase)<span></span></label><button onclick='checkOut()' style = 'margin-top:20px'>Complete Order</button>");
      //checkOut();
      //alert("checkedout");
    } else {
      $(".modal").remove();
      $(".filter").remove();
      createModal("", "Please log in before checking out");
    }
  });
});
//console.log($(".product-div").html());
$(".addcart").click(function() {
  var prid = $(this).attr("data-prid");
  var pn = $(this).attr("data-pn");
  var price = $(this).attr("data-cost");
  var name = $(this).attr("data-name");
  var quantity = $("#"+prid).val();
  if (quantity == "" || quantity == 0) {
    createModal("","Please specify a quantity");
  } else {
    data = {prid:prid, quantity:quantity, name: name, price: price, pn:pn};
    addCart(data);
  }
});
$(".product-div").find("tr").not(".headerr").click(function() {
  var oidd = $("td:first", $(this)).text();
  getOrderDetails(oidd);
});

function assignCartClick(){
  $(".cart").click(function() {
    createModal("Your Shopping Cart",viewCart());
    $(".trash").click(function(){
      removeItem($(this).attr("id"));
    });
    $('.checkout').click(function(){
      if (checkLogin() == true){
      $(".modal").remove();
      $(".filter").remove();
      createModal("Checkout Options", "<p>Please select your shipping preferences</p><input type = 'radio' id = 'ship-1'  name = 'radio' class = 'shipping' value = '1.00'><label for = 'ship-1'>Standard Shipping (24 hours, No additional cost)<span></span></label><br><input type = 'radio' id = 'ship-2' name = 'radio' class = 'shipping' value = '1.20'><label for = 'ship-2'>Rush Shipping (4 hours, +20% on total purchase)<span></span></label><br><input type = 'radio' id = 'ship-3' name = 'radio' class = 'shipping' value = '1.50'><label for = 'ship-3'>Super-Rush Shipping (30 minutes, +50% on total purchase)<span></span></label><button onclick='checkOut()' style = 'margin-top:20px'>Complete Order</button>");
      //checkOut();
      //alert("checkedout");
    } else {
      $(".modal").remove();
      $(".filter").remove();
      createModal("", "Please log in before checking out");
    }
    });
  });
}
function viewCart(){
  if (localStorage.getItem("Cart") == null) {
    return "Sorry, you have no items in your shopping cart, add some by finding a product and adding it to your cart.";
  } else {
    var cart = JSON.parse(localStorage.getItem("Cart"));
    console.log(cart);
    var result = "<table><tr><th></th><th>Product Name</th><th></th><th>Unit Price</th><th>Total</th>";
    for (var i = 0; i < cart.length; i++) {
      if (typeof cart[i] == "undefined") {
        continue;
      }
      result+="<tr><td><image src = '../site-resources/images/garbage.png' class = 'trash' id = '"+i+"'></td><td><b>"+cart[i]["name"]+"</b><br>P/N: "+cart[i]["pn"]+"</td><td style = 'white-space:nowrap;'>x "+cart[i]["quantity"]+"</td><td>$"+cart[i]["price"]+"</td><td>"+(cart[i]["quantity"]*cart[i]["price"])+"</td></tr>";
    }
    result += "</table><button class = 'checkout' style = 'border-color:black;'>Checkout</button>";
    return result;
  }
}
function addCart(entry) {
  if (localStorage.getItem("Cart") == null) {
    call = {0: entry, length: 1, l: 1};
    localStorage.setItem("Cart",JSON.stringify(call));
    createModal("",entry["quantity"]+" item(s) added to your cart successfully");
  } else {
    //localStorage.removeItem("Cart");
    
    var existing = JSON.parse(localStorage.getItem("Cart"));
    var length = existing.length;
    existing["length"] += 1;
    existing["l"] += 1;
    existing[length] = entry;
    localStorage.setItem("Cart",JSON.stringify(existing));
    createModal("",entry["quantity"]+" item(s) added to your cart successfully");
  }
}
function removeItem(id) {
  var existing = JSON.parse(localStorage.getItem("Cart"));
  delete existing[id];
  if (existing["l"] == 1) {
    localStorage.removeItem("Cart");
    $(".modal-content").empty();
    $(".modal-content").html(viewCart());
  } else {
    var base = id + 1;
    //alert(existing["length"]);
    existing["l"] -= 1;
    localStorage.setItem("Cart",JSON.stringify(existing));
    
    $(".modal-content").empty();
    $(".modal-content").html(viewCart());
  }
}

function checkOut(){
  var order = JSON.parse(localStorage.getItem("Cart"));
  var usernamed = JSON.parse(localStorage.getItem("User")).username;
  var vendor = $(".vendor").val();
  var shipping = $(".shipping").val();
  var postData = {order: order, user: usernamed, vendor: vendor, shipping: shipping};
  //console.log(postData);
  $.ajax({
      url: "../site-resources/api/create_order.php",
      type: "POST",
      data: postData,
      success: function(data) {
        console.log(data);
        if (data == "success") {
          localStorage.removeItem("Cart");
          $(".modal").remove();
          $(".filter").remove();
          createModal("", "Purchase Successful, please check your account for order status");
        } else {
          alert("fail");
          //console.log(data);
        }
      }, 
      error: function(data) {
        console.log(data);
      }
  });
}

function getOrderDetails(oid){
  var postData = {oid:oid};
  $.ajax({
    url:"../site-resources/api/get_order_details.php",
    type: "POST",
    data: postData,
    dataType: "JSON",
    success: function(data) {
      var string = "<table>";
      for(var i = 0; i < data.length; i++) {
        string+="<tr><td>"+data[i]['name']+"<br><span style = 'font-size:16px'>P/N "+data[i]['pn']+"</span></td><td>$"+data[i]['price']+" x "+data[i]['quantity']+" <b>$"+(data[i]['quantity']*data[i]['price'])+"</b></td></tr>";
      }
      string+="</table>";
      createModal("Order Details: Order "+oid,string);
      console.log(data);
    },
    error: function(data) {
      alert(data.responseText);
    }
  });
}