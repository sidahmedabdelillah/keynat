<?php
require_once('connect_hanout.php');
$id = $_GET['id'];
$q="select * from article where cle='$id' limit 1";
addToLog($q);
$r=mysqli_query($dbc,$q);
$row=mysqli_fetch_assoc($r);
$q2="select * from vente where article_id='$id'";
$r2=mysqli_query($dbc,$q2);
$nbr_vente=mysqli_num_rows($r2);
$q3="select * from achat where article_id='$id'";
$r3=mysqli_query($dbc,$q3);
$nbr_achat=mysqli_num_rows($r3);
$q4="select * from perte where article_id='$id'";
$r4=mysqli_query($dbc,$q4);
$nbr_perte=mysqli_num_rows($r4);
$q5="select * from rendu where article_id='$id'";
$r5=mysqli_query($dbc,$q5);
$nbr_rendu=mysqli_num_rows($r5);
?>
<div class="col-md-12" align="center">
  <ul class="list-group">
    <li class="list-group-item"><b>Designiation:</b>  <span id="designiation"><?= $row['designiation'] ?></span> || <?= $row['quantite'] ?></li>
    <li class="list-group-item"><b>Nbr d'Achat:</b>  <span id=""><?= $nbr_achat ?></li>
    <li class="list-group-item"><b>Nbr de Vende:</b>  <span id=""><?= $nbr_vente ?></li>
    <li class="list-group-item"><b>Nbr de Rendu:</b>  <span id=""><?= $nbr_rendu ?></li>
    <li class="list-group-item"><b>Nbr de Perte:</b>  <span id=""><?= $nbr_perte ?></li>
  </ul>
</div>
