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
<div class="col-md-12">
  <?php
  if(isset($_GET['startdate']) AND isset($_GET['finishtdate'])){
    $startdate=$_GET['startdate'];
    $finishdate=$_GET['finishtdate'];
    $q = "SELECT * from vente,article where vente.article_id=article.cle AND (article.seuil2 -article.quantite)>0 AND date(date) BETWEEN '$startdate' AND '$finishdate' ORDER by date desc";
    addToLog($q);
    echo'<h1 align="center">first Historique des ventes entre '.$startdate.' et '.$finishdate.'</h1><br>';
  }
   ?>
<div class="col-md-12">
  <br>
    <form action="30_32_12histo_vente_seui2.php" method="get">
       <div class="col-md-5 form-group">
           <input type="date" name="startdate" value="<?= date("Y-m-j") ?>" class="form-control">
        </div>
       <div class="col-md-5 form-group">
          <input type="date" name="finishtdate" value="<?= date("Y-m-j") ?>" class="form-control">
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
          <th>Quante vente</th>
          <th>Stock Restant</th>
          <th>Seuile2</th>
          <th>Diff Seuil2</th>
          <th>Prix Achat</th>
          <!-- <th>Prix Vente</th> -->
          <!-- <th>Total</th> -->
          <!-- <th>Gain</th> -->
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
          <td><?= getArticleInfo($historique['article_id'])['seuil2'] ?></td>
          <td> <?=getArticleInfo($historique['article_id'])['quantite']-getArticleInfo($historique['article_id'])['seuil2']  ?></td>
          
          <td><?= getArticleInfo($historique['article_id'])['prix_achat'] ?></td>
          <!-- <td><?= $historique['prix_vente'] ?></td> -->
          <!-- <td><?= $historique['qte_vente']*$historique['prix_vente'] ?></td> -->
          <!-- <td> <?= ($historique['prix_vente'] -getArticleInfo($historique['article_id'])['prix_achat'])*$historique['qte_vente'] ?></td> -->
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
