<?php

error_reporting(E_ALL);

require "../connectdb.php";

if (isset($_POST["hash"])) {
    $reset = md5($_POST["hash"]);

    if ($stmt = $mysqli->prepare("SELECT password_reset FROM users WHERE password_reset = ?")) {
        $stmt->bind_param("s", $reset);
        $stmt->execute();
        $stmt->bind_result($gen);
        if ($stmt->fetch()) {
            echo trim("success");
        } else {
            echo "fail";
        }
        $stmt->close();
        $mysqli->close();
    }
} else {
    echo "No code specified";
}
