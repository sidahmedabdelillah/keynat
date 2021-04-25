<?php
session_start();
if(!isset($_SESSION['prenom'])){
  $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
if($_SESSION['type']<>1){
  header("location:index.php");
}
require_once('includes/function_inc.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
require_once('includes/header.html');
require_once('connect_hanout.php');
if(isset($_GET['month'])){
  $month=$_GET['month'];
}else{
  $month=date('m');
}
?>
<br><br>
<div class="col-md-10 col-md-offset-1">
  <table class="table table-hover">
    <tr>
      <th>Jour</th>
      <th>Recette</th>
    </tr>
    <?php

    for ($i=1; $i <= 30; $i++) {
      ?>
      <tr>
        <td><?= $i ?></td>
        <td><?= calculerRecette($i,$month) ?></td>
      </tr><?php
    }

     ?>
  </table>
</div>

<?php
require_once('includes/footer.html');
 ?>
