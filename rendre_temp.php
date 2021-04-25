<?php
session_start();
require_once('connect_hanout.php');
require_once('includes/function_inc.php');
if (isset($_GET['article'])){
	$vendeur=$_SESSION['v_id'];
	$article=$_GET['article'];
	$qte=$_GET['qte'];
	$pv=$_GET['pv'];
	$q="INSERT INTO `rendu_temp` (`cle`, `qte`, `pv`, `v_id`) VALUES ('$article', '$qte', '$pv', '$vendeur');";
  $r=mysqli_query($dbc,$q);
  if($r){

  }else{
    echo mysqli_error($dbc);
  }
}

?>
