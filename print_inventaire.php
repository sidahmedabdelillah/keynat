<?php
session_start();
if(!isset($_SESSION['prenom'])){
  $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
$page_title="Inventaire";
require_once('includes/header.html');
require_once('connect_hanout.php');
supprimerInventaireVide();

?>
<h1>Inventaire</h1>
<table class="table table-hover" style="table-layout:fixed;" id="articleTable">
  <tr>
    <th style="width:20%;">Cle</th>
    <th>Article</th>
    <th style="width:5%;">CB</th>
    <th style="width:15%;">Qte</th>
  </tr>
      <?php
      if($_SESSION['type']<>1){
        $vendeur=$_SESSION['v_id'];
          $q2 = "SELECT * from inventaire ";
          if(isset($_GET['etat'])){
            $etat=$_GET['etat'];
            $q2.="WHERE etat='$etat' ";
          }else{
              $q2.="WHERE etat='En Attente' ";
          }
          //$q2.=" AND responsable=$vendeur ";
      }else{
        $q2 = "SELECT * from inventaire ";
        if(isset($_GET['etat'])){
          $etat=$_GET['etat'];
          $q2.="where etat='$etat' ";
        }
      }
        $q2.="order by article_id asc";
        addToLog($q2);
        $r2 = mysqli_query($dbc,$q2);
        while($historique = mysqli_fetch_assoc($r2)){
          $designiation = str_replace("eliminer","",getArticleInfo($historique['article_id'])['designiation']);
          $designiation = str_replace("unite", "", $designiation);
          $des= substr($designiation,0,30);
          $designiation = substr($des,0,strrpos($des,' '));
      ?>
  <tr>
    <td style="width:20%;"><?= $historique['article_id'] ?></td>
    <td><?= $designiation ?></td>
    <td style="width:5%;"><?php
    if(!getArticleInfo($historique['article_id'])['codebar']==""){
      echo '1';
    }else{
      echo '0';
    }
    ?></td>
    <td style="width:15%;"><?= getArticleInfo($historique['article_id'])['quantite'] ?></td>
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
  print();
  window.location="inventaire.php";
</script>
