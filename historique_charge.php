<?php
session_start();
if(!isset($_SESSION['prenom'])){
  $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
$page_title="Historique Charge";
require_once('includes/header.html');
require_once('connect_hanout.php');
?>


<div class="col-md-12">
<h1 align="center"><?= NOM ?> - Historique des charges</h1>
<h3 align="center">Charge de mois: <?= getChargeMois($month) ?> DA</h3><br>

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
          <th>Numéro article</th>
          <th>Article</th>
          <th>Quantité</th>
          <th>Restant</th>
          <th>Prix Achat</th>
          <th>Total</th>
          <th>Date de charge</th>
          <th>Vendeur</th>
        </tr>
            <?php
            $q = "SELECT * from charge where month(date)=month(CURRENT_DATE) order by date DESC";
            addToLog($q);
            $r = mysqli_query($dbc,$q);
            while($historique = mysqli_fetch_assoc($r)){
              if(is_null($historique['a_id'])){
                $des=$historique['obser1']." <em>externe</em>";
              }else{
                $des=str_replace("eliminer","<em>eliminer</em>",getArticleInfo($historique['a_id'])['designiation']);
              }
              ?>
        <tr>
          <td><?= $historique['a_id'] ?></td>
          <td style="word-wrap: break-word;"><?= $des ?></td>
          <td><?= $historique['qte'] ?></td>
          <td><?= getArticleInfo($historique['a_id'])['quantite'] ?></td>
          <td><?= $historique['pa'] ?></td>
          <td><?= $historique['qte']*$historique['pa'] ?></td>
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
