<?php

error_reporting(E_ALL);

include("site-resources/api/connectdb.php");
$count = 1;
echo "<html><head><title>PALBOTICS Standings</title><link rel = 'stylesheet' type = 'text/css' href = 'site-resources/style/standings.css'><script src='site-resources/js/dependencies/jquery/jquery.js''></script>
<script src = 'site-resources/js/standings.js'></script></head><body>";
echo "<h1  style = 'margin:40px;'>Prime Division</h1><table id = 'prime' style = 'color:white;'>";
if ($stmt = $mysqli->prepare("SELECT DISTINCT p.gid, p.points, m.progression, i.name, i.slogan FROM group_points p JOIN `group` g ON g.gid = p.gid JOIN programs q ON g.pid = q.pid JOIN (SELECT gid, MAX(progression) AS maxAmount FROM group_mission GROUP BY gid) as max ON max.gid = g.gid JOIN group_mission m ON m.gid = g.gid JOIN group_info i ON i.gid = p.gid WHERE q.age = 'PALBOTICS Prime' AND m.progression = max.maxAmount AND q.start <= DATE_ADD(NOW(), INTERVAL 1 DAY) AND q.end >= NOW() ORDER BY points DESC")){
  $stmt->execute();
  $stmt->bind_result($gid, $points, $mission, $name, $slogan);
  while($stmt->fetch()){
    echo "<tr><td>Rank ".$count."</td><td>Team: ".$gid."</td><td>".$name."<br><span style = 'font-size:16px;'>".$slogan."</span></td><td>Points: ".$points."<br><span style = 'font-size:16px;'>Missions Completed: ".$mission."</td> </tr>";
    $count++;
  }
} else {
  echo $mysqli->error;
}
echo "</table>";

$count = 1;
echo "<h1 style = 'margin:40px;'>Boost Division</h1><table id = 'boost'>";
if ($stmt = $mysqli->prepare("SELECT DISTINCT p.gid, p.points, m.progression, i.name, i.slogan FROM group_points p JOIN `group` g ON g.gid = p.gid JOIN programs q ON g.pid = q.pid JOIN (SELECT gid, MAX(progression) AS maxAmount FROM group_mission GROUP BY gid) as max ON max.gid = g.gid JOIN group_mission m ON m.gid = g.gid JOIN group_info i ON i.gid = p.gid WHERE q.age = 'PALBOTICS Boost' AND m.progression = max.maxAmount AND q.start <= DATE_ADD(NOW(), INTERVAL 1 DAY) AND q.end >= NOW() ORDER BY points DESC")){
  $stmt->execute();
  $stmt->bind_result($gid, $points, $mission, $name, $slogan);
  while($stmt->fetch()){
    echo "<tr><td>Rank ".$count."</td><td>Team: ".$gid."</td><td style = 'max-width:400'>".$name."<br><span style = 'font-size:16px;'>".$slogan."</span></td><td>Points: ".$points."<br><span style = 'font-size:16px;'>Missions Completed: ".$mission."</td></tr>";
    $count++;
  }
} else {
  echo $mysqli->error;
}
echo "</table></body></html>";

?>