<?php
require_once('../includes/function_inc.php');
if(isset($_GET['month'])){
  $month=$_GET['month'];
}else{
  $month=date('m');
}

echo'
<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {"packages":["corechart"]});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ["Mois", "Gain Torkia", "Administrateur"],';
          for ($i=9; $i <= 12; $i++) {
            $gain=calculerGainVendeur(6,$i);
            $gain_admin=calculerGainAdminParMois($i);
            if($gain>0){
              echo "['".$i." - 17',  ".$gain.",  ".$gain_admin."],";
            }
          }
          for ($i=1; $i < 9; $i++) {
            $gain=calculerGainVendeur(6,$i);
            $gain_admin=calculerGainAdminParMois($i);
            if($i==1){
              $gain_admin+=3000;
            }
            if($gain>0){
              echo "['".$i." - 18',  ".$gain.",  ".$gain_admin."],";
            }
          }
          echo'
        ]);

        var options = {
          title: "Gain des vendeurs",
          curveType: "function",
          legend: { position: "bottom" }
        };

        var chart = new google.visualization.LineChart(document.getElementById("curve_chart"));

        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="curve_chart" style="width: 100%; height: 100%"></div>
  </body>
</html>';
