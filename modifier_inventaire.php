<?php
require_once('includes/function_inc.php');
require_once('../connect_hanout.php');
//amine
$q2="update inventaire set responsable=9 where article_id>0 and article_id<250;";
$r2=mysqli_query($dbc,$q2);
//mouna
$q2="update inventaire set responsable=10 where article_id>=250 and article_id<500;";
$r2=mysqli_query($dbc,$q2);
//wail
$q2="update inventaire set responsable=2 where article_id>=500 and article_id<750;";
$r2=mysqli_query($dbc,$q2);
//torkia
$q2="update inventaire set responsable=6 where article_id>=750 and article_id<1000;";
$r2=mysqli_query($dbc,$q2);
//abdennour
$q2="update inventaire set responsable=7 where article_id>=1000 and article_id<1250;";
$r2=mysqli_query($dbc,$q2);
//wail 2
$q2="update inventaire set responsable=2 where article_id>=1250 and article_id<1500;";
$r2=mysqli_query($dbc,$q2);
//torkia 2
$q2="update inventaire set responsable=6 where article_id>=1500 and article_id<1750;";
$r2=mysqli_query($dbc,$q2);
//amine 2
$q2="update inventaire set responsable=9 where article_id>=1750 and article_id<2000;";
$r2=mysqli_query($dbc,$q2);

//wail 3
$q2="update inventaire set responsable=2 where article_id>=2000 and article_id<2250;";
$r2=mysqli_query($dbc,$q2);

//mouna 3
$q2="update inventaire set responsable=10 where article_id>=2250 and article_id<2500;";
$r2=mysqli_query($dbc,$q2);

//wail 4
$q2="update inventaire set responsable=2 where article_id>=2500 and article_id<2750;";
$r2=mysqli_query($dbc,$q2);

//amine 3
$q2="update inventaire set responsable=9 where article_id>=2750 and article_id<3000;";
$r2=mysqli_query($dbc,$q2);

//wail 5
$q2="update inventaire set responsable=2 where article_id>=3000 and article_id<3250;";
$r2=mysqli_query($dbc,$q2);

//abdennour 2
$q2="update inventaire set responsable=7 where article_id>=3250 and article_id<3500;";
$r2=mysqli_query($dbc,$q2);

//amine 4
$q2="update inventaire set responsable=9 where article_id>=3500 and article_id<3750;";
$r2=mysqli_query($dbc,$q2);

//amine 5
$q2="update inventaire set responsable=9 where article_id>=3750 and article_id<4000;";
$r2=mysqli_query($dbc,$q2);

//amine 6
$q2="update inventaire set responsable=9 where article_id>=4000 and article_id<4250;";
$r2=mysqli_query($dbc,$q2);

//torkia 2
$q2="update inventaire set responsable=6 where article_id>=4250 and article_id<4500;";
$r2=mysqli_query($dbc,$q2);

//torkia 2
$q2="update inventaire set responsable=6 where article_id>=4500 and article_id<4750;";
$r2=mysqli_query($dbc,$q2);

//amine
$q2="update inventaire set responsable=9 where article_id>=4750 and article_id<5000;";
$r2=mysqli_query($dbc,$q2);

//amine
$q2="update inventaire set responsable=9 where article_id>=5000 and article_id<5250;";
$r2=mysqli_query($dbc,$q2);

//abdennour
$q2="update inventaire set responsable=7 where article_id>=5250;";
$r2=mysqli_query($dbc,$q2);
?>
