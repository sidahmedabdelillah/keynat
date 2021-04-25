<?php
session_start();
if (!isset($_SESSION['prenom'])) {
    $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
require_once('connect_hanout.php');
$page_title='Gain des Vendeurs';
include('includes/header.html');
if(isset($_GET['month'])){
  $month=$_GET['month'];
}else{
  $month=date('m');
}
$gain_mois=getGainTotal($month);
$charge_mois=getChargeMois($month);
$q="SELECT * FROM vendeur where active=1";
$r=mysqli_query($dbc,$q);
while($row=mysqli_fetch_assoc($r)){
  if($row['type']==1){
    $gain_vendeur[$row['v_id']]=calculerGainAdminParMois($month);
  }elseif($row['type']==2){
    $gain_vendeur[$row['v_id']]=calculerGainVendeur($row['v_id'],$month);
  }elseif($row['type']==3){
    $gain_vendeur[$row['v_id']]=calculerGainVendeur2($row['v_id'],$month);
  }
}
$gain_admin=calculerGainAdminParMois($month);
if($month==1){
  $gain_admin+=3000;
}
$gain_wail=$gain_admin;
$gain_amine=calculerGainVendeur(4,$month);
$gain_torkia=calculerGainVendeur(6,$month);
$gain_abdennour=calculerGainVendeur2(7,$month);
$gain_wassim=calculerGainVendeur(10,$month);
$salaire_a_payer=$gain_admin+$gain_amine+$gain_torkia+$gain_abdennour+$gain_wassim+$gain_wail;
$reste_benifice=($gain_mois/2)-$salaire_a_payer;
$charge_interne=0;
$charge_externe=$charge_mois-$charge_interne;
$reste_developpement=($gain_mois/2)-($charge_interne+$charge_externe);
?>

    <div class="col-md-12">
        <h1 align="center">Bénifice des charges</h1>
        <div class="form-actions">
          <a href="gain_vendeur.php?month=8"><button class="btn btn-default">Aout</button></a>
          <a href="gain_vendeur.php?month=9"><button class="btn btn-default">Septembre</button></a>
          <a href="gain_vendeur.php?month=10"><button class="btn btn-default">Octobre</button></a>
          <a href="gain_vendeur.php?month=11"><button class="btn btn-default">Novembre</button></a>
          <a href="gain_vendeur.php?month=12"><button class="btn btn-default">Decembre</button></a>
          <a href="gain_vendeur.php?month=1"><button class="btn btn-default">Janvier</button></a>
          <a href="gain_vendeur.php?month=2"><button class="btn btn-default">Février</button></a>
          <a href="gain_vendeur.php?month=3"><button class="btn btn-default">Mars</button></a>
        </div>
        <br>
        <table class="table table-hover">
            <tr>
              <th></th>
              <th>Gain Brute</th>
              <th>Pertes</th>
              <th>Pourcentage des pertes</th>
              <th>Salaire du mois</th>
            </tr>
            <tr>
              <th>Gain du mois</th>
              <td><?= number_format($gain_mois,0) ?></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <th>Hamel Bessafi</th>
              <td><?= number_format($gain_admin,0) ?></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <th>Wail Skanderi</th>
              <td><?= number_format($gain_wail,0) ?></td>
              <td><?= number_format(getPerteVendeur(2,$month),0) ?></td>
              <td><?= number_format(getPerteVendeur(2,$month)*0.125,0) ?></td>
              <td><?= number_format($gain_wail-(getPerteVendeur(2,$month)*0.125),0) ?></td>
            </tr>
            <tr>
              <th>Torkia Hamraoui</th>
              <td><?= number_format($gain_torkia,0) ?></td>
              <td><?= number_format(getPerteVendeur(6,$month),0) ?></td>
              <td><?= number_format(getPerteVendeur(6,$month)*0.125,0) ?></td>
              <td><?= number_format($gain_torkia-(getPerteVendeur(6,$month)*0.125),0) ?></td>
            </tr>
            <tr>
              <th>Abddennour Mehdi</th>
              <td><?= number_format($gain_abdennour,0) ?></td>
              <td><?= number_format(getPerteVendeur(7,$month),0) ?></td>
              <td><?= number_format(getPerteVendeur(7,$month)*0.125,0) ?></td>
              <td><?= number_format($gain_abdennour-(getPerteVendeur(7,$month)*0.125),0) ?></td>
            </tr>
            <tr>
              <th>Wassim Makhlouf</th>
              <td><?= number_format($gain_wassim,0) ?></td>
              <td>0</td>
              <td>0</td>
              <td><?= number_format($gain_wassim,0) ?></td>
            </tr>
            <tr>
              <th>Reste de bénifice des salaires</th>
              <td><?= number_format($reste_benifice,0) ?></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
        </table><br>
        <h1 align="center">Bénifice pour le developpement</h1><br>
        <table class="table table-hover">
            <tr>
              <th>Charges externes</th>
              <td><?= number_format($charge_mois-0,0) ?></td>
            </tr>
            <tr>
              <th>Charges internes</th>
              <td><?= number_format(0,0) ?></td>
            </tr>
            <tr>
              <th>Reste des benifices des developpemens</th>
              <td><?= number_format($reste_developpement,0) ?></td>
            </tr>
        </table>
    </div>

<?php
  include('includes/footer.html');
?>
