<?php
session_start();
if(!isset($_SESSION['prenom'])){
  $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
$vendeur=$_SESSION['v_id'];
require_once('includes/function_inc.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
require_once('includes/header.html');
require_once('connect_hanout.php');
if(isset($_GET['submitted'])){
    $pe=$_GET['pe'];
    $caisse=getCaisseInfo();
    $ancien_caisse_gain=getCaisseGainInfo()['val'];
    $gain_caisse=$ancien_caisse_gain-$pe;
    $q1="INSERT INTO `caisse_gain` (`val`) VALUES ($gain_caisse);";
    addToLog($q1);
    $r1=mysqli_query ($dbc, $q1);
    if($r1){
      //
    }else{
      echo mysqli_error($dbc);
      exit();
    }
    $q2="INSERT INTO `caisse_modification` (`pa`, `pe`, `v_id`, `explication`, `gain50`) VALUES ('0', '$pe', '$vendeur', 'gain_50', '1');";
    addToLog($q2);
    $r2=mysqli_query($dbc,$q2);
    if($r2){
	   //
	  }else{
	    echo mysqli_error($dbc);
      exit();
	  }
}

?>
