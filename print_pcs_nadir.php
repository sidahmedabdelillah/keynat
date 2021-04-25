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
include('includes/header.html');
$pcs=[111,102];
?>
<style media="screen">
  @page{margin:0cm 0cm 0cm 0cm;}
</style>
<script type="text/javascript" src="js/JsBarcode.all.min.js"></script>
<script type="text/javascript">

function FormatNumberLength(num, length) {
    var r = "" + num;
    while (r.length < length) {
        r = "0" + r;
    }
    return r;
}  //print();
</script>

</script>
<?php
foreach ($pcs as $key => $value) {
  # code...
  $pc=getPCNadirInfo($value);

 ?>
    <div class="col-md-10 col-md-offset-1">
      <div class="col-md-12">
        <h2 style="text-align:center;">PC N° <?= $pc['pn_id'] ?></h2>
      </div>

      <ul style="font-size:18px;">
        <li>Marque: <?= $pc['marque'] ?></li>
        <li>Processeur: <?= $pc['processeur'] ?></li>
        <li>Carte Graphique: <?= $pc['carte_graphique'] ?></li>
        <li>Ram: <?= $pc['ram'] ?></li>
        <div class="col-md-4" style="float:right;float:bottom">
          <img id="barcode1"/><br><br>
          <h3>Prix: <?= $pc['prix'] ?></h3>
        </div>
        <li>Disque Dur: <?= $pc['dd'] ?></li>
        <li>Taille et Type d'écran: <?= $pc['ecran'] ?></li>
        <li>Batterie: <?= $pc['batterie'] ?></li>
        <li>Webcam intégré: <?= $pc['webcam'] ?></li>
        <li>Lecteur CD/DVD graveur: <?= $pc['graveur'] ?></li>
        <li>Lecteur carte mémoire SD: <?= $pc['sd'] ?></li>
        <li>Lecteur carte SIM: <?= $pc['sim'] ?></li>
        <li>Lecteur d'empreinte: <?= $pc['empreinte'] ?></li>
        <li>Wi Fi / Bluetooth: <?= $pc['wifi'] ?></li>
        <li>Port USB: <?= $pc['usb'] ?></li>
        <li>Port HDMI: <?= $pc['hdmi'] ?></li>
        <li>Port VGA: <?= $pc['vga'] ?></li>
        <li>Couleur: <?= $pc['couleur'] ?></li>
        <li>Poids: <?= $pc['poids'] ?></li>
        <li>Etat: <?= $pc['etat'] ?></li>
      </ul>

        <script type="text/javascript">
        var val=FormatNumberLength(<?= $pc['pn_id'] ?>, 5);
        JsBarcode("#barcode1", val, {
          width: 2,
          height: 100,
          displayValue: false
        });
        </script>
    </div>


<?php
}
  include('includes/footer.html');
?>
