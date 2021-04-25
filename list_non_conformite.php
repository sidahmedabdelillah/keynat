<?php
session_start();
if(!isset($_SESSION['prenom'])){
  $url=$_SERVER['REQUEST_URI'];
?><script type="text/javascript">location.href='login.php?continue=<?= $url ?>';</script><?php
}
require_once('includes/function_inc.php');

require_once('connect_hanout.php');
$page_title="Liste Non Confirmite";
require_once('includes/header.html');
calculerStock();
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
?>


<div class="col-xs-12">
<h1 align="center"><?= NOM ?> Non Confirmite</h1><br>

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
      <input type="text" class="form-control" placeholder="Recherche par Code" id="searchBarCode" onkeydown="filterTableCode()" autofocus name="code">
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
<div class="form-actions">
  <a href="list_non_conformite.php"><button class="btn btn-default">Toutes <?= getNbrNonConformite(); ?></button></a>
  <a href="list_non_conformite.php?etat=En Attente"><button class="btn btn-warning">En Attente <?= getNbrNonConformite('En Attente'); ?></button></a>
    <a href="list_non_conformite.php?etat=Terminé"><button class="btn btn-primary">Terminé <?= getNbrNonConformite('Terminé'); ?></button>
    <a href="list_non_conformite.php?etat=Fermé"><button class="btn btn-success">Fermé <?= getNbrNonConformite('Fermé'); ?></button></a><br><br>
    <a href="ajouter_non_confirmite.php"><button class="btn btn-danger">Ajouter Non Conformite</button></a>
</div>
<br>
<table class="table table-hover" style="table-layout:fixed;" id="articleTable">
  <tr>
    <th>Clé</th>
    <th>Vendeur</th>
    <th>Date Création</th>
    <th class="col-xs-3">Déscription</th>
    <th>Sur</th>
    <th>Info</th>
  </tr>
<?php
$q = "SELECT * FROM `non_confirmite` ";
if(isset($_GET['code'])){
  $code=$_GET['code'];
  $q .= "WHERE `nc_id`='$code' ";
  $vendeur=$_SESSION['v_id'];
  if(ownNonConforimite($code,$vendeur)){
    seenNonConformite($code);
  }
}elseif(isset($_GET['etat'])){
  $etat=$_GET['etat'];
  if($etat=='En Attente'){
    $q.="WHERE (action is null OR action='')";
  }elseif($etat=="Terminé"){
    $q.="WHERE (classement is null OR classement='') and (action is not null and action!='')";
  }elseif($etat=="Fermé"){
    $q.="WHERE (classement is not null OR classement!='') and (action is not null and action!='')";
  }
}
 $q .=" order by date_creation desc ";
 addToLog($q);
$r = mysqli_query($dbc,$q);
while($row = mysqli_fetch_assoc($r)){
  $sur=$row['qui_fait_action'];
  $nom_sur=getVendeurInfo($sur)['prenom'];
  ?>
  <tr>
    <td><?= $row['nc_id'] ?></td>
    <td><?= getVendeurInfo($row['v_id'])['prenom'] ?></td>
    <td><?= $row['date_creation'] ?></td>
    <td style="word-wrap: break-word;"><?= $row['description'] ?></td>
    <td><?= $nom_sur ?></td>
    <td><button type="button" class="btn btn-primary btn-md" data-toggle="modal" data-target="#dialog<?= $row['nc_id'] ?>">Information</button></td>
  </tr>
  <div class="modal fade" id="dialog<?= $row['nc_id'] ?>" role="dialog" tabindex="-1">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?= $row['nc_id'] ?></h4>
          </div>
          <form class="" action="modifier_non_confirmite.php">
          <div class="modal-body">
            <p>
              <ul class="list-group">
                  <h4 align="center">Partie Reclameur</h4>
                  <li class="list-group-item">Clé <span class="badge"><?= $row['nc_id'] ?></span></li>
                  <li class="list-group-item">Vendeur: <?= getVendeurInfo($row['v_id'])['prenom'] ?></li>
                  <li class="list-group-item">Date Création: <?= $row['date_creation'] ?></li>
                  <li class="list-group-item">Déscription: <?= $row['description'] ?></li>
                  <li class="list-group-item">Impact: <?= $row['impact'] ?></li>
                  <h4 align="center">Partie Résponsable</h4>
                  <?php
                  if($row['v_id']==$_SESSION['v_id'] || $_SESSION['type']==1){?>
                  Sur:<select class="form-control" name="responsable">
                    <option value="<?= $sur ?>"><?= $nom_sur ?></option>
                    <option value="3">Bessafi hamel</option>
                    <option value="2">Wail Skanderi</option>
                    <option value="6">Torkia Hamraoui</option>
                    <option value="7">Abdennour Mehdi</option>
                    <option value="10">Wassim Makhlouf</option>
                  </select>
                <?php }else{?>
                  <li class="list-group-item">Sur: <?= $nom_sur ?></li>
                  <input type="hidden" name="responsable" value="<?= $sur ?>">
                  <?php
                }
                  if($row['qui_fait_action']==$_SESSION['v_id'] || $_SESSION['type']==1){?>
                  Action:<li class="list-group-item"><input value="<?= $row['action'] ?>" class="form-control" name="action"></li>
                  Pour Quand:<li class="list-group-item"><input  type="date" value="<?= $row['quand'] ?>" class="form-control" name="quand"></li>
                  Observation:<li class="list-group-item"><input value="<?= $row['obser1'] ?>" class="form-control" name="obser1"></li>
                <?php }else{ ?>
                  <li class="list-group-item">Action: <?= $row['action'] ?></li>
                  <li class="list-group-item">Quand: <?= $row['quand'] ?></li>
                  <li class="list-group-item">Observation: <?= htmlspecialchars($row['obser1']) ?></li>
                <?php }?>
                <h4 align="center">Partie évaluation</h4>
                <?php
                if($_SESSION['type']==1){?>
                Classement:<li class="list-group-item"><input value="<?= $row['classement'] ?>" class="form-control" name="classement"></li>
                Action corrective:<li class="list-group-item"><input value="<?= $row['action_correct'] ?>" class="form-control" name="action_correct"></li>
              <?php }else{ ?>
                <li class="list-group-item">Classement: <?= htmlspecialchars($row['classement']) ?></li>
                <li class="list-group-item">Action corrective: <?= htmlspecialchars($row['action_correct']) ?></li>
              <?php }?>
                  <input type="hidden" name="nc_id" value="<?= $row['nc_id'] ?>">
              </ul>

            </p>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary" style="float:left;">Modifier</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
          </div>
          </form>
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
