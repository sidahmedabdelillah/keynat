<?php
session_start();
if (!isset($_SESSION['prenom'])) {
    $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
if(!isset($_GET['id'])){
  header('location:list_maintenance.php');
}
require_once('includes/function_inc.php');
require_once('connect_hanout.php');
$page_title="Imprimer Maintenance";
include('includes/header.html');
$maintenance=getMaintenanceInfo($_GET['id']);
?>
<style media="screen">
  @page{margin:0cm 0cm 0cm 0cm;}
</style>
<style>
  td{
    text-align: center;
  }

  @media print
{    
    .no-print, .no-print *
    {
        display: none !important;
    }
}
</style>
<script type="text/javascript" src="js/JsBarcode.all.min.js"></script>
<script type="text/javascript">
function FormatNumberLength(num, length) {
    var r = "" + num;
    while (r.length < length) {
        r = "0" + r;
    }
    return r;
}  print();
</script>
    <div class="col-md-10 col-md-offset-1">
      <div style="text-align:center;">
        <p style="font-size:24px;">Bon N° <?= $maintenance['m_id'] ?></p>
        <img id="barcode1" style="margin-top:-18px;"/>
      </div>
        <table class="table table-hover table-bordered">
            <tr>
              <th style="width:20%">Mrq</th>
              <td><?= $maintenance['marque'] ?></td>
            </tr>
            <tr>
              <th style="width:20%">Nom</th>
              <td><?= $maintenance['nom'] ?></td>
            </tr>
            <tr>
              <th style="width:20%">Pro</th>
              <td><?= $maintenance['problem'] ?></td>
            </tr>
            <tr class="width:20%">
              <th style="width:20%">MDP</th>
              <td><?= $maintenance['pass'] ?></td>
            </tr>
        </table>
        <script type="text/javascript">
        var val=FormatNumberLength(<?= $maintenance['m_id'] ?>, 5);
        JsBarcode("#barcode1", val, {
          width: 2,
          height: 30,
          displayValue: false
        });
        </script>
        <div class="col-md-12 toprint" style="text-align:center;">
          <h6>Pour suivre l'état de votre maintenance visitez: www.keynatech.com/maintenance <br>
          ID: <?= $maintenance['m_id'] ?> | Mot de passe: <?= $maintenance['pass'] ?></h6>
          <h6>Logiciel fait par: <b>KeynaTech</b><br>045718904 Rue de 1er Nov Mascara<br>www.keynatech.com</h6>
        </div>
    </div>

<?php
  include('includes/footer.html');
?>
