<?php
session_start();
if(!isset($_SESSION['prenom'])){
  $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
$page_title="Modifier la caisse";
require_once('includes/header.html');
require_once('connect_hanout.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
if(isset($_GET['done'])){
  ?>
    <script type="text/javascript">
      alert('Caisse Modifié');
      window.location.href = "caisser.php";
    </script>
  <?php
}
$caisse = getCaisseInfo();
$vendeur=$_SESSION['v_id'];
if(isset($_GET['submitted'])){
    $pa=$_GET['pa'];
    $pe=$_GET['pe'];
    if(isset($_GET['explication'])){
      $explication=$_GET['explication'];
    }else{
      $explication="";
    }
    if(isset($_GET['gain'])){
      $gain50=$_GET['gain'];
    }else{
      $gain50=0;
    }
    $val = $caisse['val']+$pa-$pe;
    newCaisse($val);
    $q2="INSERT INTO `caisse_modification` (`pa`, `pe`, `v_id`, `explication`, `gain50`) VALUES ('$pa', '$pe', '$vendeur', '$explication', '$gain50');";
    addToLog($q);
    $r2=mysqli_query($dbc,$q2);
    if($r2){
	   //
	  }else{
	    echo mysqli_error($dbc);
      exit();
	  }
    ?>
      <script type="text/javascript">
        window.location.href = "caisser.php?done";
      </script>
    <?php
}
?>

<div class="col-md-10 col-md-offset-1" style="margin-top:150px;">
  <h2>Caisse actuelle: <?= $caisse['val'] ?></h2>
    <form method="get" action="caisser.php">
  <div class="form-group">
    <label for="pa">Prix a ajouter:</label>
    <input type="number" name="pa" class="form-control" id="pa" value="0" required>
  </div>

  <div class="form-group">
    <label for="pe">Prix a enlever:</label>
    <input type="number" name="pe" class="form-control" id="pe" value="0" required>
  </div>

  <div class="form-group">
    <label for="explication">Explication:</label>
    <input type="text" name="explication" class="form-control" id="explication">
  </div>

  <input type="hidden" name="submitted" value="TRUE">
  <input type="hidden" name="gain" value="0">
  <button type="submit" class="btn btn-primary btn-block">Modifier</button>
</form>

</div>
<?php
require_once('includes/footer.html');
 ?>
