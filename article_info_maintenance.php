<?php
require_once('includes/function_inc.php');
require_once('connect_hanout.php');
@session_start();
$id = $_GET['id'];
$q="select * from article where cle='$id' OR codebar='$id' OR code='$id' order by cle limit 1";
$r=mysqli_query($dbc,$q);
$row=mysqli_fetch_assoc($r);
?>
<div class="form-group">
    Designiation: <input type="text" class="form-control" value="<?= $row['designiation'] ?>|| <?= $row['quantite'] ?>" disabled>
</div>
<div class="form-group">
    Quantité: <input type="text" class="form-control" id="qte" placeholder="Quantité" name="qte" value="1"
               required>
</div>

<div class="form-group">
    Prix Vente: <input type="text" class="form-control" id="pv" value="<?= $row['prix_vente'] ?>" name="pv" required>
</div>

<div class="form-group">
    Prix Achat: <input type="text" class="form-control" value="<?= $row['prix_achat'] ?>" disabled>
</div>
<div class="form-group">
    Prix Vente Détail: <input type="text" class="form-control" value="<?= $row['prix_vente'] ?>" disabled>
</div>
<div class="form-group">
    Prix Vente Gros: <input type="text" class="form-control" value="<?= $row['prix_vente2'] ?>" disabled>
</div>
<div class="form-group">
    Prix Vente Super Gros: <input type="text" class="form-control" value="<?= $row['prix_vente3'] ?>" disabled>
</div>
