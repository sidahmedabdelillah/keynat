<?php
require_once('includes/function_inc.php');
require_once('includes/header.html');
require_once('connect_hanout.php');
$q="select * from article";
$r=mysqli_query($dbc,$q);
for ($i=1; $i < 5000; $i++) {
  while($row=mysqli_fetch_assoc($r)){

  }
}
?>
