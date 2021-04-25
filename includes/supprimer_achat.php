<?php
require_once('../connect_hanout.php');
session_start();
$article = $_GET['id'];
$q = "DELETE FROM `achat_temp` WHERE `achat_temp`.`cle` = $article limit 1;";
addToLog($q);
$r = mysqli_query($dbc, $q);
if ($r) {
    header('location:http://192.168.1.6/hanout/acheter2.php');
} else {
    echo mysqli_error($dbc);
}

?>
