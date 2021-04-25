<?php
require_once('includes/function_inc.php');
require_once('connect_hanout.php');
@session_start();
$id = $_GET['id'];
$q="select * from article where cle='$id' OR codebar='$id' OR code='$id' order by cle limit 1";
addToLog($q);
$r=mysqli_query($dbc,$q);
$row=mysqli_fetch_assoc($r);
?>
<ul class="list-group">
  <li class="list-group-item"><b>Designiation:</b>  <span id="designiation"><?= $row['designiation'] ?></span> || <?= $row['quantite'] ?></li>
</ul>
<?php
if(isset($_GET['maintenance'])){
  $cle=$_GET['id'];
  echo $cle;
  if(isset($_SESSION['client'])){
    $client=getClientInfo($_SESSION['client']);
    $type=$client['type'];
    if($type==3){
      ?>
      Prix Vente Super Gros: <input type="number" id="pv<?= $cle ?>" value="<?= $row['prix_vente3'] ?>" class="form-control">
      <?php
    }elseif($type==2){
      ?>
      Prix Vente Gros: <input type="number" id="pv<?= $cle ?>" value="<?= $row['prix_vente2'] ?>" class="form-control">
      <?php
    }else{
      ?>
      Prix Vente Détail: <input type="number" id="pv<?= $cle ?>" value="<?= $row['prix_vente'] ?>" class="form-control">
      <?php
    }
  }else{
    ?>
    Prix Vente Détail: <input type="number" id="pv<?= $cle ?>" value="<?= $row['prix_vente'] ?>" class="form-control">
    <?php
  }
}else{
  if(isset($_SESSION['client'])){
    $client=getClientInfo($_SESSION['client']);
    $type=$client['type'];
    if($type==3){
      ?>
      Prix Vente Super Gros: <input type="number" id="pv" value="<?= $row['prix_vente3'] ?>" class="form-control">
      <?php
    }elseif($type==2){
      ?>
      Prix Vente Gros: <input type="number" id="pv" value="<?= $row['prix_vente2'] ?>" class="form-control">
      <?php
    }else{
      ?>
      Prix Vente Détail: <input type="number" id="pv" value="<?= $row['prix_vente'] ?>" class="form-control">
      <?php
    }
  }else{
    ?>
    Prix Vente Détail: <input type="number" id="pv" value="<?= $row['prix_vente'] ?>" class="form-control">
    <?php
  }
}
?>
<h3>Information</h3>
<input type="hidden" id="qter" value="<?= $row['quantite'] ?>" class="form-control">
Prix Achat: <input type="number" id="pa" value="<?= $row['prix_achat'] ?>" class="form-control">
Prix Vente Detail:<input type="number" value="<?= $row['prix_vente'] ?>" class="form-control">
Prix Vente Gros:<input type="number" id="pv2" value="<?= $row['prix_vente2'] ?>" class="form-control">
Prix Vente Super Gros:<input type="number" id="pv3" value="<?= $row['prix_vente3'] ?>" class="form-control">
<br><br>
