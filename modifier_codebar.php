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
if(isset($_GET['eviter'])){
  ?>
  <script type="text/javascript">
    alert('codebar à éviter');
  </script>
  <?php
}
if(isset($_GET['code'])){
  $article=$_GET['article'];
  $code=$_GET['code'];
  if(siCodebarEviter($code)>0){ 
    ?>
    <script type="text/javascript">
      window.location='modifier_codebar.php?eviter';
    </script>
    <?php
  }
  $q="UPDATE `article` SET `codebar` = '$code' WHERE `article`.`cle` = $article;";
  addToLog($q);
  $r=mysqli_query($dbc,$q);
  if($r){
    ?>
    <script type="text/javascript">
      alert('Codebar modifié');
    </script>
    <?php
  }else{
    echo mysqli_error($dbc);
    exit();
  }

}
?>


    <h1 align="center"><?= NOM ?> Modifier CodeBar</h1><br>
    <form class="" action="modifier_codebar.php">
    <div class="col-md-6 text-center">
          Choisir un article:<input type="number" id="article" name="article" class="form-control" onchange="getCodebar();" autofocus required>
    </div>
    <span id="spanpv">
        <div class="col-md-6">
          Codebar:<input type="text" id="code" name="code" class="form-control" required><br>
        </div>
    </span>
    <div class="form-actions">
      <input type="submit" class="btn btn-primary">
    </div>
    </div>
</form>



    <script type="text/javascript">

        function getCodebar() {
            var article = document.getElementById('article').value;
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp=new XMLHttpRequest();
            } else { // code for IE6, IE5
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange=function() {
                if (this.readyState==4 && this.status==200) {
                    document.getElementById("spanpv").innerHTML=this.responseText;
                }
            }
            xmlhttp.open("GET","articlecodebar.php?id="+article,true);
            xmlhttp.send();
        }
    </script>
    <?php
    require_once('includes/footer.html');
    ?>
