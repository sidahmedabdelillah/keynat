<?php
session_start();
require_once('includes/function_inc.php');
if(!isset($_SESSION['prenom'])){
  $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
$page_title="Historique Vente";
require_once('includes/header.html');
require_once('connect_hanout.php');
?>
<!--1: partie php -->
<!-- 2:div Search bar designation -->
<!-- 3:DIV Search bar par code -->
<!-- 4:input  -->
<!-- 5:table affichage des donnees -->
<div class="col-md-12">
<!--  1:debut php execute la requete de recherche avec les conditions-->
  <?php
  if(isset($_GET['startdate']) AND isset($_GET['finishtdate'])){
    $startdate=$_GET['startdate'];
    $finishdate=$_GET['finishtdate'];
    $id22=$_GET['nom2'];
    $q = "SELECT * from vente where date(date) BETWEEN '$startdate' AND '$finishdate' AND vendeur_id =$id22 ORDER by date desc";
    addToLog($q);
    echo'<h1 align="center">first Historique des ventes entre '.$startdate.' et '.$finishdate.'</h1><br>';
  }elseif(isset($_GET['nom2'])){
    $id=$_GET['nom2'];
    $q = "SELECT * from vente where   date(date) BETWEEN '$startdate' AND '$finishdate'  AND vendeur_id =$id  ORDER by date desc";
    echo'<h1 align="center"> second Historique des ventes de:<h1><h3>' .getArticleInfo($id)['designiation']. '</h3><br>';
  }else {
    $todaydate= date('Y/m/d',mktime(0, 0, 0, date("m")  , date("d"), date("Y")));
    $q = "SELECT * from vente where date(date)='$todaydate' ORDER by date desc";
    addToLog($q);
    echo'<h1 align="center">third Historique des ventes <?= $todaydate ?></h1><br>';
  }
   ?>


<!--2: debut DIV input par search bar:  partie input les donnees start et finich date -->
      <!--id search bar  pour prendre les donnees de recherche  -->
<div class="col-md-8">
  <form>
    <div class="input-group">
      <div class="col-md-4">
        <input type="text" class="form-control" placeholder="Recherche par Désignation" id="searchBar" onkeydown="filterTable()"><br>
      </div>
      <div class="col-md-4">
        <input type="text" class="form-control" placeholder="Recherche par Désignation" id="searchBar2" onkeydown="filterTable()"><br>
      </div>
      <div class="col-md-4">
        <input type="text" class="form-control" placeholder="Recherche par Désignation" id="searchBar3" onkeydown="filterTable()"><br>
      </div>
      <div class="input-group-btn">
        <button class="btn btn-default" type="submit">
          <i class="glyphicon glyphicon-search"></i>
          <button class="btn btn-default" type="button" onclick="resetBtn();">Reset</button><br>
        </button>
      </div>
    </div>
  </form>
</div>


<!-- 3:debut DIV input par code bar prendre les donnees de recherche  -->
<div class="col-md-4">
  <form>
    <div class="input-group">
      <input type="text" class="form-control" placeholder="Recherche par Code" id="searchBarCode" name="article" onkeydown="filterTableCode()">
     
      <div class="input-group-btn">
        <button class="btn btn-default" type="submit">
          <i class="glyphicon glyphicon-search"></i>
            <button class="btn btn-default" type="button" onclick="resetBtn();">Reset</button><br>
        </button>
      </div>
    </div>

  </form>
</div>

<!--4 Parte des input des donnees  -->
<div class="col-md-12">
  <br>
    <form action="70_1_10Vente_Vendeur.php" method="get">
       <div class="col-md-2 form-group">
           <input type="date" name="startdate" value="<?= date("Y-m-j") ?>" class="form-control">
        </div>
       <div class="col-md-2 form-group">
          <input type="date" name="finishtdate" value="<?= date("Y-m-j") ?>" class="form-control">
       </div>
       
       <div class="col-md-2">
        <input type="text"  name="nom2" class="form-control" placeholder="Search by name"  ><br>
      </div>
       
       <div class="col-md-2 form-group">
             <button type="submit" class="btn btn-primary btn-block">Filter</button>
       </div>
   
      </form>
</div>

 <br><br>

<!--5: la table affichage des donnees avec leur entetes -->
      <table class="table table-hover" style="table-layout:fixed;" id="articleTable">
        <tr>
          <th>Numéro article</th>
          <th>Article</th>
          <th>Quantité</th>
          <th>Restant</th>
          <th>Prix Achat</th>
          <th>Prix Vente</th>
          <th>Total</th>
          <th>Gain</th>
          <th>Date de vente</th>
          <th>Vendeur</th>
        </tr>
            <?php
            $r = mysqli_query($dbc,$q);
            $summ=0;
            while($historique = mysqli_fetch_assoc($r)){

              ?>
        <tr>
          <td><?= $historique['article_id'] ?></td>
          <td style="word-wrap: break-word;"><?= str_replace("eliminer","<em>eliminer</em>",getArticleInfo($historique['article_id'])['designiation']) ?></td>
          <td><?= $historique['qte_vente'] ?></td>
          <td><?= getArticleInfo($historique['article_id'])['quantite'] ?></td>
          <td><?= getArticleInfo($historique['article_id'])['prix_achat'] ?></td>
          <td><?= $historique['prix_vente'] ?></td>
          <td><?= $historique['qte_vente']*$historique['prix_vente'] ?></td>
          <td> <?= ($historique['prix_vente'] -getArticleInfo($historique['article_id'])['prix_achat'])*$historique['qte_vente'] ?></td>
          <td><?= $historique['date'] ?></td>
          <td><?= getVendeurInfo($historique['vendeur_id'])['prenom'] ?></td>
        </tr>

        <?php
        $summ+=($historique['qte_vente']*$historique['prix_vente']);
      }

       ?>

      </table>
      <h2 align="right">La somme est de: <?= $summ ?> DA</h2>

  </div>

<?php
require_once('includes/footer.html');
 ?>
