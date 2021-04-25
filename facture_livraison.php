<?php
session_start();
if(!isset($_SESSION['prenom'])){
    $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
//siPermis($_SESSION['type'],basename(__FILE__, '.php'));
require_once('connect_hanout.php');
require_once('includes/header.html');

?>
<div class="col-md-12">

    <h5 align="center">Bon de livriason N° 1389103</h5>
    <h5 align="center">pour Mlle: Aicha</h5><br>

        <table class="table table-hover" style="table-layout:fixed; font-size:14px;" id="achatListTable">
            <tr>
                <th class="">N° Client</th>
                <td class="">85431</td>
            </tr>
            <tr>
                <th style="width:25%">Nom</th>
                <td style="width:25%">Aicha</td>
            </tr>
            <tr>
                <th style="width:40%">Téléphone</th>
                <td style="width:40%">0659 71 94 14</td>
            </tr>
            <tr>
                <th style="width:20%">Wilaya</th>
                <td style="width:20%">Sidi Belabbess</td>
            </tr>


    </table>
        <div class="col-md-12 toprint" style="text-align:center;">
      <h5>Logiciel fait par: <b>KeynaTech</b><br>0661 25 87 57  Rue de 1er Nov Mascara</h5>
      <h5>www.KeynaTech.com</h5>
    </div>
    <?php
    require_once('includes/footer.html');
    ?>
<script type="text/javascript">
  print();
</script>
