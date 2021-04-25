<?php //hebergement

require_once('connect_hanout.php');
require_once('includes/function_inc.php');
if (isset($_GET['article'])&&isset($_GET['qte'])){
	$article=$_GET['article'];
	$place=$_GET['place'];
	$q="UPDATE `article` SET `place`='$place' where `cle`=$article limit 1";
	addToLog($q);
	$r=mysqli_query ($dbc, $q);
}

?>
