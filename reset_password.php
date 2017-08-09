<html>
  <head>
    <title>myPALBOTICS Portal</title>
    <link rel="stylesheet" type="text/css" href="site-resources/style/index.css">
    <link rel="stylesheet" type="text/css" href="site-resources/font/css/fontello.css">
    <link rel="shortcut icon" type="image/png" href="site-resources/images/favicon.png"/>
    <script src="site-resources/js/jquery.js"></script>
    <script src="site-resources/js/reset.js"></script>
    <script src="site-resources/js/mobileVerify.js"></script>
    <script src="site-resources/js/generalClassFunctions.js"></script>
    <link rel="stylesheet" href="site-resources/js/jquery-ui-1.11.4.custom/jquery-ui.min.css">
    <script src="site-resources/js/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
    <script>
      /*if (window.mobilecheck() == false && screen.height < screen.width) {
        $("link[rel=stylesheet]").attr({href : "site-resources/styles/index.css"});
      } else {
        $("link[rel=stylesheet]").attr({href : "site-resources/styles/mobile.css"});
      }*/
    </script>
  </head>
  <body>
    <div class = "opener">
      <div class = "verticle-middle">
        <image src = "site-resources/images/logo.png" class = "icon"></image>
        <div class = "title verticle-middle">
          <span class = "small" id = "info">Password Reset:</span>Please enter your reset code below<br><br><input type = "text" id = "reset-code" name = "reset-code" placeholder = "Password Reset Code" required /><button id = "reset-submit-btn" style = 'height:auto;padding:5px;' type="submit">Reset Password</button>
        </div>
      </div>
    </div>
    <div class = "footer">Copyright &copy;2016, Yonkers Police Athletic League, Contact us: director@palbotics.org</div>
  </body>
</html>
