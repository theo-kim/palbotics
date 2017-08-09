<?php

require_once "vendor/autoload.php";

function email ($email, $first, $last, $subject, $message)
{
    $mail = new PHPMailerOAuth;
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->Debugoutput = 'html';
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 465;
    $mail->SMTPSecure = 'ssl';
    $mail->SMTPAuth = true;
    $mail->AuthType = 'XOAUTH2';

    $mail->oauthUserEmail = "robotics@yonkers-pal.org";
    $mail->oauthRefreshToken = "1/chUPVKVZo5P_FGjiI0QutIpTT1psRDc_-Soh5w2rLd8";
    $mail->oauthClientId = "898195590233-3dl7b33fcifcvvfvnhl5v0n3jf600u3s.apps.googleusercontent.com";
    $mail->oauthClientSecret = "DiCoft5ATeFpLoU54IMuJCjl";

    $mail->setFrom("robotics@yonkers-pal.org", "Yonkers PALBOTICS");

    $mail->addAddress($email, $first." ".$last);
    $mail->AddBCC("robotics@yonkers-pal.org", "Yonkers PALBOTICS");
    $mail->Subject = $subject;
    $mail->isHTML(true);
    $mail->Body = $message ;
    
    $result = !$mail->send();
    if ($result) {
        echo "\nMailer Error: " . $mail->ErrorInfo;
        return false;
    } else {
        return true;
    }
}
?>