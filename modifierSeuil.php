<?php //hebergement

require_once('connect_hanout.php');
require_once('includes/function_inc.php');
if (isset($_GET['article'])&&isset($_GET['s1'])&&isset($_GET['s2'])&&isset($_GET['s3'])){
	$article=$_GET['article'];
	$s1=$_GET['s1'];
	$s2=$_GET['s2'];
  $s3=$_GET['s3'];
	$q1=$_GET['q1'];
	$q2=$_GET['q2'];
	$q3=$_GET['q3'];

	$q="UPDATE `article` SET `seuil`= $s1, `seuil2`= $s2, `seuil3`= $s3, `qte_achat1`= $q1, `qte_achat2`= $q2, `qte_achat3`= $q3 where `cle`=$article limit 1";
	addToLog($q);
	$r=mysqli_query ($dbc, $q);
	if($r){
    //
  }else{
    echo mysqli_error($dbc);
  }
}

?>
