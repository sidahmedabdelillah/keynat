<?php //hebergement

session_start();

require_once('connect_hanout.php');
require_once('includes/function_inc.php');
if (isset($_GET['article'])&&isset($_GET['qte'])){
    $code=$_GET['article'];
    $article=getArticleInfo($_GET['article'])['cle'];
    echo $article;
    $vendeur=$_SESSION['v_id'];
    $qte=$_GET['qte'];
    $pv=$_GET['pv'];
    $pv2=@$_GET['pv2'];
    $pv3=@$_GET['pv3'];
    // $q1="SELECT * from vente_temp WHERE `cle`= $article limit 1;";
    // addToLog($q1);
    // $r2=mysqli_query($dbc,$q1);
    // if(mysqli_num_rows($r2)){
    //   $q2="UPDATE `vente_temp` SET `qte` = '$qte', `pv` = '$pv', `pv2` = '$pv2', `pv3` = '$pv3', `v_id` = '$vendeur' WHERE `vente_temp`.`cle` = $article;";
    // }else{
      $q2="INSERT INTO `vente_temp` (`cle`, `qte`, `pv`, `pv2`, `pv3`, `datetime`, `v_id`) VALUES ('$article', '$qte', '$pv', '$pv2', '$pv3', CURRENT_TIMESTAMP, '$vendeur');";
    //}
    addToLog($q2);
    $r2=mysqli_query($dbc,$q2);
    if($r2){
      //
    }else{
      echo mysqli_error($dbc);
    }
}

?>
