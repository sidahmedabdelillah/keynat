<?php
session_start();
require_once('includes/function_inc.php');
if(!isset($_SESSION['prenom'])){
  $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
$page_title="Historique Changement Place";
require_once('includes/header.html');
require_once('connect_hanout.php');
?>


<div class="col-md-12">
  <?php
  if(isset($_GET['startdate']) AND isset($_GET['finishtdate'])){
    $startdate=$_GET['startdate'];
    $finishdate=$_GET['finishtdate'];
    $q = "SELECT * from changer_place where date(date) BETWEEN '$startdate' AND '$finishdate' ORDER by date desc";
    addToLog($q);
    echo'<h1 align="center">Historique des Changement Place entre '.$startdate.' et '.$finishdate.'</h1><br>';
  }elseif(isset($_GET['article'])){
    $id=$_GET['article'];
    $q = "SELECT * from vente where article_id=$id ORDER by date desc";
    echo'<h1 align="center">Historique des Changement Place de:<h1><h3>' .getArticleInfo($id)['designiation']. '</h3><br>';
  }else {
    $todaydate= date('Y/m/d',mktime(0, 0, 0, date("m")  , date("d"), date("Y")));
    $q = "SELECT * from changer_place where date(date)='$todaydate' ORDER by date desc";
    addToLog($q);
    echo'<h1 align="center">Historique des Changement Place <?= $todaydate ?></h1><br>';
  }
   ?>


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

<div class="col-md-12">
  <br>

<form action="historique_changement_place.php" method="get">
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


      <table class="table table-hover" style="table-layout:fixed;" id="articleTable">
        <tr>
          <th>Numéro Changement</th>
          <th>Article 1</th>
          <th>Article 2</th>
          <th>Date</th>
          <th>Vendeur</th>
        </tr>
            <?php
            $r = mysqli_query($dbc,$q);
            while($historique = mysqli_fetch_assoc($r)){
              ?>
        <tr>
          <td><?= $historique['cp_id'] ?></td>
          <td><?= $historique['article1'] ?></td>
          <td><?= $historique['article2'] ?></td>
          <td><?= $historique['date'] ?></td>
          <td><?= getVendeurInfo($historique['v_id'])['prenom'] ?></td>
        </tr>

        <?php
      }

       ?>

      </table>

  </div>

<?php
require_once('includes/footer.html');
 ?>
