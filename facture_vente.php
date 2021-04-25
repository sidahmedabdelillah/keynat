<?php
session_start();
if(!isset($_SESSION['prenom'])){
    $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
require_once('connect_hanout.php');
if(isset($_GET['id'])){
  $id=$_GET['id'];
  $facture=getFactureInfo($id);
  $client=getClientInfo($facture['client_id']);
  $page_title="Facture N° ".$id;
}else{
  header("location:list_client.php");
}
require_once('includes/header.html');

?>
<div class="col-md-12">

    <h6 align="center"><?= "Bon N° ".$id; ?></h6>
    <h6 align="center">pour Mr: <?= $client['nom']." ".$client['prenom'] ?></h6><br>

        <table class="table table-hover" style="table-layout:fixed; font-size:10px;" id="achatListTable">
            <tr>
                <th style="width:10%">Clé</th>
                <th class="col-md-6" style="width:35%">Des</th>
                <th style="width:15%">Qte</th>
                <th style="width:20%">PV</th>
                <th style="width:20%">Total</th>
            </tr>
          <?php
          $q="SELECT * FROM `vente` WHERE `facture_vente_id`=$id;";
          addToLog($q);
          $r=mysqli_query($dbc,$q);
          $s=0;
          while($row=mysqli_fetch_assoc($r)){
            $s+=$row['qte_vente']*$row['prix_vente'];
            $designiation = str_replace("eliminer","",getArticleInfo($row['article_id'])['designiation']);
            $designiation = str_replace("unite", "", $designiation);
            $des= substr($designiation,0,30);
            $designiation = substr($des,0,strrpos($des,' '));
            ?>
            <tr>
                <td style="width:10%"><?= $row['article_id'] ?></td>
                <td style="word-wrap: break-word;" class="col-md-6" style="width:35%"><?=  $designiation ?></td>
                <td style="width:15%"><?= $row['qte_vente'] ?></td>
                <td style="width:20%"><?= number_format($row['prix_vente'],0) ?></td>
                <td style="width:20%"><?= number_format($row['qte_vente']*$row['prix_vente'],0) ?></td>
            </tr>
            <?php
          }
            ?>

    </table>
    <div class="col-md-4 nottoprint" style="float:right;font-size:20px;text-align:right;">
      <div class="col-md-6">
        <b>Total: </b>
      </div>
      <div class="col-md-6" style="color:red;">
        <b><?= number_format($s,2) ?>DA</b>
      </div>

      <div class="col-md-6 ">
        <b>Crédit Précédent: </b>
      </div>
      <div class="col-md-6" style="color:green;">
        <b><?= number_format($client['credit'],2) ?>DA</b>
      </div>

      <div class="col-md-6">
        <b>Versement: </b>
      </div>
      <div class="col-md-6" style="color:blue;">
        <b><?= number_format(getVersementFacture($id)['val'],2) ?>DA</b>
      </div>
    </div>

    <div class="col-md-12">
      <div style="text-align:center;">
        <h4>Total: <?= number_format($s,2) ?>DA</h4>
      </div>
      <div style="text-align:center;">
        <h6><?=date('Y-m-d H:i:s')?></h6>
      </div>
    </div>

    <div class="col-md-12 toprint" style="text-align:center;">
      <h6>Logiciel fait par: <b>KeynaTech</b><br>045718904 Rue de 1er Nov Mascara</h6>
      <h6>www.KeynaTech.com</h6>
    </div>
    <?php
    require_once('includes/footer.html');
    ?>
<script type="text/javascript">
  print();
</script>
