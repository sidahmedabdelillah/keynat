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
          ["Jour", "NBR"],';
          $q="select date, val+val_stock+val2+val_stock2 from caisse where month(date)=month(CURRENT_DATE) and day(date)=day(CURRENT_DATE) ORDER by `date`";
          $r=mysqli_query($dbc,$q);
          while($row=mysqli_fetch_assoc($r)){
            $val=$row['val+val_stock+val2+val_stock2'];
            echo "['".$row['date']."',  ".$val."],";
          }
          echo'
        ]);

        var options = {
          title: "Capital par jour",
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
