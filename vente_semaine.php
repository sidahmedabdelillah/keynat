<?php
session_start();
if (!isset($_SESSION['prenom'])) {
    $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
require_once('connect_hanout.php');
include('includes/header_stat.html');
?>

<div class="col-md-12">
    <div id="vente_chart" style="width: 100%; height: 100%"></div>
    <table class="table table-hover">
      <tr>
        <th>Jour</th>
        <th>Vente</th>
      </tr>
      <?php
        for ($i=29; $i >= 0; $i--) {
          ?>
          <tr>
            <td><?= date("l d/m",strtotime("-".$i." days")) ?></td>
            <td id="vente<?= $i ?>"><?= calculerNbrVente($i) ?></td>
          </tr>
          <?php
        }
       ?>
      <tr>
        <td><b>Total Vente</b></td>
        <b><td id="totalVente"></td></b>
      </tr>
    </table>
</div>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['Date', 'Nbr de Vente'],
      <?php for ($i=29; $i> 0; $i--) {
        ?>
          ['<?= date("d/m",strtotime("-".$i." days")) ?>',  <?= calculerNbrVente($i) ?>],
        <?php
      }?>
      ['<?= date("d/m") ?>',  <?= calculerNbrVente(0) ?>]
    ]);

    var options = {
      title: 'Le Nombre de vente de la semaine',
      colors: ['#109028','#e6693e', '#ec8f6e', '#f3b49f', '#f6c7b6'],
      legend: { position: 'bottom' }
    };

    var chart = new google.visualization.LineChart(document.getElementById('vente_chart'));

    chart.draw(data, options);
  }
</script>
<script
  src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
  integrity="sha256-k2WSCIexGzOj3Euiig+TlR8gA0EmPjuc79OEeY5L45g="
  crossorigin="anonymous"></script>
<script type="text/javascript">
var sum1 =0;
for (var i = 0; i <30; i++) {
  sum1 += parseInt(document.getElementById('vente'+i).innerHTML);
}
document.getElementById('totalVente').innerHTML='<b>'+sum1+'</b>';


</script>
<?php
include ('includes/footer.html');?>