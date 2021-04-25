<?php
require_once('connect_hanout.php');
for ($i=1;$i<20000;$i++){
    $q="SELECT * FROM `article` WHERE `cle`=$i;";
    $r=mysqli_query($dbc,$q);
    if(mysqli_num_rows($r)==0){
        $q2="INSERT INTO `article` (`cle`, `designiation`, `prix_achat`) VALUES ('$i', NULL, '0');";
        $r2=mysqli_query($dbc,$q2);
        if($r2){
          //
        }else{
          echo mysqli_error($dbc);
          exit();
        }
    }

}
