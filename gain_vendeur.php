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
$q="SELECT * FROM vendeur";
$r=mysqli_query($dbc,$q);
$gain_vendeurs=0;
while($row=mysqli_fetch_assoc($r)){
  $v_id=$row['v_id'];
  $q3="SELECT * from salaire where month(date)=$month and v_id=$v_id limit 1";
  $r3=mysqli_query($dbc,$q3);
  if(mysqli_num_rows($r3)>0){
    $row3=mysqli_fetch_assoc($r3);
    $gain_vendeur[$v_id]=floatval($row3['val']);
    $gain_vendeurs+=floatval($gain_vendeur[$v_id]);
  }else{
    if($row['type']==1){
      $gain_vendeur[$row['v_id']]=calculerGainAdminParMois($month);
    }elseif($row['type']==2){
      $gain_vendeur[$row['v_id']]=calculerGainVendeur($row['v_id'],$month);
    }elseif($row['type']==3){
      $gain_vendeur[$row['v_id']]=calculerGainVendeur2($row['v_id'],$month);
    }
    $gain_vendeurs+=$gain_vendeur[$row['v_id']];
  }
}
$gain_admin=calculerGainAdminParMois($month);
if($month==1){
  $gain_admin+=3000;
}
$reste_benifice=($gain_mois/2)-$gain_vendeurs;
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
          <a href="gain_vendeur.php?month=4"><button class="btn btn-default">Avril</button></a>
          <a href="gain_vendeur.php?month=5"><button class="btn btn-default">Mai</button></a>
          <a href="gain_vendeur.php?month=6"><button class="btn btn-default">Juin</button></a>
      
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
            <?php
            $q2="SELECT * FROM vendeur";
            $r2=mysqli_query($dbc,$q2);
            while($row2=mysqli_fetch_assoc($r2)){
              if($gain_vendeur[$row2['v_id']]>0){
              ?>
              <tr>
                <th><?= $row2['prenom'] ?></th>
                <td><?= number_format($gain_vendeur[$row2['v_id']],0) ?></td>
                <td><?= number_format(getPerteVendeur($row2['v_id'],$month),0) ?></td>
                <td><?= number_format(getPerteVendeur($row2['v_id'],$month)*0.125,0) ?></td>
                <td><?= number_format($gain_vendeur[$row2['v_id']]-(getPerteVendeur($row2['v_id'],$month)*0.125),0) ?></td>
              </tr>

          <?php } }?>

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
