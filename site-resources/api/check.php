<?php 
error_reporting(E_ALL);
include("connectdb.php");
if (isset($_POST['username'])) {
        
    $username = $_POST['username'];
    
    $stmt = $mysqli->prepare("SELECT username FROM users WHERE username = ?");
    
    if (isset($stmt)) {
        //echo $stmt;
    } else {
        printf("Errormessage: %s\n", $mysqli->error);
    }
    
    if ($stmt->bind_param("s", $username)) {
        if ($stmt->execute()) {
            $stmt->bind_result($username);
            if ($stmt->fetch()) {
                echo "successful";
            } else {
                sleep(1);
                echo "fail";
            }
        }
        else {
            echo "fail";
        }  
    } else {
        echo "bad bind";
        printf("Errormessage: %s\n", $mysqli->error);
    }
    $stmt->close();
} else {
    echo "failure";
}
?>