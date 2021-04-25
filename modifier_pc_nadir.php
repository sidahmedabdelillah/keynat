<?php //hebergement

require_once('connect_hanout.php');
require_once('includes/function_inc.php');

if (isset($_GET['pn'])){
	$pn=$_GET['pn'];
	$marque=$_GET['marque'];
	$date_ajout=$_GET['date_ajout'];
  $processeur=$_GET['processeur'];
  $carte_graphique=$_GET['carte_graphique'];
	$ecran = $_GET['ecran'];
	$ram = $_GET['ram'];
	$dd = $_GET['dd'];
	$prix = $_GET['prix'];
	$batterie=$_GET['batterie'];
	$etat=$_GET['etat'];
    $obser1=$_GET['obser1'];


	$q="UPDATE `pc_nadir` SET `marque`='$marque', `date_ajout`='$date_ajout', `obser1`='$obser1', `prix`='$prix', `processeur`='$processeur', `carte_graphique`='$carte_graphique', `ram`='$ram', `dd`='$dd', `ecran`='$ecran', `batterie`='$batterie', `etat`='$etat' WHERE `pc_nadir`.`pn_id` = '$pn';";
	addToLog($q);
	$r=mysqli_query ($dbc, $q);
	if($r){
    header('location:list_pc_nadir.php?searchBarCode='.$pn);
  }else{
    echo mysqli_error($dbc);
  }
}

?>
