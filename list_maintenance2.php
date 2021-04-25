<?php
session_start();
if(!isset($_SESSION['prenom'])){
  $url=$_SERVER['REQUEST_URI'];
?><script type="text/javascript">location.href='login.php?continue=<?= $url ?>';</script><?php
}
require_once('includes/function_inc.php');

require_once('connect_hanout.php');
$page_title="Liste Maintenance";
require_once('includes/header.html');
calculerStock();
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
?>


<div class="col-xs-12">
<h1 align="center"><?= NOM ?> Maintenance</h1><br>

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
  <a href="list_maintenance.php"><button class="btn btn-default">Toutes <?= getNbrMaintenance(); ?></button></a>
  <a href="list_maintenance.php?etat=En Attente"><button class="btn">En Attente <?= getNbrEnAttente(); ?></button></a>
    <a href="list_maintenance.php?etat=En Cours"><button class="btn btn-primary">En Cours <?= getNbrEnCours(); ?></button></a>
    <a href="list_maintenance.php?etat=Oran"><button class="btn btn-danger">Oran <?= getNbrOran(); ?></button></a>
    <a href="list_maintenance.php?etat=Commande"><button class="btn btn-primary">Commande <?= getNbrCommandeMain(); ?></button></a>
    <a href="list_maintenance.php?etat=Terminé"><button class="btn btn-success">Terminé <?= getNbrTermine(); ?></button></a>
    <a href="list_maintenance.php?etat=Annulé" ><button class="btn btn-warning">Annulé <?= getNbrAnnule(); ?></button></a>
    <a href="list_maintenance.php?rendu" ><button class="btn btn-success">Rendu <?= getNbrRendu(); ?></button></a>
</div>
<br>
<table class="table table-hover" style="table-layout:fixed;" id="articleTable">
  <tr>
    <th  onclick="sortTable(0);">Clé</th>
    <th class="col-xs-3">Marque</th>
    <th>Nom</th>
    <th>Téléphone</th>
    <th>Date</th>
    <th>Etat</th>
    <th>Vendeur</th>
    <th>Résponsable</th>
    <th>Rendu</th>
    <th>Info</th>
  </tr>
<?php
$q = "SELECT * FROM `maintenance` ";
if(isset($_GET['etat'])){
  $etat=$_GET['etat'];
  if($etat=='NadirAttente'){
    $q .= "WHERE `etat`='Nadir' AND `rendu` is null ";
  }elseif($etat=='Commande'){
    $q .= "WHERE `etat`='Commande' AND `rendu` is null ";
  }elseif($etat=='Terminé'){
    $q .= "WHERE `etat`='Terminé' AND `rendu` is null ";
  }elseif($etat=='Annulé'){
    $q .= "WHERE `etat`='Annulé' AND `rendu` is null ";
  }else{
    $q .= "WHERE `etat`='$etat' ";
  }
}elseif(isset($_GET['rendu'])){
  $q .= "WHERE `rendu` is not null ";
}
if(isset($_GET['nom'])){
  $nom=$_GET['nom'];
  $q .= "WHERE `nom`='$nom' ";
}
if(isset($_GET['code'])){
  $code=$_GET['code'];
  $q .= "WHERE `m_id`='$code' ";
}
 $q .="order by `m_id`";
 addToLog($q);
