<?php
session_start();
if (!isset($_SESSION['prenom'])) {
    $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
require_once('connect_hanout.php');
$article=$_GET['article'];
$qte_reel=$_GET['qte_reel'];
$code=$_GET['code'];
$articleInfo=getArticleInfo($article);
if($articleInfo['quantite']==$qte_reel){
  $q="DELETE FROM `inventaire` WHERE `inventaire`.`article_id` = $article;";
  addToLog($q);
  $r=mysqli_query($dbc,$q);
  if($r){
    //
  }else{
    echo mysqli_error($dbc);
    exit();
  }
  $q2="UPDATE `article` SET `codebar` = '$code' WHERE `article`.`cle` = $article;";
  addToLog($q2);
  $r2=mysqli_query($dbc,$q2);
  if($r2){
  }else{
    echo mysqli_error($dbc);
    exit();
  }
}
else{
  $perte=$articleInfo['quantite']-$qte_reel;
  $q="DELETE FROM `inventaire` WHERE `inventaire`.`article_id` = $article;";
  addToLog($q);
  $r=mysqli_query($dbc,$q);
  if($r){
    //
  }else{
    echo mysqli_error($dbc);
    exit();
  }
  $q2="UPDATE `article` SET `codebar` = '$code' WHERE `article`.`cle` = $article;";
  addToLog($q2);
  $r2=mysqli_query($dbc,$q2);
  if($r2){
  }else{
    echo mysqli_error($dbc);
    exit();
  }

  $url = 'http://localhost/hanout/declarer_perte.php?article='.$article.'&nbr='.$perte;

  $contents = @file_get_contents($url);
}
?>
