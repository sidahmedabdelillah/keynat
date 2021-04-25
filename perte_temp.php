<?php
session_start();
if(!isset($_SESSION['prenom'])){
  $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
$page_title="Perte Temporaire";
require_once('includes/header.html');
require_once('connect_hanout.php');
if(isset($_GET['valider'])){
  $id=$_GET['id'];
  $article=$_GET['article'];
  $qte=$_GET['qte'];
  ?>
  <script src="js/jquery.js"></script>
  <script type="text/javascript">
  $.ajax({
     url: 'perte.php',
     type: 'get',
     data: 'article='+<?= $article ?>+'&qte='+<?= $qte ?>,
     success: function(output)
     {
         alert('La perte a été validé '+output);
         window.location.href = 'perte_temp.php?etat=En%20Attente';
     }, error: function()
     {
         alert('something went wrong, rating failed');
     }
  });
  </script>
  <?php
  $q="DELETE FROM `perte_temp` WHERE `perte_temp_id`=$id";
  addToLog($q);
  $r=mysqli_query($dbc,$q);
  if($r){
    //
  }else{
    echo mysqli_error($dbc);
    exit();
  }
}elseif(isset($_GET['annuler'])){
  $id=$_GET['id'];
  $q="DELETE FROM `perte_temp` WHERE `perte_temp_id`=$id";
  addToLog($q);
  $r=mysqli_query($dbc,$q);
  if($r){
    //
  }else{
    echo mysqli_error($dbc);
    exit();
  }
  ?>
  <script type="text/javascript">
    alert('La perte a été annulé ');
    window.location.href = 'perte_temp.php';
  </script>
  <?php
}
?>


<div class="col-md-12">
<h1 align="center"><?= NOM ?> - Pertes Temporaires</h1><br>


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

<br><br><br>
<table class="table table-hover" style="table-layout:fixed;" id="articleTable">
  <tr>
    <th>Numéro Article</th>
    <th>Article</th>
    <th>Quantité</th>
    <th>Quantité Stock</th>
    <th>Date de perte</th>
    <th>Etat</th>
    <th>Vendeur</th>
    <th>Résponsable</th>
    <?php
    if($_SESSION['type']==1){?>
    <th>Valider</th>
    <th>Annuler</th>
  <?php }?>
  </tr>
      <?php
      if($_SESSION['type']<>1){
        $vendeur=$_SESSION['v_id'];
          $q = "SELECT * from perte_temp WHERE `reponsable`='$vendeur' ";
          if(isset($_GET['etat'])){
            $etat=$_GET['etat'];
            $q.="AND etat='$etat' ";
          }
      }else{
        $q = "SELECT * from perte_temp ";
        if(isset($_GET['etat'])){
          $etat=$_GET['etat'];
          $q.="where etat='$etat' ";
        }
      }

        $q.="order by article_id asc";
        addToLog($q);
        echo $q;
        $r = mysqli_query($dbc,$q);
        while($historique = mysqli_fetch_assoc($r)){
      ?>
  <tr>
    <td><?= $historique['article_id'] ?></td>
    <td style="word-wrap: break-word;"><?= str_replace("eliminer","<em>eliminer</em>",getArticleInfo($historique['article_id'])['designiation']) ?></td>
    <td><?= $historique['qte_perdu'] ?></td>
    <td><?= getArticleInfo($historique['article_id'])['quantite'] ?></td>
    <td><?= $historique['date'] ?></td>
    <td><?= $historique['etat'] ?></td>
    <td><?= getVendeurInfo($historique['vendeur_id'])['prenom'] ?></td>
    <td><?php
    if($historique['article_id']>$config['middle']){
      echo getVendeurInfo(6)['prenom'];
    }else{
      echo getVendeurInfo(10)['prenom'];
    }?></td>
    <?php
    if($_SESSION['type']==1){?>
    <td><a href="perte_temp.php?<?= 'valider&id='.$historique['perte_temp_id'].'&qte='.$historique['qte_perdu'].'&article='.$historique['article_id'] ?>"><button class="btn btn-primary">Valider</button></a></td>
    <td><a href="perte_temp.php?annuler&id=<?= $historique['perte_temp_id'] ?>"><button class="btn btn-danger">Annuler</button></a></td>
  <?php } ?>
  </tr>

  <?php
}

 ?>

</table>

  </div>

<?php
require_once('includes/footer.html');
 ?>
