<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');

require "../../connectdb.php";
require "../../auth/get_permissions.php";

$gid = $_POST["gid"];
$moid = $_POST["moid"];
$progression = $_POST["progression"];
$value = $_POST["value"];

if (isset($gid) && isset($moid) && isset($progression) && isset($value) && $role == 1) {
    if (isset($_FILES)) {
        $flag = false;
        $path = "../images/teams/".$gid."/mission";
        $full_path = "../images/teams/".$gid."/mission/".$moid;
        //$adjusted_path = "site-resources/images/teams/".$gid."/logo/";
        $path_small = "../images/teams/".$gid;
        if (file_exists($path)) {
            if (file_exists($full_path)) {
                if (file_exists($full_path)) {
                    if (move_uploaded_file($_FILES[0]['tmp_name'], $full_path."/".basename($_FILES[0]['name']))) {
                        //$path_logo = $adjusted_path.basename($_FILES[0]['name']);
                    } else {
                        $flag = true;
                    }
                } else {
                    $flag = true;
                }
            } else {
                if (mkdir($full_path)) {
                    if (move_uploaded_file($_FILES[0]['tmp_name'], $full_path."/".basename($_FILES[0]['name']))) {
                        //$path_logo = $adjusted_path.basename($_FILES[0]['name']);
                    } else {
                        $flag = true;
                    }
                } else {
                    $flag = true;
                }
            }
        } else {
            if (file_exists($path_small)) {
                if (mkdir($path) == true) {
                    if (mkdir($full_path)) {
                        if (move_uploaded_file($_FILES[0]['tmp_name'], $full_path."/".basename($_FILES[0]['name']))) {
                            //$path_logo = $adjusted_path.basename($_FILES[0]['name']);
                        } else {
                            $flag = true;
                        }
                    } else {
                        $flag = true;
                    }
                } else {
                    echo "Could not make target directory";
                    $flag = true;
                }
            } else {
                if (mkdir($path_small) == true) {
                    if (mkdir($path) == true) {
                        if (mkdir($full_path)) {
                            if (move_uploaded_file($_FILES[0]['tmp_name'], $full_path."/".basename($_FILES[0]['name']))) {
                                //$path_logo = $adjusted_path.basename($_FILES[0]['name']);
                            } else {
                                $flag = true;
                            }
                        } else {
                            $flag = true;
                        }
                    } else {
                        echo "Could not make target directory";
                        $flag = true;
                    }
                }
            }
        }
    } else {
        $flag = true;
        echo "No file";
    }

    if ($flag == false) {
        if ($stmt = $mysqli->prepare("INSERT INTO group_points (gid, points) VALUES (?, (SELECT ?/(completed + 1) FROM mission WHERE moid = ?)) ON DUPLICATE KEY UPDATE points = points + (SELECT ?/(completed + 1) FROM mission WHERE moid = ?)")) {
            $stmt->bind_param("iiiii", $gid, $value, $moid, $value, $moid);
            $stmt->execute();
            $a = $mysqli->affected_rows;
            if ($a > 0) {
                $query = "UPDATE mission SET completed = completed + 1 WHERE moid = ?";
                $stmt->close();
                if ($stmt = $mysqli->prepare($query)) {
                    $stmt->bind_param("i", $moid);
                    $stmt->execute();
                    $a = $mysqli->affected_rows;

                    if ($a > 0) {
                        if ($stmt = $mysqli->prepare("INSERT INTO group_mission (gid, moid, progression) VALUES (?, ?, ?)")) {
                            $stmt->bind_param("iii", $gid, $moid, $progression);
                            $stmt->execute();
                            $a = $mysqli->affected_rows;
                            if ($a > 0) {
                                echo "success";
                            } else {
                                echo "fail";
                            }
                        } else {
                            echo $mysqli->error;
                        }
                    } else {
                        echo "No changes could be made";
                    }
                    $stmt->close();
                    $mysqli->close();
                } else {
                    echo "Database error 2: ".$mysqli->error;
                }
            } else {
                echo $a.$mysqli->error;
            }
        } else {
            echo $mysqli->error;
        }
    } else {
        echo "Flagged";
    }
} else {
    echo "No variables";
}
