<?php
session_start();
include('includes/footer.html');
require_once('connect_hanout.php');
require_once('includes/function_inc.php');
if(isset($_GET['article']) AND isset($_GET['rendre'])){
  $article=$_GET['article'];
  $maintenance=getMaintenanceInfo($article);
  $_SESSION['client']=$maintenance['client'];
  $q="UPDATE `maintenance` SET `rendu` = CURRENT_TIMESTAMP WHERE `maintenance`.`m_id` =$article;";
  addToLog($q);
  $r=mysqli_query($dbc,$q);
  if($r){
    $qm="select * from article_maintenance where m_id=$article;";
    $rm=mysqli_query($dbc,$qm);
    if(mysqli_num_rows($rm)>0){
      while($rowm=mysqli_fetch_assoc($rm)){
        ?>
        <script type="text/javascript">
          ajouterVenteMaintenace(<?= $rowm['article_id'] ?>,<?= $rowm['qte'] ?>,<?= $rowm['pv'] ?>);
        </script>
        <?php
      }
    }
    ?>
    <script type="text/javascript">
        window.location.href = "list_maintenance.php?code=<?= $article ?>";
    </script>
    <?php
  }else{
    echo mysqli_error($dbc);
  }
}elseif(isset($_GET['article']) AND isset($_GET['nonrendre'])){
  $article=$_GET['article'];
  $q="UPDATE `maintenance` SET `rendu` = NULL WHERE `maintenance`.`m_id` =$article;";
  addToLog($q);
  $r=mysqli_query($dbc,$q);
  if($r){

  }else{
    echo mysqli_error($dbc);
  }
}else{?>
  <script type="text/javascript">
    href.location='index.php?nonRendu';
  </script>
  <?php
}
?>
