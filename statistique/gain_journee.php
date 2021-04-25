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
          ["Jour", "NBR"],';
          for ($i=1; $i < 31; $i++) {
            $nbr=getGainOFDay($i,$month);
            if($nbr>0){
              echo "['".$i."',  ".getGainOFDay($i,$month)."],";
            }
          }
          echo'
        ]);

        var options = {
          title: "Gain par jour",
          curveType: "function",
          legend: { position: "bottom" }
        };

        var chart = new google.visualization.LineChart(document.getElementById("curve_chart"));

        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="curve_chart" style="width: 100%; height: 500px"></div>
  </body>
</html>';
