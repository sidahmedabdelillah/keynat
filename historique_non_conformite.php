<?php
session_start();
if (!isset($_SESSION['prenom'])) {
    $url = $_SERVER['REQUEST_URI'];
  ?><script type="text/javascript">location.href='login.php?continue=<?= $url ?>';</script><?php
}
require_once('includes/function_inc.php');
siPermis($_SESSION['type'], basename(__FILE__, '.php'));
require_once('connect_hanout.php');
$page_title='Historique non confirmite par Vendeur';
include('includes/header.html');
?>
<h2 align='center'>Historique non confirmite par Vendeur</h2><br>
<div class="form-actions">
  <a href="list_non_conformite.php"><button class="btn btn-default">Toutes <?= getNbrNonConformite(); ?></button></a>
  <a href="list_non_conformite.php?etat=En Attente"><button class="btn btn-primary">En Attente <?= getNbrNonConformite('En Attente'); ?></button></a>
    <a href="list_non_conformite.php?etat=Traité"><button class="btn btn-success">Terminé <?= getNbrNonConformite('Terminé'); ?></button></a><br><br>
    <a href="ajouter_non_confirmite.php"><button class="btn btn-danger">Ajouter Non Conformite</button></a>
    <a href="list_non_conformite.php"><button class="btn btn-warning">Liste Non Conformite</button></a>
</div>
<br>
<div class="col-md-12">
  <table class="table table-hover" style="width:100%;">
    <tr>
      <th>Ordre</th>
      <th>Vendeur</th>
      <th>Impact sur le projet</th>
      <th>Information</th>
    </tr>
    <?php
    $q="SELECT qui_fait_action, sum(impact) FROM `non_confirmite` group by qui_fait_action order by sum(impact) desc";
    $r=mysqli_query($dbc,$q);
    $i=1;
    while($row=mysqli_fetch_assoc($r)){?>
    <tr>
      <td><?= $i ?></td>
      <td><?= getVendeurInfo($row['qui_fait_action'])['prenom'] ?></td>
      <td><?= $row['sum(impact)'] ?></td>
      <td><a href="list_non_conformite.php?vendeur=<?= $row['qui_fait_action'] ?>"> <button type="button" class="btn btn-primary btn-md">Information</button></a></td>
    </tr>
    <?php
    $i++;
      }
    ?>
  </table>
</div>

<?php
include('includes/footer.html');

?>
