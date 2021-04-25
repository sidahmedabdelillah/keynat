<?php
session_start();
if (!isset($_SESSION['prenom'])) {
    $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
require_once('connect_hanout.php');
$page_title="Les Gains a enlever";
include('includes/header.html');
if(isset($_GET['month'])){
  $month=$_GET['month'];
}else{
  $month=date('m');
}
$gain_mois=getGainTotal($month);
?>

    <div class="col-md-12">
        <h1 align="center">Bénéfice à enlever</h1><br>
        <table class="table table-hover">
            <tr>
              <th>A Enlever</th>
              <th>Valider</th>
            </tr>
            <tr>
              <td><input type="number" class="form-control" name="pe" id="pe" value="<?= getGainAEnlever($month) ?>" autofocus></td>
              <td><button type="button" name="button" class="btn btn-primary" onclick="enleverGain();">Enlever</button></td>
            </tr>
        </table>
        <br><br>
        <h1 align="center">Historique des enlevements</h1><br>
        <table class="table table-hover">
            <tr>
              <th>Jour</th>
              <th>Prix Enlever</th>
              <th>50% Gain</th>
            </tr>
            <?php
            $s=0;
            for ($i=1; $i <= 31; $i++) {
              $pejour=getPEJour($i,$month);
              $gain50=getGain50($i,$month);
              if(!is_null($pejour) OR !is_null($gain50)){
                $s+=$pejour;
              ?>
              <tr>
                <td>Jour <?= $i ?></td>
                <td><?= $pejour ?></td>
                <td><?= $gain50 ?></td>
              </tr>
            <?php }}?>
        </table>
    </div>
    <div class="col-md-12">
      <h3 style="text-align:center;">Total deja enlevé: <?= $s ?> DA</h3>
      <br>
    </div>

<?php
  include('includes/footer.html');
?>

<script type="text/javascript">
function enleverGain(){
    var pe = document.getElementById('pe').value;
    $.ajax({
        url: 'caisser.php',
        type: 'get',
        data: 'pa=0&pe='+pe+'&explication=&submitted=TRUE&gain=1',
        success: function(output)
        {
            window.location.href = "les_gains.php";
        }, error: function()
        {
            alert('something went wrong, rating failed');
        }
    });
}
</script>
