loginButtonAssign();
signupButtonAssign()

if (checkLogin() == true){
  replaceAccount(1);
} else if (getUrlParameter("account") != false){
  window.location = "./";
}

function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    } 
  return false;
};

function accountButtonAssign() {
  $(".accountm").click(function(){
    var usd = JSON.parse(localStorage.getItem("User"));
    console.log()
    window.location = "./index.php?account="+usd.username;
  });
}

function signupButtonAssign(){
  $(".signup").click(function() {
    createModal("","Sorry, we are no longer accepting requests for new accounts.  Please check back later");
  });
}

function loginButtonAssign() {
  $(".login").click(function() {
    createModal("Account Login","<div class = 'container'><label for='username'>Username</label><br><input type = 'text' id = 'username' placeholder = 'Username'/><label for='password'>Password</label><br><input id = 'password' type = 'password' placeholder = 'Password'/></div><div class = 'container description'>Please login to view your available balance, purchase history, and pending orders.<br> <button class = 'loginb'>Login</button></div>");
    $(".loginb").click(function(){
      if (login($("#username").val(),$("#password").val()) == true){
        var user = {username:$("#username").val()};
        localStorage.setItem("User",JSON.stringify(user));
        replaceAccount();
        $(".modal").remove();
        $(".filter").remove();
        createModal("", "Login Successful");
      } else {
        $(".modal").remove();
        $(".filter").remove();
        createModal("", "Login failed");
      } 
    });
  });
}
function replaceAccount(direction) {
  if (direction == 0) {
    $(".account").empty().html("<a class = 'login'>Login</a><a class = 'signup'>Sign up</a><a class = 'cart'></a>");
    assignCartClick();
    loginButtonAssign();
    signupButtonAssign();
  } else if (direction == 1) {
    $(".account").empty().html("<a class = 'accountm'>Account</a><a class = 'logout'>Logout</a><a class = 'cart''></a>");
    accountButtonAssign();    
    $(".logout").click(function(){
      localStorage.removeItem("User");
      replaceAccount(0);
      if (getUrlParameter("account") != false){
        createModal("","Account Logged Out, you will be redirected automatically");
        setTimeout(function(){
          window.location="./";  
        }, 1500);
      } else {
        createModal("", "Account Logged Out");
      }
    });
  } else {
    $(".account").empty().html("<a class = 'accountm'>Account</a><a class = 'logout'>Logout</a><a class = 'cart''></a>");
    accountButtonAssign(); 
    assignCartClick();
    $(".logout").click(function(){
      localStorage.removeItem("User");
      replaceAccount(0);
      if (getUrlParameter("account") != false){
        createModal("","Account Logged Out, you will be redirected automatically");
        setTimeout(function(){
          window.location="./";  
        }, 1500);
      } else {
        createModal("", "Account Logged Out");
      }
    });
  }
}

function checkLogin() {
  if (localStorage.getItem("User") == null) {
    return false;
  } else {
    return true;
  }
}

function login(username, password) {
  var postData = {username: username, password:password};
  var loginFlag = false;
  
  $.ajax({
      url: "../site-resources/api/login.php",
      type: "POST",
      data: postData,
      async: false,
      success: function(data) {
        console.log(data);
        if (data == "success") {
          loginFlag = true;
        } else {
          loginFlag = false;
        }
      }, 
      error: function(data) {
        console.log(data);
      }
  });
  
  if (loginFlag == true) {
    return true;
  } else {
    return false;
  }
  
  return false;
}
