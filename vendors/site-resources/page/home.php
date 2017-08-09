<html>
  <head>
    <title><?=$title;?></title>
    <link rel="stylesheet" type="text/css" href = "../site-resources/css/<?=$css;?>">
  </head>
  <body>
    <div class = "banner">
      <h1><?=$title;?></h1>
      <h2><?=$description;?></h2>
    </div>
    <div class = "navigation">
      <a href = "./index.php?product=view_all">Products</a>
      <a onclick = "createModal('Contact Us', 'Hello and thank you for wanting to get in contact with <?=$title; ?>.  The following is information about contacting us:<br><br><b>Phone: </b>(903)-124-3934<br><b>Email: </b>info<?=$email; ?><br><br><?=$title;?>, Inc. <br>127 North Sunway<br>Rye, NY 10034 U.S.A');">Contact Us</a>
    </div>
    <div class = "account">
      <a class = "login">Login</a>
      <a class = "signup">Sign up</a>
      <a class = "cart"></a>
    </div>
    <div class = "content">
      <?=$slogan; ?>
      <div class = "box">
      <div class = "column-30">
        <h2>Featured Product</h2>
        <hr>
        <h3><?=$featured_title;?></h3>
        <p>
          <?=$featured_description;?>
        </p>
        <button onclick="window.location='./index.php?product=<?=$featured_pid; ?>'">View Product Details</button>
      </div>
      <div class = "column-40">
        <image src = "../site-resources/images/products/<?=$featured_pid; ?>.jpg"></image>
      </div>
                </div>

      <div class = "view">
        <image src = "../site-resources/images/<?=$name; ?>-stem.jpg"></image>
        <div class = "column-30">
          <h2>Serving our community</h2>
          <hr>
          <p>
            <?=$title;?> is dedicated to serving our community with the utmost respect and dedication to awareness of social issues and towards the development of the next generation of innovators, explorers and researchers.  In line with this principle we sponsor numerous local and national robotics and STEM programs and are proud to do it!
          </p>
          <button onclick = "createModal('','Please contact your site coordinator for sponsorship opportunities')">Get Sponsored</button>
        </div>
      </div>
    </div>
    <div class = "footer">
      <div class = "column-1">
        <h3>PRODUCT</h3>
        <a>Check order status</a><br>
        <a>Order by part number</a><br>
        <a>Payment and Order Information</a>
      </div>
      <div class = "column-1">
        <h3>SUPPORT</h3>
        <a>Community forums</a><br>
        <a>Manuals and Tutorials</a><br>
        <a>Schematics and Diagrams</a>
        <a>Troubleshooting</a>
      </div>
      <div class = "column-1">
        <h3>COMPANY</h3>
        <a>About <?=$title;?></a><br>
        <a>Events</a><br>
        <a>Careers</a>
      </div>
      <div class = "column-2">Copyright &copy; 2016 <?=$title;?> Inc.</div>
    </div>
    <input type = "hidden" value = "<?=$vendor;?>" class = "vendor">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src = "../site-resources/js/modal.js"></script>
    <script src = "../site-resources/js/login.js"></script>
    <script src = "../site-resources/js/shopping.js"></script>
  </body>
</html>