<?php

require_once('connect_hanout.php');   

if (isset($_POST['debut_mod_cat']) and isset($_POST['debut_mod_cat']) and isset($_POST['catimodi22'])) {
    
    $debut_mod_cat=$_POST['debut_mod_cat'];
    $fin_mod_cat=$_POST['fin_mod_cat'];
    $catimodi23=$_POST['catimodi22'];
    $catimodi33=$_POST['catimodi32'];
    $catimodi64=$_POST['catimodi63'];
    // prix_vente2  catimodi32   catimodi63
    $q = "UPDATE  article SET prix_vente = '$catimodi23' , prix_vente2 = '$catimodi33'  , prix_vente3 = '$catimodi64'
    WHERE cle between $debut_mod_cat and $fin_mod_cat ";
    
    $r = mysqli_query($dbc, $q);
    if ($r) {
        echo 'done'; 
    } else {
        echo mysqli_error($dbc);
    }
}




?>
