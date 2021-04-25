<?php
require_once('includes/function_inc.php');
require_once('connect_hanout.php');
$q="SELECT sum(quantite),count(*),sum(quantite)*count(*) FROM `article` WHERE quantite>0 and prix_achat>0";
$r=mysqli_query($dbc,$q);
$row=mysqli_fetch_assoc($r);
$nbr_artcle_sup=$row['count(*)'];
$nbr_sum_quantite=$row['sum(quantite)'];
$nbr_article_total=$row['sum(quantite)*count(*)'];

$q="INSERT INTO `nbr_article` (`nbr_artcle_sup`, `nbr_sum_quantite`, `nbr_article_total`) VALUES ('$nbr_artcle_sup', '$nbr_sum_quantite', '$nbr_article_total');";
$r=mysqli_query($dbc,$q);

 ?>
