<?php

require "cookies.php";
if (destroyCookie("usertoken")) {
    echo "success";
} else {
    echo "failure";
}
