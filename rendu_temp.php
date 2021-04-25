<?php
session_start();
if(!isset($_SESSION['prenom'])){
  $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
$page_title="Rendu Temporaire";
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
     url: 'rendre.php',
     type: 'get',
     data: 'article='+<?= $article ?>+'&qte='+<?= $qte ?>,
     success: function(output)
     {
         alert('Le rendu a été validé '+output);
         window.location.href = 'reund_temp.php';
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
  $q="DELETE FROM `rendu_temp` WHERE `rt_id`=$id";
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
<h1 align="center"><?= NOM ?> - Rendu Temporaires</h1><br>


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
    <th>Date de rendu</th>
    <th>Vendeur</th>
    <?php
    if($_SESSION['type']==1){?>
    <th>Valider</th>
    <th>Annuler</th>
  <?php }?>
  </tr>
      <?php
      if($_SESSION['type']<>1){
        $vendeur=$_SESSION['v_id'];
          $q = "SELECT * from rendu_temp WHERE `v_id`='$vendeur' ";
      }else{
        $q = "SELECT * from rendu_temp ";
      }
        $q.="order by cle asc";
        addToLog($q);
        $r = mysqli_query($dbc,$q);
        while($historique = mysqli_fetch_assoc($r)){
      ?>
  <tr>
    <td><?= $historique['cle'] ?></td>
    <td style="word-wrap: break-word;"><?= str_replace("eliminer","<em>eliminer</em>",getArticleInfo($historique['cle'])['designiation']) ?></td>
    <td><?= $historique['qte'] ?></td>
    <td><?= getArticleInfo($historique['cle'])['quantite'] ?></td>
    <td><?= $historique['datetime'] ?></td>
    <td><?= getVendeurInfo($historique['v_id'])['prenom'] ?></td>
    <?php
    if($_SESSION['type']==1){?>
    <td><a href="rendu_temp.php?<?= 'valider&id='.$historique['rt_id'].'&qte='.$historique['qte'].'&article='.$historique['cle'] ?>"><button class="btn btn-primary">Valider</button></a></td>
    <td><a href="rendu_temp.php?annuler&id=<?= $historique['rt_id'] ?>"><button class="btn btn-danger">Annuler</button></a></td>
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
