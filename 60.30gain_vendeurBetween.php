<?php
session_start();
$startdate=null;
$finishdate=null;
if (!isset($_SESSION['prenom'])) {
    $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
require_once('connect_hanout.php');
$page_title='Gain des Vendeurs';
include('includes/header.html');

// 1:convertis les input dans des variable

if(isset($_GET['startdate']) AND isset($_GET['finishtdate'])){
  $startdate=$_GET['startdate'];
  $finishdate=$_GET['finishtdate'];
  addToLog($q);
  echo'<h1 align="center">Recap des benifices et des charges entre '.$startdate.' et '.$finishdate.'</h1><br>';
}else {
  $todaydate= date('Y/m/d',mktime(0, 0, 0, date("m")  , date("d"), date("Y")));
  addToLog($q);
  echo'<h1 align="center">Recap des benifices <?= $todaydate ?></h1><br>';
}
 // Ramene Gains Total entre deux date
 $gain_mois=getGainTotalBetween($startdate,$finishdate);
// $gain_mois=getGainTotal($month);
$charge_mois=getChargeBetween($startdate,$finishdate);
$q="SELECT * FROM vendeur";
$r=mysqli_query($dbc,$q);
$gain_vendeurs=0;
while($row=mysqli_fetch_assoc($r)){
  $v_id=$row['v_id'];
  $q3="SELECT * from salaire where  date(date) BETWEEN '$startdate' AND '$finishdate'  and v_id=$v_id limit 1";
  $r3=mysqli_query($dbc,$q3);
  if(mysqli_num_rows($r3)>0){
    $row3=mysqli_fetch_assoc($r3);
    $gain_vendeur[$v_id]=floatval($row3['val']);
    $gain_vendeurs+=floatval($gain_vendeur[$v_id]);
  }else{
    if($row['type']==1){
      $gain_vendeur[$row['v_id']]=calculerGainAdminBetween($startdate,$finishdate);
    }elseif($row['type']==2){
      $gain_vendeur[$row['v_id']]=calculerGainVendeurBetween($row['v_id'],$startdate,$finishdate);
    }elseif($row['type']==3){
      $gain_vendeur[$row['v_id']]=calculerGainVendeur2Between($row['v_id'],$month);
    }
    $gain_vendeurs+=$gain_vendeur[$row['v_id']];
  }
}
$gain_admin=calculerGainAdminBetween($startdate,$finishdate);
if($month==1){
  $gain_admin+=3000;
}
$reste_benifice=($gain_mois/2)-$gain_vendeurs;
$charge_interne=0;
$charge_externe=$charge_mois-$charge_interne;
$reste_developpement=($gain_mois/2)-($charge_interne+$charge_externe);
?>

    <div class="col-md-12">
        
        </div>
        
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
                <td><?= number_format(getPerteVendeurBetween($row2['v_id'],$startdate,$finishdate),0) ?></td>
                <td><?= number_format(getPerteVendeurBetween($row2['v_id'],$startdate,$finishdate)*0.125,0) ?></td>
                <td><?= number_format($gain_vendeur[$row2['v_id']]-(getPerteVendeurBetween($row2['v_id'],$startdate,$finishdate)*0.125),0) ?></td>
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


<!--4 Parte des input des donnees  -->
<div class="col-md-12">
  <br>
    <form action="60.30gain_vendeurBetween.php" method="get">
       <div class="col-md-5 form-group">
           <input type="date" name="startdate" value="<?= date("Y-m-j") ?>" class="form-control">
        </div>
       <div class="col-md-5 form-group">
          <input type="date" name="finishtdate" value="<?= date("Y-m-j") ?>" class="form-control">
       </div>
       <div class="col-md-2 form-group">
             <button type="submit" class="btn btn-primary btn-block">Filter</button>
       </div>
    </form>
</div>
<?php
  include('includes/footer.html');
?>
<!-- calculerGainAdminBetween($startdate,$finishdate)
     calculerGainVendeurBetween($row['v_id'],$startdate,$finishdate)
     calculerGainVendeur2Between
     getGainTotalBetween($startdate,$finishdate)
     getChargeBetween($startdate,$finishdate)
     getPerteVendeurBetween
 -->