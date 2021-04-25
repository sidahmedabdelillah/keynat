<?php
require_once('includes/function_inc.php');
require_once('connect_hanout.php');
@session_start();
$id = $_GET['id'];
$q="select * from article where cle='$id' OR codebar='$id' OR code='$id' order by cle limit 1";
$r=mysqli_query($dbc,$q);
$row=mysqli_fetch_assoc($r);
/**
 * update moulay start 01
 */
$id_four = getFournisseurInfo($_SESSION['fournisseur'])['f_id'];
$id_art = $row['cle'];
$q = "SELECT  `cle_article_four` FROM `article_four` WHERE `cle_article` = '$id_art' AND `id_four` = '$id_four'";
$r = mysqli_query($dbc,$q);
if (mysqli_num_rows($r) > 0)  {
  $cle_four = mysqli_fetch_assoc($r)['cle_article_four'];
}else {
  $cle_four = "";
}
/**
 * update moulay fin 02
 */
?>
<ul class="list-group">
  <li class="list-group-item"><b>Designiation:</b>  <span id="designiation"><?= $row['designiation'] ?></span> || <?= $row['quantite'] ?></li>
</ul>
<h3>Information</h3>
<input type="hidden" id="qter" value="<?= $row['quantite'] ?>" class="form-control">
Quantite en Stock: <input type="number" id="cle" value="<?= $row['quantite'] ?>" class="form-control">
Cle Fournisseur: <input type="text" id="clefour" value="<?= $cle_four ?>" class="form-control">
Prix Achat: <input type="number" id="pa" value="<?= $row['prix_achat'] ?>" class="form-control">
Prix Vente Detail:<input type="number" id="pv" value="<?= $row['prix_vente'] ?>" class="form-control">
Prix Vente Gros:<input type="number" id="pv2" value="<?= $row['prix_vente2'] ?>" class="form-control">
Prix Vente Super Gros:<input type="number" id="pv3" value="<?= $row['prix_vente3'] ?>" class="form-control">
<br><br>
