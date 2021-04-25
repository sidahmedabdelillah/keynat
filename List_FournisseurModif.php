<?php

require_once('connect_hanout.php');
require_once('includes/function_inc.php');
if (isset($_GET['Fournisseur200']) && isset($_GET['Wilaya200']) ) {
    $Fournisseur440 = $_GET['Fournisseur200'];
    $Wilaya440 = $_GET['Wilaya200'];
    $NomFourn440 = $_GET['designiation200'];
    $Telephone440 = $_GET['Telephone200'];
    
   $Telephone2_440 = $_GET['Telephone2_200'];
    $prenom_440 = $_GET['prenom_200'];
    $adress_440 = $_GET['adress_200'];
    $obser1_440 = $_GET['obser1_200'];
    $obser2_440 = $_GET['obser2_200'];

    // 
    
    $q = "UPDATE `fournisseur` SET `f_wilaya` = '$Wilaya440', `f_nom` = '$NomFourn440', `telephon1` = '$Telephone440'  
   ,`telephon2` = '$Telephone2_440', `prenom` = '$prenom_440', `adress` = '$adress_440', `obser1` = '$obser1_440' , `obser2` = '$obser2_440'
   
    
     WHERE  `fournisseur`.`f_id` = $Fournisseur440   ;";
    addToLog($q);
    $r = mysqli_query($dbc, $q);
    if ($r) {
        echo 'done';
    } else {
        echo mysqli_error($dbc);
    }
}

?>
