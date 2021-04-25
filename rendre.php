<?php //hebergement
session_start();
require_once('connect_hanout.php');
require_once('includes/function_inc.php');
if (isset($_GET['article'])){
	$vendeur=$_SESSION['v_id'];
	$article=$_GET['article'];
	$qte=$_GET['qte'];
	$pv=$_GET['pv'];
	$caisse = getCaisseInfo();
	$val_stock = $caisse['val_stock'];
	$article_info = getArticleInfo($article);
	$gain = (($pv - $article_info['prix_achat'])*$qte)*(-1);
	$gain_propre = $caisse['gain'] - $gain;
	$val = $caisse['val'] - ($pv*$qte);



	$q="UPDATE `article` SET `quantite`=`quantite`+ $qte where `cle`=$article limit 1;";
	addToLog($q);
	$r=mysqli_query ($dbc, $q);
	if($r){
    //
  }else{
    echo mysqli_error($dbc);
  }
	rendu_fun($article,$qte,$vendeur,$pv);
	newCaisse($val);

	$q4="INSERT INTO `gain` (`val`, `vendeur`) VALUES ('$gain', '$vendeur');";
	addToLog($q4);
	$r4=mysqli_query ($dbc, $q4);
	if($r4){
    //
  }else{
    echo mysqli_error($dbc);
  }
}

?>
