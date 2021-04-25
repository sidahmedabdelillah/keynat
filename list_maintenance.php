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
    <th>Pass</th>
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
    <td><?= $row['pass'] ?></td>
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
          <form class=""  action="modifier_maintenance.php" method="POST">
            <input type="hidden" name="<?= $row['m_id'] ?>_nombre_status" id="<?= $row['m_id'] ?>_nombre_status">
            <input type="hidden" name="<?= $row['m_id'] ?>_nombre_status_editer" id="<?= $row['m_id'] ?>_nombre_status_editer">
            <input type="hidden" name="<?= $row['m_id'] ?>_status_supprimer" id="<?= $row['m_id'] ?>_status_supprimer">
          <div class="modal-body">
            <p>
              <ul class="list-group">

                  Marque:<li class="list-group-item"><input name="marque" id="marque<?= $row['m_id'] ?>" value="<?= $row['marque'] ?>" class="form-control"></li>
                  <br><br>
                  Nom:<li class="list-group-item"><input name="nom" id="nom<?= $row['m_id'] ?>" value="<?= $row['nom'] ?>" class="form-control"></li>
                  <br><br>
                  Téléphone:<li class="list-group-item"><input name="telephone" id="telephone<?= $row['m_id'] ?>" value="<?= $row['telephone'] ?>" class="form-control"></li>

                  <br><br>

                  Probleme:<li class="list-group-item"><input name="problem" id="problem<?= $row['m_id'] ?>" value="<?= $row['problem'] ?>" class="form-control"></li>
                  <br><br>

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
                  <br><br>

                  Résponsable:
                  <select class="form-control" name="responsable">
                    <option value="<?= $responsable ?>"><?= $nom_responsable ?></option>
                    <option value="3">Bessafi hamel</option>
                    <option value="2">Wail Skanderi</option>
                    <option value="6">Torkia Hamraoui</option>
                  </select>
               
                  <!----------  STATUS  ------------>
                  <label for="status" style="margin-top: 40px">Les status: <a id="nouveau_status_<?php echo $row['m_id'] ?>" onclick="new_status(<?php echo $row['m_id'] ?>)">(ajouter)</a></label>

                  <?php
                  //ali
                  $q_status = "SELECT * FROM status WHERE status != 'supprimé' AND maintenance_id = ".$row['m_id'];
                  $r_status = mysqli_query($dbc, $q_status);
                  while($status_row = mysqli_fetch_assoc($r_status)){?>
                    <br>
                      <li class="list-group-item" id="<?= $row['m_id'] ?>_list_item_<?php  echo $status_row['id']; ?>" style="margin-bottom: 4px;max-height: 300px;transition: max-height 0.15s ease-out;">
                        <b><?php  echo $status_row['created_at']; ?></b>
                        <input readonly="" class="form-control" value="<?php  echo $status_row['status_text']; ?>" style="padding: 21px;margin-top: 12px;" name="<?php  echo $row['m_id']; ?>_status_text_exst_<?php  echo $status_row['id']; ?>"
                        id="<?php  echo $row['m_id']; ?>_status_text_exst_<?php  echo $status_row['id']; ?>">
                        <div>
                            <button type="button" class="btn btn-danger" style="float: right; margin: 2px;position: absolute;right: 0px; top: 0;" onclick="delExist(<?php  echo $status_row['id']; ?>, <?php  echo $row['m_id']; ?>)">D</button>
                            <button type="button" class="btn btn-primary" style="float: right; margin: 2px;position: absolute;right: 38px; top: 0;" onclick="editExist(<?php  echo $status_row['id']; ?>, <?php  echo $row['m_id']; ?>)">E</button>
                         </div>
                      </li>
                  <?php }
                  ?>
                  </li>


                  <!----------  Nouveau statut  ------------>
                  <div id="nouveau_status_div_<?php echo $row['m_id'] ?>">
                  <textarea name='<?php echo $row['m_id'] ?>_nouveau_status_1' id='<?php echo $row['m_id'] ?>_nouveau_status_1' style='margin-right:5px;display: none;' rows='2' cols='78' placeholder='Ecrivez un seul statut'></textarea>
                  <textarea name='<?php echo $row['m_id'] ?>_nouveau_status_2' id='<?php echo $row['m_id'] ?>_nouveau_status_2' style='margin-right:5px;display: none;' rows='2' cols='78' placeholder='Ecrivez un seul statut'></textarea>
                  <textarea name='<?php echo $row['m_id'] ?>_nouveau_status_3' id='<?php echo $row['m_id'] ?>_nouveau_status_3' style='margin-right:5px;display: none;' rows='2' cols='78' placeholder='Ecrivez un seul statut'></textarea>
                  <textarea name='<?php echo $row['m_id'] ?>_nouveau_status_4' id='<?php echo $row['m_id'] ?>_nouveau_status_4' style='margin-right:5px;display: none;' rows='2' cols='78' placeholder='Ecrivez un seul statut'></textarea>
                  <textarea name='<?php echo $row['m_id'] ?>_nouveau_status_5' id='<?php echo $row['m_id'] ?>_nouveau_status_5' style='margin-right:5px;display: none;' rows='2' cols='78' placeholder='Ecrivez un seul statut'></textarea>
                  <textarea name='<?php echo $row['m_id'] ?>_nouveau_status_6' id='<?php echo $row['m_id'] ?>_nouveau_status_6' style='margin-right:5px;display: none;' rows='2' cols='78' placeholder='Ecrivez un seul statut'></textarea>
                  </div>
                  <br>
                  <br><br>
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


<script type="text/javascript">
  var clicksNumbre = 0;
  var existArr = [];
  var existDel = [];

  function new_status(m_id){
    clicksNumbre++;
    document.getElementById(m_id+'_nombre_status').value = clicksNumbre;
    /* document.getElementById('nouveau_status_div_'+id).innerHTML += "<textarea name='nouveau_status_"+clicksNumbre+ "' style='margin-right:5px;' rows='2' cols='78' placeholder='Ecrivez un seul statut'></textarea>"; */

    document.getElementById(m_id+'_nouveau_status_'+clicksNumbre).style.display = "inline-block";
    document.getElementById(m_id+'_nouveau_status_'+clicksNumbre).focus();;  
  }

  function editExist(id, m_id){
      existArr.push(id);
      document.getElementById(m_id+'_status_text_exst_'+id).removeAttribute('readonly');
      document.getElementById(m_id+'_nombre_status_editer').value = existArr;
  }

  function delExist(id, m_id){
      existDel.push(id);
      var listData =encodeURI(document.getElementById(m_id+'_list_item_'+id).innerHTML);
      document.getElementById(m_id+'_list_item_'+id).style.maxHeight  = '0';
      document.getElementById(m_id+'_list_item_'+id).style.padding  = '20px';
      document.getElementById(m_id+'_list_item_'+id).innerHTML  = 'Statut supprimé, <a href="list_maintenance.php?code='+m_id+'">Annuler?</a>';
      document.getElementById(m_id+'_status_supprimer').value = existDel;
  }




</script>


<?php
require_once('includes/footer.html');
 ?>
