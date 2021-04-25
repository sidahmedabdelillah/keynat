<?php
require_once('../connect_hanout.php');
session_start();
if (isset($_GET['achat'])) {
    $q = "TRUNCATE `achat_temp`;";
    addToLog($q);
    $r = mysqli_query($dbc, $q);
    if ($r) {
        header('location:../acheter2.php');
    } else {
        echo mysqli_error($dbc);
    }
} elseif (isset($_GET['vente'])) {
    $q = "TRUNCATE `vente_temp`;";
    addToLog($q);
    $r = mysqli_query($dbc, $q);
    if ($r) {
        header('location:../vente.php');
    } else {
        echo mysqli_error($dbc);
    }
} elseif (isset($_GET['client'])) {
    unset($_SESSION['client']);
    if (isset($_GET['maintenance'])) {
        header('location:../ajouter_maintenance.php');
        exit();
    } else {
        header('location:../vente.php');
        exit();
    }
}


?>
