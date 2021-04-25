<?php
session_start();
require_once('connect_hanout.php');
require_once('includes/function_inc.php');
if(isset($_GET['pn'])){
  $pn=$_GET['pn'];
  $prix_vendu=$_GET['prix_vendu'];
  $q="UPDATE `pc_nadir` SET `date_vendu` = CURRENT_TIMESTAMP, `prix_vendu`='$prix_vendu' WHERE `pc_nadir`.`pn_id` =$pn;";
  addToLog($q);
  $r=mysqli_query($dbc,$q);
  if($r){
    
  }else{
    echo mysqli_error($dbc);
  }
}else{
  header('location:index.php');
}
 ?>
