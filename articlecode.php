<?php
require_once('connect_hanout.php');
$id = $_GET['id'];
$q="select * from article where cle=$id limit 1";
addToLog($q);
$r=mysqli_query($dbc,$q);
$row=mysqli_fetch_assoc($r);
?>
<div class="col-md-6">
  Code:<input type="number" id="code" name="code" value="<?= $row['code'] ?>" placeholder="Pas de code trouvé" class="form-control" required onchange="getCodeifUnique();" onkeydown="getCodeifUnique();"><br>
</div>
<div class="col-md-12" align="center">
  <ul class="list-group">
    <li class="list-group-item"><b>Designiation:</b>  <span id="designiation"><?= $row['designiation'] ?></span> || <?= $row['quantite'] ?></li>
  </ul>
</div>
