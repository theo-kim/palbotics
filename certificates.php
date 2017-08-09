<?php

error_reporting(E_ALL);

include("site-resources/api/connectdb.php");

if ($stmt = $mysqli->prepare("SELECT a.*, r.first, r.last FROM awards a JOIN registration r ON r.id = a.uid JOIN programs p ON p.pid = r.pid WHERE p.start <= DATE_ADD(NOW(), INTERVAL 1 DAY) AND p.end >= NOW()")){
  $stmt->execute();
  $stmt->bind_result($aid, $uid, $name, $explaination, $palbotics, $first, $last);
  $count = 0;
  $arr = array();
  $finArr = array();
  while($stmt->fetch()) {
    $arr["name"] = $name;
    $arr["explaination"] = $explaination;
    $arr["title"] = $first." ".$last;
    $finArr[] = $arr;
    $count++;
  } 
  if ($count == 0){
    $finArr[0]["name"] = "Test Award";
    $finArr[0]["explaination"] = "This award is appearing because you either A) have not currently running programs, or B) have not received any award submissions from your mentors.  Please instruct mentors to fill out award submission in their Team Management Section.";
    $finArr[0]["title"] = "Lorem Ipsum";
    $finArr[1]["name"] = "Test Award 2";
    $finArr[1]["explaination"] = "This award is appearing because you either A) have not currently running programs, or B) have not received any award submissions from your mentors.  Please instruct mentors to fill out award submission in their Team Management Section.";
    $finArr[1]["title"] = "Lorem Ipsum Dos";
  }
} else {
  echo $mysqli->error;
}

?>

<html>
  <head>
    <title>Certificates</title>
    <style>
      @media print{
        @page{
          margin:0;
        }
        body {
          margin-left:1in;
          margin-right:1in;
          margin-top:0in;
          margin-bottom:0in;
          font-family: sans-serif;
        }
        h1 {
          font-size: inherit;
          display: inline;
        }
        #logo{
          width: 100%;
          text-align: center;
          position:relative;
          top:50%;
          transform: translateY(-60%);
          width:100%;
          line-height: 1.5em;
          font-size: 20pt;
        }
        #logo img{
          width: 1.5in;
        }
        #sign{
          position: absolute;
          bottom:0.5in;
          font-size:20pt;
        }
        .container{
          width: 100%;
          position: relative;
          height:100%;
          page-break-after: always;
        }
        .print{
          display:none;
        }
      }
      @media screen{
        .print{
          position: fixed;
          top:25px;
          left:25px;
          height:50px;
          width:100px;
        }
        @page{
          margin:0;
        }
        body {
          margin-left:1in;
          margin-right:1in;
          margin-top:0in;
          margin-bottom:0in;
          font-family: sans-serif;
        }
        h1 {
          font-size: inherit;
          display: inline;
        }
        #logo{
          width: 100%;
          text-align: center;
          position:relative;
          top:50%;
          transform: translateY(-60%);
          width:100%;
          line-height: 1.5em;
          font-size: 20pt;
        }
        #logo img{
          width: 1.5in;
        }
        #sign{
          position: absolute;
          bottom:0.5in;
          font-size:20pt;
        }
        .container{
          width: 100%;
          position: relative;
          height:100%;
          page-break-after: always;
        }
      }
    </style>
    <script>
      
    </script>
  </head>
  <body>
    <div class = "print"><button onclick = "window.print();">Print Page</button></div>
    <?php
    foreach($finArr as $f) {
      echo '<div class = "container"><div id = "logo"><image src = "site-resources/images/logo.png"></image><br><span style = "font-size:inherit;">In commendation of </span><h1>'.$f["title"].'\'s</h1> <span style = "font-size:inherit;">achievements, we present you with the commendation of </span><br><span style = "font-size:30pt;font-weight:bold;">'.$f["name"].'</span> <br>'.$f["explaination"].'</div>
    <div id = "sign">Program Director: ____________________________<br><br>Mentor: ____________________________</div></div>';
    }
    ?>
  </body>
</html>