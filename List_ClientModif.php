<?php

require_once('connect_hanout.php');
require_once('includes/function_inc.php');
if (isset($_GET['Client200']) && isset($_GET['type_200']) ) {
    $Client440 = $_GET['Client200'];
    
    $type_440 = $_GET['type_200'];
    $Nom_Client440 = $_GET['Nom_Client200'];
    $prenom440 = $_GET['prenom200'];
    $Telephone1_440 = $_GET['Telephone1_200'];
    $Telephone2_440 = $_GET['Telephone2_200'];
    
   $adress_440 = $_GET['adress_200'];
    //$obser1_440 = $_GET['obser1_200'];
    //$obser2_440 = $_GET['obser2_200'];
    
    
    //data: 'Client200=' + Client70  + '&Nom_Client200=' + Nom_Client120  +  '&type_200=' + type_120         
    // data: 'Client200=' + Client70  + '&Nom_Client200=' + Nom_Client120  +  '&type_200=' + type_80    
    // 'Client200=' + '&Nom_Client200   + '&Prenom200='  + '&Telephone1_200=' + '&Telephone2_200='  +'&adress_200=' 
    // '&obser1_200=' + '&obser2_200='   +  '&type_200='   
    
  
    //  $q = "UPDATE `client` SET `nom` = '$Nom_Client440', `prenom` = '$Prenom440', `telephone1` = '$Telephone1_440'  
   // ,`telephone2` = '$Telephone2_440', `adress` = '$adress_440', `obser1` = '$obser1_440' , `obser2` = '$obser2_440'
    //,`type` = '$type_440'


    $q = "UPDATE `client` SET `nom` = '$Nom_Client440',`type` = '$type_440',`prenom` = '$prenom440', `telephone1` = '$Telephone1_440' 
    ,`telephone2` = '$Telephone2_440'   , `adress` = '$adress_440'
     
     WHERE  `client`.`client_id` = $Client440   ;";
    addToLog($q);
    $r = mysqli_query($dbc, $q);
    if ($r) {
        echo 'done';
    } else {
        echo mysqli_error($dbc);
    }
}

?>
