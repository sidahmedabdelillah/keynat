<?php
session_start();
if(!isset($_SESSION['prenom'])){
    $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
require_once('connect_hanout.php');
require_once('includes/header.html');

if(isset($_GET['nbr'])){
  $article=$_GET['article'];
  $nbr=$_GET['nbr'];
  @session_start();
  $vendeur=@$_SESSION['v_id'];
  if($article>$config['middle']){
    $responsable=5;
  }else{
    $responsable=7;
  }
  $q1="SELECT * from perte_temp WHERE `article_id`='$article' limit 1;";
  addToLog($q1);
  $r1=mysqli_query($dbc,$q1);
  if(mysqli_num_rows($r1)==1){
    $q="UPDATE `perte_temp` SET `qte_perdu`='$nbr',`date`=CURRENT_TIMESTAMP,`vendeur_id`='$vendeur', `reponsable`='$responsable' WHERE `article_id`='$article';";
  }else {
    $q="INSERT INTO `perte_temp` (`article_id`, `qte_perdu`, `vendeur_id`, `reponsable`) VALUES ('$article', '$nbr', '$vendeur', '$responsable');";
  }
  addToLog($q);
  $r=mysqli_query($dbc,$q);
  if($r){
    ?>
    <script type="text/javascript">
      alert('Perte Declaré');
    </script>
    <?php
  }else{
    echo mysqli_error($dbc);
    exit();
  }

}
?>


    <h1 align="center"><?= NOM ?> Declarer Perte</h1><br>
    <form class="" action="declarer_perte.php">
    <div class="col-md-6 text-center">
          Choisir un article:<input type="number" id="article" name="article" class="form-control" autofocus required onchange="getDesigniation('article','spanv1')";>
    </div>

    <div class="col-md-6">
      Nombre:<input type="text" id="nbr" name="nbr" class="form-control" required><br>
    </div>
        <div class="col-md-6" id='spanv1'>

        </div>
    <div class="form-actions">
      <input type="submit" class="btn btn-primary" value="Declarer">
    </div>
    </div>
</form>


    <?php
    require_once('includes/footer.html');
    ?>
<script type="text/javascript">

    function getDesigniation(article,span) {
        var article1 = document.getElementById(article).value;
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        } else { // code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function() {
            if (this.readyState==4 && this.status==200) {
                document.getElementById(span).innerHTML=this.responseText;
            }
        }
        xmlhttp.open("GET","article_designiation.php?id="+article1,true);
        xmlhttp.send();
    }
</script>
