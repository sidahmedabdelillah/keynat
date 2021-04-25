<?php
require_once('connect_hanout.php');

$q="SELECT * FROM `log2` WHERE `date` >= 2021-01-22 and not val like 'select%'";
$r=mysqli_query($dbc,$q);

while($row=mysqli_fetch_assoc($r)){
    $qry=$row['val'];
    //echo $qry; exit();
    $res=mysqli_query($dbc,$qry);
}
echo "sucess";
?>