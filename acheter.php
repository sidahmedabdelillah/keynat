<?php

require_once('connect_hanout.php');
require_once('includes/function_inc.php');
if (isset($_GET['article']) && isset($_GET['quantite_a']) && isset($_GET['prix_achat_a'])) {
    $article = $_GET['article'];
    $qte = $_GET['quantite_a'];
    $designiation = $_GET['designiation'];
    $pa = $_GET['prix_achat_a'];
    $pv = $_GET['prix_vente_a'];
    $obser1 = $_GET['obser1'];
    $obser2 = $_GET['obser2'];
    $code = $_GET['code'];

    $articleInfo = getArticleInfo($article);
    $q = "UPDATE `article` SET `quantite` = '$qte',`codebar` = '$code', `prix_achat` = '$pa', `prix_vente` = '$pv', `designiation` = '$designiation', `obser1` = '$obser1', `obser2` = '$obser2' WHERE `article`.`cle` = $article;";
    addToLog($q);
    $r = mysqli_query($dbc, $q);
    if ($r) {
        echo 'done';
    } else {
        echo mysqli_error($dbc);
    }
}

?>
