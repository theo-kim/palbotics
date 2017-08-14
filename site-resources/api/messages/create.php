<?php

error_reporting(E_ALL);

require "../connectdb.php";
require "../mail.php";
require "../auth/get_permissions.php";

$dest = $_POST["destination"];
$src = $identity;
$subj = $_POST["subject"];
$mess = nl2br($_POST["message"]);

if (isset($dest) && isset($src) && isset($subj) && isset($mess) && isset($role)) {
    $query = "INSERT INTO messages (uid, message, subject, source)
                VALUES (?, ?, ?, ?)";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("issi", $dest, $mess, $subj, $src);
        $stmt->execute();
        $a = $mysqli->affected_rows;
        $stmt->close();
        if ($a > 0) {
            if ($stmt = $mysqli->prepare("SELECT email, first_name, last_name FROM users WHERE user_id = ?")) {
                $stmt->bind_param("i", $dest);
                $stmt->execute();
                $stmt->bind_result($email, $first, $last);
                if ($stmt->fetch()) {
                    $stmt->close();
                    $message = "<p>Hello,</p><p>This is an email alert that you have a new message in your myPALBOTICS portal account.  Please login to your account on our site: <a href = 'http://my.robotics.yonkers-pal.org'>my.robotics.yonkers-pal.org</a>.</p><p>Thank you and have a nice day.</p>" ;

                    if (email($email, $first, $last, "You have a new myPALBOTICS message", $message)) {
                        echo "success";
                    } else {
                        echo "Email Failure";
                    }
                } else {
                    echo "Bondage Failure";
                }
            } else {
                echo "fail";
            }
            $mysqli->close();
        } else {
            echo "Bondage Failure";
        }
    }
} else {
    echo "Missing Information";
}
