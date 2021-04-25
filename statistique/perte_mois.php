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
          ["Jour", "Perte"],';
          for ($i=1; $i < 12; $i++) {
            $nbr=calculerPerteMonthYear($i,$month,2017);
            if($nbr>0){
              echo "['".$i."',  ".calculerPerteMonthYear($i,$month,2017)."],";
            }
          }
          for ($i=1; $i < 12; $i++) {
            $nbr=calculerPerteMonthThisYear($i,$month);
            if($nbr>0){
              echo "['".$i."',  ".calculerPerteMonthThisYear($i,$month)."],";
            }
          }
          echo'
        ]);

        var options = {
          title: "Perte par mois",
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
