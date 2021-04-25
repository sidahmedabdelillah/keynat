<?php
session_start();
require_once('connect_hanout.php');
require_once('includes/function_inc.php');
if (isset($_GET['article']) && isset($_GET['qte'])) {
    $articleInfo = getArticleInfo($_GET['article']);
    $article_id = $articleInfo['cle'];
    $vendeur = $_SESSION['v_id'];
    $qte = $_GET['qte'];
    $four_cle = $_GET['clefour']; // update moulay 02
    $pa = $_GET['pa'];
    $pv = $_GET['pv'];
    $pv2 = $_GET['pv2'];
    $pv3 = $_GET['pv3'];
    $q1 = "SELECT * from achat_temp WHERE `cle`= $article_id limit 1;";
    addToLog($q1);
    $r1 = mysqli_query($dbc, $q1);
    if (mysqli_num_rows($r1) > 0) {
        $q2 = "UPDATE `achat_temp` SET `cle_four`= '$four_cle', `qte` = '$qte', `pv` = '$pv', `pv2` = '$pv2', `pv3` = '$pv3', `pa` = '$pa', `v_id` = '$vendeur' WHERE `achat_temp`.`cle` = $article_id;";
    } else {
        $q2 = "INSERT INTO `achat_temp` (`cle`,`cle_four`, `qte`, `pv`, `pa`, `pv2`, `pv3` , `v_id`) VALUES ('$article_id','$four_cle', '$qte', '$pv','$pa', '$pv2', '$pv3', '$vendeur');";
    }
    addToLog($q2);
    $r2 = mysqli_query($dbc, $q2);
    if ($r2) {
        //
    } else {
        echo mysqli_error($dbc);
    }
}
?>
