<?php
session_start();
if(!isset($_SESSION['prenom'])){
  $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
$page_title='Liste des clients';
require_once('includes/function_inc.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
require_once('connect_hanout.php');
$page_title="Liste Client";
require_once('includes/header.html');
?>


<div class="col-xs-12">
<h1 align="center"><?= NOM ?> Liste des clients</h1><br>

<div class="col-xs-8">
  <form>
    <div class="input-group">
      <div class="col-xs-4">
        <input type="text" class="form-control" placeholder="Recherche par Désignation" id="searchBar" onkeydown="filterTable()">
      </div>

      <div class="col-xs-4">
        <input type="text" class="form-control" placeholder="Recherche par Désignation" id="searchBar2" onkeydown="filterTable()">
      </div>

      <div class="col-xs-4">
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
<div class="col-xs-4">
  <form>
    <div class="input-group">
      <input type="text" class="form-control" placeholder="Recherche par Code" name="searchBarCode" id="searchBarCode" onkeydown="filterTableCode()" autofocus name="code">
      <div class="input-group-btn">
        <button class="btn btn-default" type="submit">
          <i class="glyphicon glyphicon-search"></i>
            <button class="btn btn-default" type="button" onclick="resetBtn();">Reset</button>
        </button>
      </div>
    </div>

  </form>
</div>

<br><br><br>
<table class="table table-hover" style="table-layout:fixed;" id="articleTable">
  <tr>
    <th>Clé</th>
    <th class="col-xs-3">Cilent</th>
    <th>Crédit</th>
    <th>Information</th>
    <th>Modifier</th>
  </tr>
<?php

$q = "SELECT * FROM `client` ";
  $q .=" order by `client_id`";
  addToLog($q);
$r = mysqli_query($dbc,$q);
while($row = mysqli_fetch_assoc($r)){
  ?>
  <tr>
    <td><?= $row['client_id'] ?></td>
    <td style="word-wrap: break-word;"><?= $row['nom']." ".$row['prenom'] ?></td>
    <td><?= $row['credit'] ?></td>
    <td><a href="client_information.php?id=<?= $row['client_id'] ?>"><button type="button" name="button" class="btn btn-primary">Information</button></a></td>
    <td><button type="button" class="btn btn-danger btn-md" data-toggle="modal"
            data-target="#dialog<?= $row['client_id'] ?>">Modifier</button></td>
  </tr>
  <div class="modal fade" id="dialog<?= $row['client_id'] ?>" role="dialog" tabindex="-1">
      <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title"><?= $row['nom']." ".$row['prenom'] ?></h4>
              </div>
              <div class="modal-body">
                  <ul class="list-group">
                      Nom:
                      <li class="list-group-item"><input type="text"
                                                         id="nom<?= $row['client_id'] ?>"
                                                         value="<?= $row['nom'] ?>"
                                                         class="form-control"></li>
                       Prénom:
                       <li class="list-group-item"><input type="text"
                                                          id="prenom<?= $row['client_id'] ?>"
                                                          value="<?= $row['prenom'] ?>"
                                                          class="form-control"></li>
                      Adresse:
                      <li class="list-group-item"><input type="text"
                                                         id="adresse<?= $row['client_id'] ?>"
                                                         value="<?= $row['adress'] ?>"
                                                         class="form-control"></li>
                       Email:
                       <li class="list-group-item"><input type="email"
                                                          id="email<?= $row['client_id'] ?>"
                                                          value="<?= $row['email'] ?>"
                                                          class="form-control"></li>
                      Type de client:
                      <li class="list-group-item"><input
                                                         id="type<?= $row['client_id'] ?>"
                                                         value="<?= $row['type'] ?>"
                                                         class="form-control disabled"></li>
                      Téléphone 1:
                      <li class="list-group-item"><input type="text"
                                                         id="telephone1<?= $row['client_id'] ?>"
                                                         value="<?= $row['telephone1'] ?>"
                                                         class="form-control"></li>
                       Téléphone 2:
                       <li class="list-group-item"><input type="text"
                                                          id="telephone2<?= $row['client_id'] ?>"
                                                          value="<?= $row['telephone2'] ?>"
                                                          class="form-control"></li>
                      N° RC:
                      <li class="list-group-item"><input type="text"
                                                         id="n_rc<?= $row['client_id'] ?>"
                                                         value="<?= $row['n_rc'] ?>"
                                                         class="form-control"></li>
                       N° Art.Impo:
                       <li class="list-group-item"><input type="text"
                                                          id="n_ai<?= $row['client_id'] ?>"
                                                          value="<?= $row['n_ai'] ?>"
                                                          class="form-control"></li>
                      Mat.Fisc:
                      <li class="list-group-item"><input type="text"
                                                         id="n_mf<?= $row['client_id'] ?>"
                                                         value="<?= $row['n_mf'] ?>"
                                                         class="form-control"></li>
                       Obser 1:
                       <li class="list-group-item"><input type="text"
                                                          id="obser1<?= $row['client_id'] ?>"
                                                          value="<?= $row['n_ai'] ?>"
                                                          class="form-control"></li>
                      Obser 2:
                      <li class="list-group-item"><input type="text"
                                                         id="obser2<?= $row['client_id'] ?>"
                                                         value="<?= $row['n_mf'] ?>"
                                                         class="form-control"></li>
                  </ul>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" style="float:left;"
                          onclick="modifierClient(<?= $row['client_id'] ?>);">Modifier Client
                  </button>
                  </button>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
              </div>
          </div>

      </div>
  </div>
  <?php
}

 ?>

</table>

<?php
require_once('includes/footer.html');
 ?>
 <script type="text/javascript">
     function modifierClient(client) {
         var nom = document.getElementById('nom' + client).value;
         var prenom = document.getElementById('prenom' + client).value;
         var address = document.getElementById('adresse' + client).value;
         var email = document.getElementById('email' + client).value;
         var type = document.getElementById('type' + client).value;
         var telephone1 = document.getElementById('telephone1' + client).value;
         var telephone2 = document.getElementById('telephone2' + client).value;
         var n_rc = document.getElementById('n_rc' + client).value;
         var n_ai = document.getElementById('n_ai' + client).value;
         var n_mf = document.getElementById('n_mf' + client).value;
         var obser1 = document.getElementById('obser1' + client).value;
         var obser2 = document.getElementById('obser2' + client).value;
         $.ajax({
             url: 'modifier_client.php',
             type: 'get',
             data: 'client=' + client + '&nom=' + nom + '&prenom=' + prenom+ '&address=' + address + '&email=' + email+ '&type=' + type + '&telephone1=' + telephone1+ '&telephone2=' + telephone2 + '&n_rc=' + n_rc+ '&n_ai=' + n_ai + '&n_mf=' + n_mf+ '&obser1=' + obser1 + '&obser2=' + obser2,
             success: function (output) {
                 alert('Le client a été modifié'+output);
                 location.reload();
             }, error: function () {
                 alert('something went wrong, rating failed');
             }
         });
     }
 </script>
