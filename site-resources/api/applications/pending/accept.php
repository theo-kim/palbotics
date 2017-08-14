<?php

error_reporting(E_ALL);

require "../../connectdb.php";
require "../../mail.php";
require "../../auth/get_permissions.php";

$id = $_POST["id"];

if (isset($id) && $role == 0) {
    $origin = "password";
    $pass = "";
    $pass_hashed = "";
    $key = rand(10, 100);
    $role = "parent";

    $flag = false;

    for ($i = 0; $i < $key; $i++) {
        $origin = md5(sha1($origin));
    }

    $pass = substr($origin, 0, 5);
    $pass_hashed = md5($pass);

    $query = "SELECT user_id
                FROM users
                WHERE email = (SELECT email FROM registration WHERE id = ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($infod);

    if ($stmt->fetch()) {
        $ai = $infod;
        $flag = true;
        $a = 1;
        $stmt->close();
    } else {
        $query = "INSERT INTO users (username, password_hash, role)
                    VALUES (?, ?, ?)";
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param("sss", $id, $pass_hashed, $role);
            $stmt->execute();
            $a = $mysqli->affected_rows;
            $ai = $mysqli->insert_id;
            $stmt->close();

            if ($a == 0) {
                http_response_code(400);
                echo "Could not create new user";
            }
        }
    }

    if ($a > 0) {
        $stmt = $mysqli->prepare("UPDATE registration SET uid = ? WHERE id = ?");
        $stmt->bind_param("ii", $ai, $id);
        $stmt->execute();
        $b = $mysqli->affected_rows;

        $stmt->close();

        if ($b > -1) {
            $stmt = $mysqli->prepare("UPDATE users JOIN registration ON users.user_id = registration.uid  SET users.email = registration.email, users.first_name = registration.first_parent, users.last_name = registration.last_parent");

            $stmt->execute();
            $c = $mysqli->affected_rows;

            $stmt->close();

            if ($c > 0 || $flag == true) {
                $stmt = $mysqli->prepare("UPDATE registration SET status = 1 WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $d = $mysqli->affected_rows;

                $stmt->close();

                if ($d > 0) {
                    $stmt = $mysqli->prepare("SELECT email, first, last FROM registration WHERE id = ?");
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
                        $message = "<p>Hello,</p><p>This email is a <b>confirmation of your child, ".$first."   ".$last."s acceptance into the PALBOTICS
              program</b>. However, we require some moreinformation prior to the program start date.  Please login to your myPALBOTICS portal
              page and update said information and confirm your continued interest in the program.</p>  <p>Your myPALBOTICS portal can be found at
              <a href =  'http://my.robotics.yonkers-pal.org'>http://my.palbotics.org</a> using the following login   information:</p>
              <ul><li><b>Username: </b>".$username."</li><li><b>Password:</b> ".$pass." </li></ul>
              <p>If you have any questions, please feel free to email robotics#yonkers-pal.org  or call (914)-309-9934.
              Thank you and have a great day!</p>";
                    } else {
                        $message = "<p>Hello,</p>
              <p>This email is a <b>confirmation of your child, ".$first."   ".$last."s acceptance into the PALBOTICS
              program</b>. However, we require some more information prior to the program start date.  Please login to your myPALBOTICS portal
              page and update said information and confirm your continued interest in the program.</p>
              <p>Your myPALBOTICS portal can be found at <a href = 'http://my.robotics.yonkers-pal.org'>http://my.robotics.yonkers-pal.org</a>.</p>
              <p>Our records show that there is already an account associated with this email address,".$email.".  Please login to your account using
              the username <b>".$username."</b>, with the password that you were provided in either a previous email or that you created upon your
              last login.  If you do not remember this password, please use the 'forgotten password' link on the login page.</p>
              <p>If you have any questions, please feel free to email robotics@yonkers-pal.org  or call (914)-309-9934.
              Thank you and have a great day!</p>";
                    }
                    $subject = "Your Registration for PALBOTICS has been processed!";
                    if (email($email, $first, $last, $subject, $message)) {
                        echo "success";
                    } else {
                        http_response_code(400);
                        echo "Email failed to send";
                    }
                } else {
                    http_response_code(400);
                    echo "Failed to update registration status";
                }
            } else {
                http_response_code(400);
                echo "Failed to update user profile";
            }
        } else {
            http_response_code(400);
            echo "Failed to update registration user";
        }
    } else {
        http_response_code(400);
        echo "Failed to identify or create user";
    }
} else {
    http_response_code(400);
    echo "No Variables";
}
