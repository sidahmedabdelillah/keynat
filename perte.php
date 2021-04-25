<?php //hebergement
session_start();
require_once('connect_hanout.php');
require_once('includes/function_inc.php');
if (isset($_GET['article'])&&isset($_GET['qte'])){
	$article=$_GET['article'];
	$qte=$_GET['qte'];
  $vendeur_id= $_SESSION['v_id'];
	$q="INSERT INTO `perte` (`article_id`, `qte_perdu`, `vendeur_id`) VALUES ($article, $qte, $vendeur_id);";
	addToLog($q);
	$r=mysqli_query ($dbc, $q);
  $caisse=getCaisseInfo();
	$q2="UPDATE `article` SET `quantite` = `quantite`-$qte WHERE `article`.`cle` = $article;";
	addToLog($q2);
	$r2=mysqli_query ($dbc, $q2);
	if($r){
    //
  }else{
    echo mysqli_error($dbc);
  }
	if($r2){
    //
  }else{
    echo mysqli_error($dbc);
  }
}

?>
