<?php

error_reporting(E_ALL);

include "connectdb.php";
include "mail.php";

if (isset($_GET["vid"])) {
    $query = "SELECT pid FROM volunteer WHERE vid = ?";
    if ($stmt = $mysqli->prepare($query)){
      $stmt->bind_param("i", $_GET["vid"]);
      $stmt->execute();
      $stmt->bind_result($pid);
      if ($stmt->fetch()) {
          echo $pid;
      } else {
          echo "fail";
      }
  } else {
      echo "fail";
  }
} else if (isset($_POST["pid"]) && isset($_POST["vid"])){
  $pid = $_POST["pid"];
  $id = $_POST["vid"];

  $origin = "password";
  $pass = "";
  $pass_hashed = "";
  $key = rand(10, 100);
  $role = "mentor";

  $flag = false;

  for ($i = 0; $i < $key; $i++) {
    $origin = md5(sha1($origin));
  }

  $pass = substr($origin, 0, 5);
  $pass_hashed = md5($pass);

  $stmt = $mysqli->prepare("SELECT user_id FROM users WHERE email = (SELECT email FROM volunteer WHERE vid = ?)");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $stmt->bind_result($infod);

  if ($stmt->fetch()) {
    $ai = $infod;
    $flag = true;
    $a = 1;
    $stmt->close();
  } else {
    if ($stmt = $mysqli->prepare("INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)")) {
      $stmt->bind_param("sss", $id, $pass_hashed, $role);
      $stmt->execute();
      $a = $mysqli->affected_rows;
      $ai = $mysqli->insert_id;
      $stmt->close();

      if ($a == 0) {
        echo "Could not create new user";
      }
    } else {
      echo $mysqli->error;
    }
  }

  if ($a > 0) {
    $stmt = $mysqli->prepare("UPDATE volunteer SET uid = ?, pid = ?, status = 0 WHERE vid = ?");
    $stmt->bind_param("iii", $ai, $pid, $id);
    $stmt->execute();
    $b = $mysqli->affected_rows;

    $stmt->close();

    if ($b > -1) {
      $stmt = $mysqli->prepare("UPDATE users JOIN volunteer ON users.user_id = volunteer.uid  SET users.email = volunteer.email, users.first_name = volunteer.first, users.last_name = volunteer.last");

      $stmt->execute();
      $c = $mysqli->affected_rows;

      $stmt->close();

      if ($c > 0 || $flag == true) {
        $stmt = $mysqli->prepare("UPDATE volunteer SET status = 1 WHERE vid = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $d = $mysqli->affected_rows;

        $stmt->close();

        if ($d > 0) {
          $stmt = $mysqli->prepare("SELECT email, first, last FROM volunteer WHERE vid = ?");
          $stmt->bind_param("i", $id);
          $stmt->execute();
          $stmt->bind_result($email, $first, $last);
          $stmt->fetch();
          $stmt->close();

          $username = substr($email, 0, strpos($email, '@'));

          $stmt = $mysqli->prepare("UPDATE users SET username = ? WHERE user_id = ?");
          $stmt->bind_param("si", $username, $ai);
          $stmt->execute();
          $stmt->close();

          if ($flag == false) {
            $message = "<p>Hello, ".$first."   ".$last."</p>
            <p>This email contains instructions on how to login into your myPALBOTICS protal page to access informatioon about your
            volunteer requirements and schedule for the 2016 PALBOTICS Camp.</p>
            <p>Your myPALBOTICS portal can be found at <a href =  'http://my.robotics.yonkers-pal.org'>http://my.robotics.yonkers-pal.org</a> using the following
            login information:</p><ul><li><b>Username: </b>".$username."</li><li><b>Password:</b> ".$pass." </li></ul>
            <p>If you have any questions, please feel free to email robotics@yonkers-pal.org  or call (914)-309-9934.
            Thank you and have a great day!</p>";
          } else {
            $message = "<p>Hello, ".$first."   ".$last."</p>
            <p>This email contains instructions on how to login into your myPALBOTICS protal page to access informatioon about your
            volunteer requirements and schedule for the 2016 PALBOTICS Camp.</p>
            <p>Your myPALBOTICS portal can be found at <a href =  'http://my.robotics.yonkers-pal.org'>http://my.robotics.yonkers-pal.org</a>.</p>
            <p>According to our records, there is already an account associated with this email address. Please login to your account
            using the username <b>".$username."</b> and the passwords either sent to you in a previous email or that you defined during your
            last login.  If you have misplaced your password, you can use the 'forgotten password' link on the login page.</p>
            <p>If you have any questions, please feel free to email robotics@yonkers-pal.org  or call (914)-309-9934.
            Thank you and have a great day!</p>";
          }

          if (email($email, $first, $last, "Volunteering with Yonkers PALBOTICS", $message)) {
              echo "success";
          } else {
            echo "Email failed to send";
          }
        } else {
          echo "Failed to update registration status";
        }
      } else {
        echo "Failed to update user profile";
      }
    } else {
      echo "Failed to update registration user";
    }
  } else {
    echo "Failed to identify or create user".$a;
  }
} else if (isset($_POST["gid"]) && isset($_POST["vid"])) {
  $pid = $_POST["pid"];
  $vid = $_POST["vid"];

} else {
  echo "No variables set";
}

//$email = "tgim9@yahoo.com";

?>