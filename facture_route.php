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
  if(isset($_GET['obser'])){
    $obser=mysqli_real_escape_string($dbc,trim($_GET['obser']));
    $q="UPDATE `facture_vente` SET `obser1`='$obser' WHERE `facture_vente_id`=$id";
    $r=mysqli_query($dbc,$q);
    if($r){
      //
    }else{
      echo mysqli_error($dbc);
      exit();
    }
  }
  $facture=getFactureInfo($id);
  $client=getClientInfo($facture['client_id']);
  $page_title="Facture N° ".$id;
  $nmrFacture=getNmrFactureVentreParAnnee($id)['count(*)'];
  $yearFacture=getNmrFactureVentreParAnnee($id)['year(date_vente)'];
}else{
  header("location:list_client.php");
}
require_once('includes/header.html');

?>
<style>
.column {
  float: left;
  width: 40%;
  margin-left: 50px;
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}
#tablePrix td{
  text-align: right;
}
body {
  height: 200vh;
  padding-bottom: 40px;
}

.footer {
  left: 0;
  bottom: 0;
  position: fixed;
  width: 100%;
  height: 180px;
}
</style>
<div class="row">
  <div class="column">
    <table>
      <tr>
        <td><b>HAMEL BESSAFI:</b></td>
      </tr>
      <tr>
        <td><b>Adr:</b> <?= getConfig('adr')['c_val'] ?></td>
      </tr>
      <tr>
        <td><b>Tél:</b> <?= getConfig('telephone')['c_val'] ?></td>
      </tr>
      <tr>
        <td><b>N° Registre:</b> <?= getConfig('n_rc')['c_val'] ?></td>
      </tr>
      <tr>
        <td><b>Code Article:</b> <?= getConfig('code_article')['c_val'] ?></td>
      </tr>
      <tr>
        <td><b>N° Compte Bancaire Societe Générale Algérie:</b> <?= getConfig('n_compte')['c_val'] ?></td>
      </tr>
      <tr>
        <td><b>N° Art.Impot.:</b> <?= getConfig('n_ai')['c_val'] ?></td>
      </tr>
      <tr>
        <td><b>Mat.Fisc.:</b> <?= getConfig('n_mf')['c_val'] ?></td>
      </tr>
      <tr>
        <td><b>NIS:</b> <?= getConfig('nis')['c_val'] ?></td>
      </tr>
    </table>
  </div>
  <div class="column">
    <table>
      <tr>
        <td><b>Mr: <?= $client['nom'].' '.$client['prenom'] ?></b></td>
      </tr>
      <tr>
        <td><b>Adr:</b> <?= $client['adress'] ?></td>
      </tr>
      <tr>
        <td><b>Tél:</b> <?= $client['telephone1'] . $client['telephone2']?></td>
      </tr>
      <tr>
        <td><b>N° Registre:</b> <?= $client['n_rc'] ?></td>
      </tr>
      <tr>
        <td><b>N° Art.Impot.:</b> <?= $client['n_ai'] ?></td>
      </tr>
      <tr>
        <td><b>Mat.Fisc.:</b> <?= $client['n_mf'] ?></td>
      </tr>
      <tr>
        <td><b>NIS:</b></td>
      </tr>
      <tr><td></br></td></tr>
      <tr>
        <td style="font-size: 18px;"><b>Facture N°:</b> <?= '5'.' / '.$yearFacture ?></td>
      </tr>
    </table>
  </div>
</div>

<h2 align="center">&Agrave; Mr:  <b><?= $client['nom'].' '.$client['prenom'] ?></b></h2>


