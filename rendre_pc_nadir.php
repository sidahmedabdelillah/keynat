<?php
session_start();
require_once('connect_hanout.php');
require_once('includes/function_inc.php');
if(isset($_GET['pn'])){
  $pn=$_GET['pn'];
  $q="UPDATE `pc_nadir` SET `date_rendu_nadir` = CURRENT_TIMESTAMP WHERE `pc_nadir`.`pn_id` =$pn;";
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
