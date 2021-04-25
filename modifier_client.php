<?php //hebergement

require_once('connect_hanout.php');
require_once('includes/function_inc.php');
if (isset($_GET['client'])){
	$client=$_GET['client'];
	$nom=$_GET['nom'];
	$prenom=$_GET['prenom'];
	$address=$_GET['address'];
	$email=$_GET['email'];
	$type=$_GET['type'];
	$telephone1=$_GET['telephone1'];
	$telephone2=$_GET['telephone2'];
	$n_rc=$_GET['n_rc'];
	$n_ai=$_GET['n_ai'];
	$n_mf=$_GET['n_mf'];
	$obser1=$_GET['obser1'];
	$obser2=$_GET['obser2'];

	$q="UPDATE `client` SET `nom`='$nom', `prenom`='$prenom',`adress`='$address', `telephone1`='$telephone1', `telephone2`='$telephone2', `email`='$email', `type`='$type', `n_rc`='$n_rc', `n_ai`='$n_ai', `n_mf`='$n_mf', `obser1`='$obser1', `obser2`='$obser2' WHERE client_id='$client'";
	addToLog($q);
	$r=mysqli_query ($dbc, $q);
	if($r){
    //
  }else{
    echo mysqli_error($dbc);
  }
}

?>