<div class="col-md-12">
  <br>
        <table class="table table-bordered" id="achatListTable" style="table-layout:fixed;border: 2px solid black;">
            <tr style="border: 2px solid black;">
                <th style="border: 2px solid black; width: 5%;">N°</th>
                <th style="border: 2px solid black;  width: 8%;">Clé</th>
                <th style="border: 2px solid black;width: 49%;">Désigniation</th>
                <th style="border: 2px solid black;  width: 8%;">Qte</th>
                <th style="border: 2px solid black; width: 15%;">Prix.U HT</th>
                <th style="border: 2px solid black;  width: 15%;">Total HT</th>
                <!-- <th>TVA</th> -->
            </tr>
          <?php
          $q="SELECT * FROM `vente` WHERE `facture_vente_id`=$id;";
          addToLog($q);
          $r=mysqli_query($dbc,$q);
          $s=0;
          $i=1;
          while($row=mysqli_fetch_assoc($r)){
            $s+=$row['qte_vente']*$row['prix_vente'];
            $designiation = str_replace("eliminer","",getArticleInfo($row['article_id'])['designiation']);
            $des= substr($designiation,0,30);
            $designiation = substr($des,0,strrpos($des,' '));
            ?>
            <tr style="border: 2px solid black;">
                <td style="border: 2px solid black; width: 5%;"><?= $i ?></td>
                <td style="border: 2px solid black;  width: 8%;"><?= $row['article_id'] ?></td>
                <td style="border: 2px solid black;word-wrap: break-word;width:49%;"><?=  $designiation ?></td>
                <td style="border: 2px solid black;  width: 8%; text-align: center;"><?= $row['qte_vente'] ?></td>
                <td style="border: 2px solid black;  width: 15%; text-align: right;"><?= number_format($row['prix_vente'],2) ?></td>
                <td style="border: 2px solid black;  width: 15%; text-align: right;"><?= number_format($row['qte_vente']*$row['prix_vente'],2) ?></td>
                <!-- <td>19%</td> -->
            </tr>
            <?php
            $i++;
          }
            ?>

    </table>
    <!-- <p style="text-align:center;font-size:30px;">Garantie de 3 mois inclue</p>
    <br> -->
    <div style="width:40%;float:left;">
      <table id="tablePrix">
        <tr>
          <th>Crédit Précédent: </th>
          <td><?= number_format(getVersementFacture($id)['val'],2) ?>DA</td>
        </tr>
        <tr>
          <th>Versement: </th>
          <td><?= number_format($client['credit'],2) ?>DA</td>
        </tr>
      </table>
    </div>
      <div style="width:40%;float:right;">
        <table id="tablePrix">
          <tr>
            <th>Total HT:</th>
            <td><?= number_format($s,2) ?>DA</td>
          </tr>
          <tr>
            <th>TVA 0%: </th>
            <td>0 DA</td>
          </tr>
          <tr>
            <th>Total TTC: </th>
            <td><?= number_format($s,2) ?>DA</td>
          </tr>
          <tr>
            <th></th>
            <td></td>
          </tr>

        </table>
    </div>

    <div class="nottoprint">
      <br><br>
        <form action="facture_route.php" >
          <div class="form-group">
            <textarea name="obser" class="form-control" rows="5" id="comment"><?= $facture['obser1'] ?></textarea>
          </div>
          <input type="hidden" name="id" value="<?= $id ?>">
          <input type="submit" value="Enregistrer" class="btn btn-primary">
        </form>
    </div>
    <div class="footer toprint">
      <div style="font-size:18px;">
        Remarque: <?= $facture['obser1'] ?>
        <br><br>
      </div>
      <div align="center">
        <b>Mode Reglement: </b>a Terme
      </div>
      <div>
          <div align="center">
            <b>Arrétée la présente facture à la somme de (En HT):</b>
          </div>
          <?= convert_number_to_words($s) ?> Dinar(s) Algérien(s)
        <br>
      </div>
      <div>
          <div align="center">
           <b>Arrétée la présente facture à la somme de (En TTC):</b>
         </div>
        <?= convert_number_to_words($s) ?> Dinar(s) Algérien(s)
      </div>
    </div>
    <?php
    require_once('includes/footer.html');
    ?>
