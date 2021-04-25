<?php
session_start();
require_once('../connect_hanout.php');
$article = $_GET['id'];
if (isset($_GET['vente'])) {
    $q = "DELETE FROM `vente_temp` WHERE `vt_id` = '$article'";
    addToLog($q);
    $r = mysqli_query($dbc, $q);
    if ($r) {
        //
    } else {
        echo mysqli_error($dbc);
    }
    header('location: ../vente.php');
}
if (isset($_GET['achat'])) {
    unset($_SESSION['stock'][$article]);
    header('location: ../acheter2.php');
}

?>
