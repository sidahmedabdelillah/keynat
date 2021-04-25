<?php
session_start();
if(!isset($_SESSION['nom'])){
  $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
require_once('includes/header.html');
require_once('connect_hanout.php');
?>


<div class="col-md-12">
<h1 align="center">Liste des fournisseur</h1><br>

<div class="col-md-8">
  <form>
    <div class="input-group">
      <input type="text" class="form-control" placeholder="Recherche par Désignation" id="searchBar" onkeyup="filterTable()">
      <div class="input-group-btn">
        <button class="btn btn-default" type="submit">
          <i class="glyphicon glyphicon-search"></i>
        </button>
      </div>
    </div>
  </form>
</div>
<div class="col-md-4">
  <form>
    <div class="input-group">
      <input type="text" class="form-control" placeholder="Recherche par Code" id="searchBarCode" onkeyup="filterTableCode()">
      <div class="input-group-btn">
        <button class="btn btn-default" type="submit">
          <i class="glyphicon glyphicon-search"></i>
        </button>
      </div>
    </div>
  </form>
</div>
<br><br><br>
<table class="table table-hover" style="table-layout:fixed;" id="fournisseurTable">
  <tr>
    <th>ID</th>
    <th>Nom</th>
    <th>Wilaya</th>
    <th>Adress</th>
    <th>Information</th>
  </tr>
<?php
$q = "SELECT * from fournisseur";
addToLog($q);
$r = mysqli_query($dbc,$q);
while($fournisseur = mysqli_fetch_assoc($r)){
  ?>
  <tr>
    <td><?= $fournisseur['f_id'] ?></td>
    <td style="word-wrap: break-word;"><?= $fournisseur['f_nom'] ?></td>
    <td><?= @getWilayaInfo($fournisseur['f_wilaya'])['wil_nom'] ?></td>
    <td><?= $fournisseur['adress'] ?></td>
    <td><button type="button" class="btn btn-info btn-md" data-toggle="modal" data-target="#dialog<?= $fournisseur['f_id'] ?>">Information</button></td>
  </tr>
  <div class="modal fade" id="dialog<?= $fournisseur['f_id'] ?>" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?= $fournisseur['f_nom'] ?><span style="float:right;margin-right:20px;"><b>ID:</b> <?= $fournisseur['f_id'] ?></span></h4>
          </div>
          <div class="modal-body">
            <p>
              <ul class="list-group">
                <li class="list-group-item"><b>Nom Prénom:</b> <?= $fournisseur['f_nom'] ?></li>
                <li class="list-group-item"><b>Wilaya:</b> <?= @getWilayaInfo($fournisseur['f_wilaya'])['wil_nom'] ?></li>
                <li class="list-group-item"><b>Téléphone 1:</b> <?= $fournisseur['telephon1'] ?></li>
                <li class="list-group-item"><b>Téléphone 2:</b> <?= $fournisseur['telephon2'] ?></li>
                <li class="list-group-item"><b>Adress:</b> <?= $fournisseur['adress'] ?></li>

              </ul>
            </p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
          </div>
        </div>

      </div>
    </div>
  </div>
  <?php
}

 ?>



</table>

<script>
function filterTable() {
  // Declare variables
  var input, filter, table, tr, td, i;
  input = document.getElementById("searchBar");
  filter = input.value.toUpperCase();
  table = document.getElementById("fournisseurTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[1];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
function filterTableCode() {
  // Declare variables
  var input, filter, table, tr, td, i;
  input = document.getElementById("searchBarCode");
  filter = input.value.toUpperCase();
  table = document.getElementById("fournisseurTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
</script>
<?php
require_once('includes/footer.html');
 ?>
