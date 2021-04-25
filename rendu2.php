<?php //hebergement

session_start();

require_once('connect_hanout.php');
require_once('includes/function_inc.php');
if (isset($_GET['article'])&&isset($_GET['qte'])){
    $article=$_GET['article'];
    $vendeur=$_SESSION['v_id'];
    $qte=$_GET['qte'];
    $pv=$_GET['pv'];
    $q1="SELECT * from rendu_temp WHERE `cle`= $article limit 1;";
    $r2=mysqli_query($dbc,$q1);
    if(mysqli_num_rows($r2)){
      $q2="UPDATE `rendu_temp` SET `qte` = '$qte', `pv` = '$pv', `v_id` = '$vendeur' WHERE `rendu_temp`.`cle` = $article;";
    }else{
      $q2="INSERT INTO `rendu_temp` (`cle`, `qte`, `pv`,`datetime`, `v_id`) VALUES ('$article', '$qte', '$pv', CURRENT_TIMESTAMP, '$vendeur');";
    }
    addToLog($q2);
    $r2=mysqli_query($dbc,$q2);
    if($r2){
      //
    }else{
      echo mysqli_error($dbc);
    }
}

?>
