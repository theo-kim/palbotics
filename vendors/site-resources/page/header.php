<div class = "banner">
  <h1><?=$title;?></h1>
  <h2><?=$description;?></h2>
</div>
<div class = "navigation">
  <a href = "./">Home</a>
  <a href = "./index.php?product=view_all">Products</a>
  <a onclick = "createModal('Contact Us', 'Hello and thank you for wanting to get in contact with <?=$title; ?>.  The following is information about contacting us:<br><br><b>Phone: </b>(903)-124-3934<br><b>Email: </b>info<?=$email; ?><br><br><?=$title;?>, Inc. <br>127 North Sunway<br>Rye, NY 10034 U.S.A');">Contact Us</a>
</div>
<div class = "account">
  <a class = "login">Login</a>
  <a class = "signup">Sign up</a>
  <a class = "cart"></a>
</div>