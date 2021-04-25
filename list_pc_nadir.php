<?php
session_start();
if(!isset($_SESSION['prenom'])){
  $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
$page_title='PC Nadir';
require_once('includes/function_inc.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
require_once('connect_hanout.php');
$page_title="Liste PC Nadir";
require_once('includes/header.html');
calculerStock();
?>


<div class="col-xs-12">
<h1 align="center"><?= NOM ?> PC Nadir</h1><br class="nottoprint">

<div class="col-xs-8 nottoprint">
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
<div class="col-xs-4 nottoprint">
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
<div class="form-actions nottoprint">
  <a href="list_pc_nadir.php"><button class="btn btn-default">Toutes <?= getNbrTtPCNadir(); ?></button></a>
  <a href="list_pc_nadir.php?etat=EnVente"><button class="btn">En Vente <?= getNbrPCNadirEnVente(); ?></button></a>
    <a href="list_pc_nadir.php?etat=VenduNONRendu"><button class="btn btn-primary">Vendu non Rendu <?= getNbrPCNadirVenduNONRendu(); ?></button></a>
    <a href="list_pc_nadir.php?etat=VenduRendu"><button class="btn btn-success">Vendu Rendu <?= getNbrPCNadirVenduRendu(); ?></button></a>
    <a href="list_pc_nadir.php?etat=Annule"><button class="btn btn-danger">Annulé <?= getNbrPCNadirAnnule(); ?></button></a>
    <br><br>
    <a href="ajouter_pc_nadir.php"><button class="btn btn-default">Ajouter PC</button></a>
    <button class="btn btn-primary" onclick="sortTable();">Trier Par Prix</button>
</div>
<ul class="toprint">
  <li>Tous <?= getNbrTtPCNadir(); ?></li>
  <li>En Vente <?= getNbrPCNadirEnVente(); ?></li>
  <li>Vendu non Rendu <?= getNbrPCNadirVenduNONRendu(); ?></li>
  <li>Vendu Rendu <?= getNbrPCNadirVenduRendu(); ?></li>
  <li>Annulé <?= getNbrPCNadirAnnule(); ?></li>
</ul>
<br>
<table class="table table-hover" style="table-layout:fixed;" id="articleTable">
  <tr>
    <th  onclick="sortTable(0);" style="width:5%;">Clé</th>
    <th class="col-xs-3" style="width:50%;">Marque</th>
    <th>Prix</th>
    <th>Etat</th>
    <th class="nottoprint">Information</th>
  </tr>
<?php

$q = "SELECT * FROM `pc_nadir` ";
if(isset($_GET['searchBarCode'])){
  $searchBarCode=$_GET['searchBarCode'];
  $searchBarCode=str_replace("PC", "", $_GET['searchBarCode']);
  $q.=" WHERE pn_id='$searchBarCode'";
}

 if(isset($_GET['etat'])){
   if($_GET['etat']=='EnVente'){
     $q = "SELECT * FROM `pc_nadir` where date_vendu is null AND date_rendu_nadir is null";
   }elseif ($_GET['etat']=='VenduNONRendu') {
     $q = "SELECT * FROM `pc_nadir` where date_vendu is not null AND date_rendu_nadir is null";
   }elseif ($_GET['etat']=='VenduRendu') {
     $q = "SELECT * FROM `pc_nadir` where date_vendu is not null AND date_rendu_nadir is not null";
   }elseif ($_GET['etat']=='Annule') {
     $q = "SELECT * FROM `pc_nadir` where date_vendu is null AND date_rendu_nadir is not null";
   }
 }
  $q .=" order by `pn_id`";
  addToLog($q);
$r = mysqli_query($dbc,$q);
while($row = mysqli_fetch_assoc($r)){
  $passedTime=false;
  if(!empty($row['date_vendu'])){
    $date_vendu=$row['date_vendu'];
    $date_now=date("Y-m-d H:i:s");
    $difference=strtotime($date_now)-strtotime($date_vendu);
    if($difference > 172800){
        $passedTime=true;
    }
    else
    {
     $passedTime=false;
    }
  }
  ?>
  <tr>
    <td style="width:5%;"><?= $row['pn_id'] ?></td>
    <td style="word-wrap: break-word;width:50%;"><?= $row['marque'] ?></td>
    <td><?= $row['prix'] ?></td>
    <td><?php
    if($row['date_vendu']==NULL AND $row['date_rendu_nadir']==NULL){
      echo 'En Vente';
    }elseif($row['date_vendu']!==NULL AND $row['date_rendu_nadir']==NULL){
      echo 'Vendu non Rendu';
      if($passedTime){
        echo '<p style="color:greendma">(à rendre)</p>';
      }else{
        echo '<p style="color:red">(en vérification)</p>';
      }
    }elseif($row['date_vendu']!==NULL AND $row['date_rendu_nadir']!==NULL){
      echo 'Vendu Rendu';
    }elseif($row['date_vendu']==NULL AND $row['date_rendu_nadir']!==NULL){
      echo 'Annulé';
    }


     ?></td>
    <td class="nottoprint"><button type="button" class="btn btn-primary btn-md" data-toggle="modal" data-target="#dialog<?= $row['pn_id'] ?>">Information</button></td>
  </tr>
  <div class="modal fade" id="dialog<?= $row['pn_id'] ?>" role="dialog" tabindex="-1">
  <form action="modifier_pc_nadir.php">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?= $row['pn_id'] ?></h4>
          </div>
          <div class="modal-body">

            <p>
              <ul class="list-group">
                Marque:<li class="list-group-item"><input name="marque" value="<?= $row['marque'] ?>" class="form-control"></li>
                Date Ajout:<li class="list-group-item"><input name="date_ajout" value="<?= $row['date_ajout'] ?>" class="form-control"></li>
                Observation:<li class="list-group-item"><textarea name="obser1" cols="30" rows="5" class="form-control"><?= $row['obser1'] ?></textarea></li>
                Prix:<li class="list-group-item"><input name="prix" value="<?= $row['prix'] ?>" class="form-control"></li>
                Processeur:<li class="list-group-item"><input name="processeur" value="<?= $row['processeur'] ?>" class="form-control"></li>
                Carte Graphique:<li class="list-group-item"><input name="carte_graphique" value="<?= $row['carte_graphique'] ?>" class="form-control"></li>
                Ram:<li class="list-group-item"><input name="ram" value="<?= $row['ram'] ?>" class="form-control"></li>
                Disque Dur:<li class="list-group-item"><input name="dd" value="<?= $row['dd'] ?>" class="form-control"></li>
                Ecran:<li class="list-group-item"><input name="ecran" value="<?= $row['ecran'] ?>" class="form-control"></li>
                Batterie:<li class="list-group-item"><input name="batterie" value="<?= $row['batterie'] ?>" class="form-control"></li>
                Lecteur carte mémoire SD:<li class="list-group-item"><input name="sd" value="<?= $row['sd'] ?>" class="form-control"></li>
                Lecteur CD/DVD graveur:<li class="list-group-item"><input name="graveur" value="<?= $row['graveur'] ?>" class="form-control"></li>
                Lecteur carte SIM:<li class="list-group-item"><input name="sim" value="<?= $row['sim'] ?>" class="form-control"></li>
                Lecteur d’empreinte:<li class="list-group-item"><input name="empreinte" value="<?= $row['empreinte'] ?>" class="form-control"></li>
                Poids:<li class="list-group-item"><input name="poids" value="<?= $row['poids'] ?>" class="form-control"></li>
                Webcam:<li class="list-group-item"><input name="webcam" value="<?= $row['webcam'] ?>" class="form-control"></li>
                Wi Fi / Bluetooth:<li class="list-group-item"><input name="wifi" value="<?= $row['wifi'] ?>" class="form-control"></li>
                Port HDMI:<li class="list-group-item"><input name="hdmi" value="<?= $row['hdmi'] ?>" class="form-control"></li>
                Port VGA:<li class="list-group-item"><input name="vga" value="<?= $row['vga'] ?>" class="form-control"></li>
                Port USB:<li class="list-group-item"><input name="usb" value="<?= $row['usb'] ?>" class="form-control"></li>
                Couleur:<li class="list-group-item"><input name="couleur" value="<?= $row['couleur'] ?>" class="form-control"></li>
                Etat:<li class="list-group-item"><input name="etat" value="<?= $row['etat'] ?>" class="form-control"></li>
                Prix Vendu:<li class="list-group-item"><input id='prix_vendu<?= $row['pn_id'] ?>' name="prix_vendu" value="<?= $row['prix_vendu'] ?>" class="form-control"></li>
                Date Vendu:<li class="list-group-item"><input name="date_vendu" value="<?= $row['date_vendu'] ?>" class="form-control"></li>
                Date Rendu Nadir:<li class="list-group-item"><input name="date_rendu_nadir" value="<?= $row['date_rendu_nadir'] ?>" class="form-control"></li>
              </ul>
            <input type="hidden" name="pn" value="<?= $row['pn_id'] ?>">
            </p>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary" style="float:left;" >Modifier</button>
            <button type="button" class="btn btn-success" style="float:left;"  onclick="rendre_pc_nadir(<?= $row['pn_id'] ?>);">Rendre</button>
            <button type="button" class="btn btn-success" style="float:left;"  onclick="vendre_pc_nadir(<?= $row['pn_id'] ?>);">Vendre</button>
            <a href="print_pc_nadir.php?id=<?= $row['pn_id'] ?>"><button type="button" class="btn btn-default" style="float:left;">Imprimer</button></a>
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
 <script type="text/javascript">
 function sortTable() {
var table, rows, switching, i, x, y, shouldSwitch;
table = document.getElementById("articleTable");
switching = true;
/* Make a loop that will continue until
no switching has been done: */
while (switching) {
  // Start by saying: no switching is done:
  switching = false;
  rows = table.getElementsByTagName("TR");
  /* Loop through all table rows (except the
  first, which contains table headers): */
  for (i = 1; i < (rows.length - 1); i++) {
    // Start by saying there should be no switching:
    shouldSwitch = false;
    /* Get the two elements you want to compare,
    one from current row and one from the next: */
    x = rows[i].getElementsByTagName("TD")[2];
    y = rows[i + 1].getElementsByTagName("TD")[2];
    // Check if the two rows should switch place:
    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
      // I so, mark as a switch and break the loop:
      shouldSwitch= true;
      break;
    }
  }
  if (shouldSwitch) {
    /* If a switch has been marked, make the switch
    and mark that a switch has been done: */
    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
    switching = true;
  }
}
}

 </script>
