<?php
require_once('../connect_hanout.php');
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
          ["Temps", "Somme Quantité sup 0"],';
          $i=1;
          $q="SELECT * FROM `nbr_article` ORDER BY `nbr_article`.`datetime` asc;";
          $r=mysqli_query($dbc,$q);
          while($row=mysqli_fetch_assoc($r)){
              echo "['". $row['datetime']."',  ".$row['nbr_sum_quantite']."],";
          }
          echo'
        ]);

        var options = {
          title: "Somme Quantité sup 0",
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
