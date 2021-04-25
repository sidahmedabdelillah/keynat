<?php
@session_start();
if(!isset($_SESSION['prenom'])){
  $url=$_SERVER['REQUEST_URI'];
?><script type="text/javascript">location.href='login.php?continue=<?= $url ?>';</script><?php
}
require_once('includes/function_inc.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
$page_title="Inventaire";
require_once('includes/header.html');
require_once('connect_hanout.php');
supprimerInventaireVide();
?>
<div class="col-md-12">
<h1 align="center"><?= NOM ?> - Inventaire à Vérifier</h1><br>
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
      <input type="text" class="form-control" name="code" placeholder="Recherche par Code" id="searchBarCode" onkeydown="filterTableCode()">
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
  <a href="inventaire.php"><button class="btn btn-primary">Toutes <?= getNbrInventaire(); ?></button></a>
  <a href="print_inventaire.php"><button class="btn btn-success">Imprimer Inventaire</button></a>
</div>
<br>
<table class="table table-hover" style="table-layout:fixed;" id="articleTable">
  <tr>
    <th>Cle</th>
    <th>Article</th>
    <th>Date der vente</th>
    <th>NbrP</th>
    <th>QteP</th>
    <th>Mt Perdu</th>
    <th>Prix Vente</th>
    <th>Seuil</th>
    <th>Ven</th>
    <th>Res</th>
    <th>Codebar</th>
    <th>Qte</th>
    <th>Vérifier</th>
  </tr>
      <?php
      if($_SESSION['v_id']<>2){
        $vendeur=$_SESSION['v_id'];
          $q2 = "SELECT * from inventaire ";
          if(isset($_GET['etat'])){
            $etat=$_GET['etat'];
            $q2.="WHERE etat='$etat' ";
          }else{
              $q2.="WHERE etat='En Attente' ";
          }
          if ($_SESSION['type'] == 2 ||$_SESSION['type']==3) {
              $q2.="AND  responsable='$vendeur' ";
          }
      }else{
        $q2 = "SELECT * from inventaire ";
        if(isset($_GET['etat'])){
          $etat=$_GET['etat'];
          $q2.="where etat='$etat' ";
        }else{
          $q2.="WHERE etat='En Attente' ";
        }

      }
        if(isset($_GET['code'])){
          $id=$_GET['code'];
          $q2.=" and article_id=$id ";
        }
        $q2.="order by article_id asc";
        addToLog($q2);
        $r2 = mysqli_query($dbc,$q2);
        while($historique = mysqli_fetch_assoc($r2)){
          $historique_perte=getHistoriquePerte($historique['article_id']);
          $article_info=getArticleInfo($historique['article_id']);
      ?>
  <tr>
    <td><?= $historique['article_id'] ?></td>
    <td style="word-wrap: break-word;"><?= str_replace("eliminer","<em>eliminer</em>",getArticleInfo($historique['article_id'])['designiation']) ?></td>
    <td><?= $historique['date'] ?></td>
    <td><?= $historique_perte['num'] ?></td>
    <td><?= $historique_perte['qte'] ?></td>
    <td><?= $historique_perte['somme'] ?> DA</td>
    <td><?= $article_info['prix_vente'] ?> DA</td>
    <?php
    $seuil=$article_info['seuil'];
     if($seuil==0){
       echo '<td style="color:red">'.$seuil.'</td>';
     }else{
       echo '<td>'.$seuil.'</td>';
     } ?>
    <td><?= getVendeurInfo($historique['vendeur'])['prenom'] ?></td>
    <td><?= getVendeurInfo($historique['responsable'])['prenom'] ?></td>
    <td><input type="text" style="text-align:center;" id="code<?= $historique['article_id'] ?>" class="form-control" value="<?= @$article_info['codebar'] ?>" required></td>
    <td><input type="number" style="text-align:center;" id="qte_reel<?= $historique['article_id'] ?>" class="form-control" value="<?= $article_info['quantite'] ?>"></td>
    <td><button class="btn btn-primary" onclick="verifier_inventaire(<?= $historique['article_id'] ?>);">Vérifier</button></td>
  </tr>

  <?php
}

 ?>

</table>

  </div>

<?php
require_once('includes/footer.html');
 ?>
 <script type="text/javascript">
 function verifier_inventaire(article)
 {
   var qte_reel = document.getElementById('qte_reel'+article).value;
   var code = document.getElementById('code'+article).value;
   if(code.length<2){
     alert('codebar invalide');
   }else{
    $.ajax({
       url: 'verifier_inventaire.php',
       type: 'get',
       data: 'article='+article+'&qte_reel='+qte_reel+'&code='+code,
       success: function(output)
       {
           alert('Inventaire a été vérifié '+output);
           location.reload();
       }, error: function()
       {
           alert('something went wrong, rating failed');
       }
    });
    }
 }
 </script>
