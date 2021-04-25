<?php
session_start();
if(!isset($_SESSION['prenom'])){
    $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
require_once('connect_hanout.php');
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
        <td><b>Mr: OPGI Mascara</b></td>
      </tr>
      <tr>
        <td><b>Adr:</b> Rue Sidi Kada Belmokhtar Zone 08 Mascara</td>
      </tr>
      <tr>
        <td><b>Tél:</b> 0457513171</td>
      </tr>
      <tr>
        <td><b>N° Registre:</b> </td>
      </tr>
      <tr>
        <td><b>N° Art.Impot.:</b> </td>
      </tr>
      <tr>
        <td><b>Mat.Fisc.:</b> </td>
      </tr>
      <tr>
        <td><b>NIS:</b></td>
      </tr>
      <tr><td></br></td></tr>
      <tr>
        <td style="font-size: 18px;"><b>Facture N°:</b> 5 / 2018</td>
      </tr>
    </table>
  </div>
</div>

<h2 align="center">&Agrave; Mr:  <b>OPGI Mascara</b></h2>


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
            </tr>
            <tr style="border: 2px solid black;">
                <td style="border: 2px solid black; width: 5%;">1</td>
                <td style="border: 2px solid black;  width: 8%;">4428</td>
                <td style="border: 2px solid black;word-wrap: break-word;width:49%;">carte graphique msi Geforce 261 2gb</td>
                <td style="border: 2px solid black;  width: 8%; text-align: center;">1</td>
                <td style="border: 2px solid black;  width: 15%; text-align: right;">4400.00</td>
                <td style="border: 2px solid black;  width: 15%; text-align: right;">4400.00</td>
            </tr>
            <tr style="border: 2px solid black;">
                <td style="border: 2px solid black; width: 5%;">2</td>
                <td style="border: 2px solid black;  width: 8%;">798</td>
                <td style="border: 2px solid black;word-wrap: break-word;width:49%;">ram ddr2 pc 2go first tech</td>
                <td style="border: 2px solid black;  width: 8%; text-align: center;">2</td>
                <td style="border: 2px solid black;  width: 15%; text-align: right;">2400.00</td>
                <td style="border: 2px solid black;  width: 15%; text-align: right;">4800.00</td>
            </tr>
            <tr style="border: 2px solid black;">
                <td style="border: 2px solid black; width: 5%;">3</td>
                <td style="border: 2px solid black;  width: 8%;">2428</td>
                <td style="border: 2px solid black;word-wrap: break-word;width:49%;">graveur DVD pc interne</td>
                <td style="border: 2px solid black;  width: 8%; text-align: center;">1</td>
                <td style="border: 2px solid black;  width: 15%; text-align: right;">2400.00</td>
                <td style="border: 2px solid black;  width: 15%; text-align: right;">2400.00</td>
            </tr>
            <tr style="border: 2px solid black;">
                <td style="border: 2px solid black; width: 5%;">4</td>
                <td style="border: 2px solid black;  width: 8%;">1855</td>
                <td style="border: 2px solid black;word-wrap: break-word;width:49%;">boite alimentation 200W P10</td>
                <td style="border: 2px solid black;  width: 8%; text-align: center;">2</td>
                <td style="border: 2px solid black;  width: 15%; text-align: right;">1100.00</td>
                <td style="border: 2px solid black;  width: 15%; text-align: right;">2200.00</td>
            </tr>
    </table>
    <!-- <p style="text-align:center;font-size:30px;">Garantie de 3 mois inclue</p>
    <br> -->
    <div style="width:40%;float:left;">
      <table id="tablePrix">
        <tr>
          <th>Crédit Précédent: </th>
          <td>13,800.00 DA</td>
        </tr>
        <tr>
          <th>Versement: </th>
          <td>0.00 DA</td>
        </tr>
      </table>
    </div>
      <div style="width:40%;float:right;">
        <table id="tablePrix">
          <tr>
            <th>Total HT:</th>
            <td>13,800.00 DA</td>
          </tr>
          <tr>
            <th>TVA 0%: </th>
            <td>0 DA</td>
          </tr>
          <tr>
            <th>Total TTC: </th>
            <td>13,800.00 DA</td>
          </tr>
          <tr>
            <th></th>
            <td></td>
          </tr>

        </table>
    </div>


    <div class="footer toprint">
      <div align="center">
        <b>Mode Reglement: </b>a Terme
      </div>
      <div>
          <div align="center">
            <b>Arrétée la présente facture à la somme de (En HT):</b>
          </div>
          treize mille, huit cent Dinar(s) Algérien(s)
        <br>
      </div>
      <div>
          <div align="center">
           <b>Arrétée la présente facture à la somme de (En TTC):</b>
         </div>
        treize mille, huit cent Dinar(s) Algérien(s)
      </div>
    </div>
    <?php
    require_once('includes/footer.html');
    ?>
