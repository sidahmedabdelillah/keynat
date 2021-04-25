<?php
require_once('connect_hanout.php');
$id = intval($_GET['id']);
$q="select * from article where code=$id limit 1";
addToLog($q);
$r=mysqli_query($dbc,$q);
$num=mysqli_num_rows($r);
if($num>0){?>
<h2 style="color:red;text-align:center;">Code deja utilisé</h2>
<div class="form-actions">
  <input type="submit" class="btn btn-primary disabled" disabled>
</div>
<?php }else{?>
  <h2 style="color:red;text-align:center;"></h2>
  <div class="form-actions">
    <input type="submit" class="btn btn-primary" >
  </div>
<?php }