$r = mysqli_query($dbc,$q);
while($row = mysqli_fetch_assoc($r)){
  if(empty($row['responsable']) || $row['responsable']=='0'){
    $responsable=0;
    $nom_responsable='<span style="color:red">Non Défini</span>';
  }else {
    $responsable=$row['responsable'];
    $nom_responsable=getVendeurInfo($responsable)['prenom'];
  }
  ?>
  <tr>
    <td><?= $row['m_id'] ?></td>
    <td style="word-wrap: break-word;"><?= $row['marque'] ?></td>
    <td><?= $row['nom'] ?></td>
    <td><?= $row['telephone'] ?></td>
    <td><?= $row['datetime'] ?></td>
    <td><?= @$row['etat'] ?></td>
    <td><?= @getVendeurInfo($row['v_id'])['prenom'] ?></td>
    <td><?= $nom_responsable ?></td>
    <td><span style="color:green;text-style:bold"><?= $row['rendu'] ?></span></td>
    <td><button type="button" class="btn btn-primary btn-md" data-toggle="modal" data-target="#dialog<?= $row['m_id'] ?>">Information</button></td>
  </tr>
  <div class="modal fade" id="dialog<?= $row['m_id'] ?>" role="dialog" tabindex="-1">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?= $row['m_id'] ?></h4>
          </div>
          <form class="" action="modifier_maintenance.php">
          <div class="modal-body">
            <p>
              <ul class="list-group">

                  <li class="list-group-item">Clé <span class="badge"><?= $row['m_id'] ?></span></li>
                  Marque:<li class="list-group-item"><input name="marque" id="marque<?= $row['m_id'] ?>" value="<?= $row['marque'] ?>" class="form-control"></li>
                  Client:<li class="list-group-item"><input name="client" id="client<?= $row['m_id'] ?>" value="<?= $row['client'].' / '. getClientInfo($row['client'])['nom'] ?>" class="form-control" disabled></li>
                  Nom:<li class="list-group-item"><input name="nom" id="nom<?= $row['m_id'] ?>" value="<?= $row['nom'] ?>" class="form-control"></li>
                  Téléphone:<li class="list-group-item"><input name="telephone" id="telephone<?= $row['m_id'] ?>" value="<?= $row['telephone'] ?>" class="form-control"></li>
                  Probleme:<li class="list-group-item"><input name="problem" id="problem<?= $row['m_id'] ?>" value="<?= $row['problem'] ?>" class="form-control"></li>
                  Etat:
                  <select class="form-control" name="etat">
                    <option value="<?= @$row['etat'] ?>"><?= @$row['etat'] ?></option>
                    <option value="En Attente">En Attente</option>
                    <option value="En Cours">En Cours</option>
                    <option value="Oran">Oran</option>
                    <option value="Terminé">Terminé</option>
                    <option value="Annulé">Annulé</option>
                    <option value="Commande">Commande</option>
                  </select>
                  Résponsable:
                  <select class="form-control" name="responsable">
                    <option value="<?= $responsable ?>"><?= $nom_responsable ?></option>
                    <option value="3">Bessafi hamel</option>
                    <option value="2">Wail Skanderi</option>
                    <option value="6">Torkia Hamraoui</option>
                  </select>
                  Remarque:<li class="list-group-item"><pre><textarea name="remarque" rows="8" cols="80" class="form-control" id="remarque<?= $row['m_id'] ?>"><?= $row['remarque']  ?></textarea></pre></li>
                  Article de maintenance:<li class="list-group-item">
                  <?php
                  $m_id=$row['m_id'];
                  $qs="select * from article_maintenance where m_id=$m_id";
                  $rs=mysqli_query($dbc,$qs);
                  if(mysqli_num_rows($rs)>0){
                      $s=0;
                        echo '<ol>';
                          while($rows=mysqli_fetch_assoc($rs)){
                              $s+=floatval($rows['qte'])*floatval($rows['pv']);
                              ?>
                            <li>Clé: <?= $rows['article_id'] .' / ' . getArticleInfo($rows['article_id'])['designiation'] ?><a href="supprimer_article_maintenace.php?cle=<?= $rows['article_id'] ?>&m_id=<?= $rows['m_id'] ?>"><span class="glyphicon glyphicon-remove"></span></a></li>
                              <ul>
                                <li>Quantité: <?= $rows['qte'] ?></li>
                                <li>Prix Vente: <?= $rows['pv'] ?> DA</li>
                              </ul>
                          <?php }
                        echo '</ul>';
                      echo "<li class=\"list-group-item\" style=\"text-align:center;color: red;\">Total: $s DA </li>";
                   }?>
                  </li>
                  Vendeur:<li class="list-group-item"><input value="<?= getVendeurInfo($row['v_id'])['prenom'] ?>" class="form-control" disabled name="remarque"></li>
                  <li class="list-group-item">Rendu: <span style="color:green;text-style:bold"><?= $row['rendu'] ?></span></li>
                  <input type="hidden" name="article" value="<?= $row['m_id'] ?>">
              </ul>

            </p>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary" style="float:left;">Modifier</button>
            <button type="button" class="btn btn-success" style="float:left;"  onclick="location.href='rendre_maintenance.php?article=<?= $m_id ?>&rendre';">Rendre</button>
            <button type="button" class="btn btn-danger" style="float:left;"  onclick="rendre_maintenance(<?= $row['m_id'] ?>,'nonrendre');">Retour</button>
            <a href="print_maintenance.php?id=<?= $row['m_id'] ?>"><button type="button" class="btn btn-default" style="float:left;">Imprimer</button></a>
            <a href="ajouter_article_maintenance.php?m_id=<?= $row['m_id'] ?>" class="btn btn-primary">Ajouter Article</a>
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
