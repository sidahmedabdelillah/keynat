<?php
session_start();
if(!isset($_SESSION['prenom'])){
  $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
$page_title="Modifier";
require_once('includes/header.html');
require_once('connect_hanout.php');
?>
<div class="col-md-12">
<h1 align="center">Modification + désigniation vide</h1><br>

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


<br><br><br>
<table class="table table-hover" style="table-layout:fixed;" id="articleTable">
  <tr>
  <th>Fourn ID </th>
    <th>Nom Fournisseur</th>
    
    <th>Telephone</th>
    <th>Adresse</th>
    <th>Modifier</th>
    
  </tr>
<?php

  $q = "SELECT * from fournisseur ";
  
  addToLog($q);
  $r = mysqli_query($dbc,$q);
  while($art60=mysqli_fetch_array($r, MYSQLI_ASSOC)){
    ?>
    <tr>
    <td><?= $art60['f_id'] ?></td>
      <td style="word-wrap: break-word;"><?= $art60['f_nom']." ".$art60['prenom'] ?></td>
      <td style="word-wrap: break-word;"><?= $art60['telephon1']." ".$art60['telephon2']?></td>
      <td style="word-wrap: break-word;"><?= $art60['adress'] ?></td>
      <td><button type="button" class="btn btn-primary btn-md" data-toggle="modal" data-target="#dialog<?= $art60['f_id'] ?>">Modifier</button></td>
    </tr>
    <div class="modal fade" id="dialog<?= $art60['f_id'] ?>" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content        -->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title"><?= $art60['f_nom'] ?></h4>
            </div>
            <div class="modal-body">
              <p>
                <ul class="list-group">
                  <li class="list-group-item">Nom Fournisseur<input type="text" class="form-control" id="Nom_Fournisseur78<?= $art60['f_id'] ?>" value="<?= $art60['f_nom'] ?>" ></li>
                  <li class="list-group-item">Prenom <input type="text" class="form-control" id="prenom_80<?= $art60['f_id'] ?>" value="<?= $art60['prenom'] ?>" ></li>
                
                  <li class="list-group-item">Premier Telephone<input type="text" class="form-control" id="Telephone78<?= $art60['f_id'] ?>" value="<?= $art60['telephon1'] ?>" ></li>
                
                  <li class="list-group-item">Deuxieme Telephone<input type="text" class="form-control" id="Telephone2_80<?= $art60['f_id'] ?>" value="<?= $art60['telephon2'] ?>" ></li>
                
                  
                  <li class="list-group-item">Adresse<input type="text" class="form-control" id="adress_80<?= $art60['f_id'] ?>" value="<?= $art60['adress'] ?>" ></li>
                
                  <li class="list-group-item">Observation Importante<input type="text" class="form-control" id="obser1_80<?= $art60['f_id'] ?>" value="<?= $art60['obser1'] ?>" ></li>
                
                  <li class="list-group-item">Observation Generale<input type="text" class="form-control" id="obser2_80<?= $art60['f_id'] ?>" value="<?= $art60['obser2'] ?>" ></li>
                
                  <li class="list-group-item">Wilaya Fournisseur <input type="number" class="form-control" id="Wilaya80<?= $art60['f_id'] ?>" value="<?= $art60['f_wilaya'] ?>"></li>
                
                
                

                
                </ul>
              </p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" style="float:left;"   onclick="ModifierFournisseur(<?= $art60['f_id'] ?>);">Modifier</button>
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

<?php
require_once('includes/footer.html');
 ?>
 
