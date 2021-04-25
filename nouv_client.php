<?php //hebergement
session_start();
require_once('connect_hanout.php');
require_once('includes/function_inc.php');
if (isset($_GET['client'])){

	$client=$_GET['client'];

$q="INSERT INTO `client` (`nom`) VALUES ('$client');";
addToLog($q);
$r=mysqli_query($dbc,$q);
if ($r){
  //
}else{
  echo mysqli_error($dbc);
}
$id=mysqli_insert_id($dbc);
$_SESSION['client']=$id;
}
 ?>
