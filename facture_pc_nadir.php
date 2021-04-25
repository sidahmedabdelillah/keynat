<?php
session_start();
if(!isset($_SESSION['prenom'])){
    $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
require_once('connect_hanout.php');
if(isset($_GET['id'])){
  $id=$_GET['id'];
  $facture=getPCNadirInfo($id);
  $page_title="Bon PC N° ".$id;
}else{
  header("location:list_pc_nadir.php");
}
$page_title="Facture PC Nadir N°".$id;
require_once('includes/header.html');

?>
<div class="col-md-12">

    <h4 align="center"><?= "Bon de vente PC N° ".$id; ?></h4>

        <table class="table table-hover" style="table-layout:fixed; font-size:10px;" id="achatListTable">
            <tr>
                <th class="nottoprint">Clé</th>
                <th class="col-md-6" style="width:45%">Marque</th>
                <th style="width:20%">PV</th>
            </tr>

            <tr>
                <td class="nottoprint"><?= $facture['pn_id'] ?></td>
                <td style="word-wrap: break-word;" class="col-md-6" style="width:45%"><?=  $facture['marque'] ?></td>
                <td style="width:15%"><?= number_format($facture['prix_vendu'],0) ?></td>
            </tr>

    </table>
    <div class="col-md-4 nottoprint" style="float:right;font-size:20px;text-align:right;">
      <div class="col-md-6">
        <b>Total: </b>
      </div>
      <div class="col-md-6" style="color:red;">
        <b><?= number_format($facture['prix_vendu'],2) ?>DA</b>
      </div>
    </div>

    <div class="col-md-12">
      <div style="text-align:center;">
        <h4>Total: <?= number_format($facture['prix_vendu'],2) ?>DA</h4>
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
