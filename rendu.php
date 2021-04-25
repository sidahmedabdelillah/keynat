<?php
session_start();
if(!isset($_SESSION['prenom'])){
  $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
require_once('includes/header.html');
require_once('connect_hanout.php');
calculerStock();
?>
<div class="col-md-12">
<h1 align="center"><?= NOM ?> Opération de Rendu</h1><br>

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
    <th  onclick="sortTable(0);">Clé</th>
    <th class="col-md-5">Désigniation</th>
    <th>Quantité</th>
    <th>Prix Vente</th>
    <th>Rendre</th>
  </tr>
<?php
if((isset($_GET['debut']) and !empty($_GET['debut'])) and (isset($_GET['fin']) and !empty($_GET['fin']))){
  $debut=$_GET['debut'];
  $fin=$_GET['fin'];
$q = "SELECT * from article where cle between $debut and $fin order by cle";
addToLog($q);
$r = mysqli_query($dbc,$q);
while($articles = mysqli_fetch_assoc($r)){
  ?>
  <tr>
    <td><?= $articles['cle'] ?></td>
    <td style="word-wrap: break-word;"><?= $articles['designiation'] ?></td>
    <td><?= $articles['quantite'] ?></td>
    <td><?= $articles['prix_vente'] ?></td>
    <td><button type="button" class="btn btn-danger btn-md" data-toggle="modal" data-target="#dialog_perte<?= $articles['cle'] ?>">Rendre</button></td>
  </tr>
    <div class="modal fade" id="dialog_perte<?= $articles['cle'] ?>" role="dialog">
        <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title"><?= $articles['designiation'] ?></h4>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                  <li class="list-group-item">Clé <span class="badge"><?= $articles['cle'] ?></span></li>
                  <li class="list-group-item">Quantité Restante<span class="badge"><?= $articles['quantite'] ?></span></li>
                  <li class="list-group-item">Prix Achat<span class="badge"><?= $articles['prix_achat'] ?></span></li>
                  <li class="list-group-item">Prix Vente<span class="badge"><?= $articles['prix_vente'] ?></span></li>
                  <li class="list-group-item">Seuil<span class="badge"><?= $articles['seuil'] ?></span></li>
                  <li class="list-group-item">Date Achat<span class="badge"><?= $articles['date_achat'] ?></span></li>
                  Quantité Rendu:<li class="list-group-item"><input type="number" id="qte_rendu<?= $articles['cle'] ?>" value="1" class="form-control"></li>
                  Prix Vente:<li class="list-group-item"><input type="number" id="prix_vente_<?= $articles['cle'] ?>" value="<?= $articles['prix_vente'] ?>" class="form-control"></li>
                </ul>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" style="float:left;"  onclick="rendre(<?= $articles['cle'] ?>,1);">Retour</button>
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
