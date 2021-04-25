<?php
require_once('includes/function_inc.php');
require_once('../connect_hanout.php');
$q1="TRUNCATE inventaire";
$r1=mysqli_query($dbc,$q1);
$q="select * from article where quantite>0";
$r=mysqli_query($dbc,$q);
while($row=mysqli_fetch_assoc($r)){
$cle=$row['cle'];
if($cle>$config['middle']){
  $responsable=6;
}else{
  $responsable=4;
}
  $q2="INSERT INTO `inventaire` (`article_id`, `vendeur`, `responsable`) VALUES ('$cle', '2', '$responsable');";
  $r2=mysqli_query($dbc,$q2);
}
 ?>
