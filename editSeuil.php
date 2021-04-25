<?php
session_start();
if(!isset($_SESSION['prenom'])){
  $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
require_once('includes/header.html');
require_once('connect_hanout.php');
?>


<div class="col-md-12">
<h1 align="center">Modifier les Seuils</h1><br>

<div class="col-md-8">
  <form>
    <div class="input-group">
      <div class="col-md-4">
        <input type="text" class="form-control" placeholder="Recherche par Désignation" id="searchBar" onkeydown="filterTable()">
      </div>
      <div class="col-md-4">
        <input type="text" class="form-control" placeholder="Recherche par Désignation" id="searchBar2" onkeydown="filterTable()">
      </div>
      <div class="col-md-4">
        <input type="text" class="form-control" placeholder="Recherche par Désignation" id="searchBar3" onkeydown="filterTable()">
      </div>

      <div class="input-group-btn">
        <button class="btn btn-default" type="submit">
          <i class="glyphicon glyphicon-search"></i>
          <button class="btn btn-default" type="button" onclick="resetBtn();">Reset</button>
        </button>

      </div>
    </div>
  </form>
</div>
<div class="col-md-4">
  <form>
    <div class="input-group">
      <input type="text" class="form-control" placeholder="Recherche par Code" id="searchBarCode" onkeydown="filterTableCode()">
      <div class="input-group-btn">
        <button class="btn btn-default" type="submit">
          <i class="glyphicon glyphicon-search"></i>
            <button class="btn btn-default" type="button" onclick="resetBtn();">Reset</button>
        </button>
      </div>
    </div>

  </form>
</div>
<br><br>
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
<br><br><br>
<table class="table table-hover" style="table-layout:fixed;" id="articleTable">
  <tr>
    <th>Clé</th>
    <th>Désigniation</th>
    <th>Quantité</th>
    <th>Seuil 1</th>
    <th>Seuil 2</th>
    <th>Seuil 3</th>
    <th>Qte A 1</th>
    <th>Qte A 2</th>
    <th>Qte A 3</th>
    <th>Info</th>
  </tr>
<?php
if((isset($_GET['debut']) and !empty($_GET['debut'])) and (isset($_GET['fin']) and !empty($_GET['fin']))){
  $debut=$_GET['debut'];
  $fin=$_GET['fin'];
$q1 = "SELECT * from article where cle between $debut and $fin order by cle;";
addToLog($q);
$r1 = mysqli_query($dbc,$q1);
while($articles = mysqli_fetch_assoc($r1)){
  ?>
  <tr>
    <td><?= $articles['cle'] ?></td>
    <td style="word-wrap: break-word;"><?= $articles['designiation'] ?></td>
    <td><?= $articles['quantite'] ?></td>
    <td><?= $articles['seuil'] ?></td>
    <td><?= $articles['seuil2'] ?></td>
    <td><?= $articles['seuil3'] ?></td>
    <td><?= $articles['qte_achat1'] ?></td>
    <td><?= $articles['qte_achat2'] ?></td>
    <td><?= $articles['qte_achat3'] ?></td>
    <td><button type="button" class="btn btn-primary btn-md" data-toggle="modal" data-target="#dialog<?= $articles['cle'] ?>">Modifier Seuil</button></td>
  </tr>
  <div class="modal fade" id="dialog<?= $articles['cle'] ?>" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?= $articles['designiation'] ?></h4>
          </div>
          <div class="modal-body">
            <p>
              <ul class="list-group">
                <li class="list-group-item">Clé <span class="badge"><?= $articles['cle'] ?></span></li>
                <li class="list-group-item">Quantité Restante<span class="badge"><?= $articles['quantite'] ?></span></li>
                <li class="list-group-item">Prix Achat<span class="badge"><?= $articles['prix_achat'] ?></span></li>
                <li class="list-group-item">Prix Vente<span class="badge"><?= $articles['prix_vente'] ?></span></li>
                <li class="list-group-item">Seuil<span class="badge"><?= $articles['seuil'] ?></span></li>
                <li class="list-group-item">Date Achat<span class="badge"><?= $articles['date_achat'] ?></span></li>
                Seuil 1:<li class="list-group-item"><input type="number" id="s1_<?= $articles['cle'] ?>" value="<?= $articles['seuil'] ?>" class="form-control"></li>
                Seuil 2:<li class="list-group-item"><input type="number" id="s2_<?= $articles['cle'] ?>" value="<?= $articles['seuil2'] ?>" class="form-control"></li>
                Seuil 3:<li class="list-group-item"><input type="number" id="s3_<?= $articles['cle'] ?>" value="<?= $articles['seuil3'] ?>" class="form-control"></li>
                Qte Achat 1:<li class="list-group-item"><input type="number" id="q1_<?= $articles['cle'] ?>" value="<?= $articles['qte_achat1'] ?>" class="form-control"></li>
                Qte Achat 2:<li class="list-group-item"><input type="number" id="q2_<?= $articles['cle'] ?>" value="<?= $articles['qte_achat2'] ?>" class="form-control"></li>
                Qte Achat 3:<li class="list-group-item"><input type="number" id="q3_<?= $articles['cle'] ?>" value="<?= $articles['qte_achat3'] ?>" class="form-control"></li>

              </ul>
            </p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" style="float:left;"  onclick="modifierSeuil(<?= $articles['cle'] ?>,1);">Modifier Seuil</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

          </div>
        </div>

      </div>
    </div>
  </div>
  <?php
}
}
 ?>

</table>

<?php
require_once('includes/footer.html');
 ?>
