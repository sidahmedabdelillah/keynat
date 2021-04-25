<?php

require_once('connect_hanout.php');

if (isset($_POST['debut_mod_cat']) and isset($_POST['debut_mod_cat']) and isset($_POST['catimodi'])) {
    
    $debut_mod_cat=$_POST['debut_mod_cat'];
    $fin_mod_cat=$_POST['fin_mod_cat'];
    $catimodi=$_POST['catimodi'];

    $q = "UPDATE  article SET obser1 = '$catimodi' WHERE cle between $debut_mod_cat and $fin_mod_cat ";
    
    $r = mysqli_query($dbc, $q);
    if ($r) {
        echo 'done';
    } else {
        echo mysqli_error($dbc);
    }
}




?>
