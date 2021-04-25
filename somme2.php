<?php
session_start();
if(!isset($_SESSION['prenom'])){
  $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
$page_title="Calcul de stock";
require_once('includes/header.html');
require_once('connect_hanout.php');
?>


<div class="col-md-12">
<h1 align="center">Calcul de stock</h1><br>

<div class="col-md-12">
  <form>
    <div class="input-group">
      <div class="col-md-6">
        <input type="number" class="form-control" placeholder="Début" name="debut" value="<?= @$_GET['debut'] ?>">
      </div>
      <div class="col-md-6">
        <input type="number" class="form-control" placeholder="Fin" name="fin" value="<?= @$_GET['fin'] ?>">
      </div>


      <div class="input-group-btn">
        <button class="btn btn-default" type="submit">
          <i class="glyphicon glyphicon-search"></i>
        </button>

      </div>
    </div>
  </form>
</div>
<br><br>
<div class="form-actions">
  <?php
  if((isset($_GET['debut']) and !empty($_GET['debut'])) and (isset($_GET['fin']) and !empty($_GET['fin']))){
  $actual_link = $actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>
  <a href="<?= $actual_link.'?debut='.$_GET['debut'].'&fin='.$_GET['fin'].'&total' ?>"><button class="btn btn-primary">Filter par total</button></a>
  <a href="<?= $actual_link.'?debut='.$_GET['debut'].'&fin='.$_GET['fin'].'&cle' ?>"><button class="btn btn-primary">Filter par clé</button></a>
<?php }
?>
</div>
<br>
<table class="table table-hover" style="table-layout:fixed;" id="articleTable">
  <tr>
    <th>Clé</th>
    <th>Désigniation</th>
    <th>Quantité</th>
    <th>Prix Achat</th>
    <th>Prix Vente</th>
    <th>Total</th>
  </tr>
<?php
if((isset($_GET['debut']) and !empty($_GET['debut'])) and (isset($_GET['fin']) and !empty($_GET['fin']))){
  $debut=$_GET['debut'];
  $fin=$_GET['fin'];
  $s=0;
  $q = "SELECT *, prix_achat*quantite from article where cle between $debut and $fin and quantite>0";
  if(isset($_GET['total'])){
    $q.=" order by prix_achat*quantite desc";
  }elseif(isset($_GET['cle'])){
    $q.=" order by cle";
  }
  addToLog($q);
  $r = mysqli_query($dbc,$q);
  while($articles=mysqli_fetch_array($r, MYSQLI_ASSOC)){
    $s+=($articles['quantite']*$articles['prix_achat']);
    ?>
    <tr>
      <td><?= $articles['cle'] ?></td>
      <td style="word-wrap: break-word;"><?= $articles['designiation'] ?></td>
      <td style="word-wrap: break-word;"><?= $articles['quantite'] ?></td>
      <td><?= $articles['prix_achat'] ?></td>
      <td><?= $articles['prix_vente'] ?></td>
      <td><?= $articles['quantite']*$articles['prix_achat'] ?></td>
    </tr>
    <?php
  }
}else{
  echo 'vérifier les valeurs de début et fin';
}


 ?>

</table>
<h1 align="right"><?= @$s ?></h1>
<?php
require_once('includes/footer.html');
 ?>
