<?php //hebergement
session_start();
require_once('connect_hanout.php');
require_once('includes/function_inc.php');
if (isset($_GET['article'])&&isset($_GET['qte'])){
	$vendeur=$_SESSION['v_id'];
	$article=$_GET['article'];
	$qte=$_GET['qte'];
	$pv=$_GET['pv'];
	$caisse = getCaisseInfo();
	$val_stock = $caisse['val_stock'];
	$article_info = getArticleInfo($article);
	$gain = ($pv - $article_info['prix_achat'])*$qte;
	$gain_propre = $caisse['gain'] + $gain;
	$val = $caisse['val'] + ($pv*$qte);

	$q="UPDATE `article` SET `quantite`=`quantite`- $qte where `cle`=$article limit 1";
	addToLog($q);
	$r=mysqli_query ($dbc, $q);
	if($r){
    //
  }else{
    echo mysqli_error($dbc);
  }

	$q2="INSERT INTO `vente` (`vente_id`, `facture_vente_id`, `article_id`, `qte_vente`, `prix_vente`, `vendeur_id`) VALUES (NULL, NULL, '$article', '$qte', '$pv', '$vendeur');";
	addToLog($q2);
	$r2=mysqli_query ($dbc, $q2);
	if($r2){
    //
  }else{
    echo mysqli_error($dbc);
  }
	$q5="INSERT INTO `gain` (`val`, `date`) VALUES ('$gain', CURRENT_TIMESTAMP);";
	addToLog($q5);
	$r5=mysqli_query ($dbc, $q5);
	if($r5){
		//
	}else{
		echo mysqli_error($dbc);
		exit();
	}
	$sum=calculerSomme_prix_achatQuantiteManuante();
	$sum2=calculerSomme_prix_achatQuantiteSeuil1();
	if($val<>$caisse['val']){
		newCaisse($val);
	}

}

?>
