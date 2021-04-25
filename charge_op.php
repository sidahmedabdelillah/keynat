<?php //hebergement
session_start();
require_once('connect_hanout.php');
require_once('includes/function_inc.php');
if (isset($_GET['article'])){
	$vendeur=$_SESSION['v_id'];
	$article=$_GET['article'];
	$qte=$_GET['qte'];
	$pa=$_GET['pa'];
	$remarque=$_GET['remarque'];

	$q="UPDATE `article` SET `quantite`=`quantite`- $qte where `cle`=$article limit 1;";
	addToLog($q);
	$r=mysqli_query ($dbc, $q);
	if($r){
    //
  }else{
    echo mysqli_error($dbc);
		exit();
  }

	$q2="INSERT INTO `charge` (`a_id`, `qte`, `pa`, `v_id`, `obser1`) VALUES ('$article', '$qte', '$pa', '$vendeur', '$remarque');";
	addToLog($q2);
	$r2=mysqli_query ($dbc, $q2);
	if($r2){
    //
  }else{
    echo mysqli_error($dbc);
		exit();
  }

}

?>
