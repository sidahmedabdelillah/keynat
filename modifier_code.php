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

if(isset($_GET['code'])){
  $article=$_GET['article'];
  $code=$_GET['code'];
  $q="UPDATE `article` SET `code` = '$code' WHERE `article`.`cle` = $article;";
  addToLog($q);
  $r=mysqli_query($dbc,$q);
  if($r){
    ?>
    <script type="text/javascript">
      alert('Code modifié');
    </script>
    <?php
  }else{
    echo mysqli_error($dbc);
    exit();
  }

}
?>
    <h1 align="center"><?= NOM ?> Modifier Code</h1><br>
    <form class="" action="modifier_code.php">
    <div class="col-md-6 text-center">
          Choisir un article:<input type="number" id="article" name="article" class="form-control" onchange="getCodebar();" autofocus required>
    </div>
    <span id="spanpv">
        <div class="col-md-6">
          Code:<input type="number" id="code" name="code" class="form-control" required onchange="getCodeifUnique();" onkeydown="getCodeifUnique();"><br>
        </div>
    </span>
    <span id="responseSpan">
      <h2 style="color:red;text-align:center;" id="errorH2"></h2>
      <div class="form-actions">
        <input type="submit" class="btn btn-primary" id="submitBtn">
      </div>
    </span>
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
            xmlhttp.open("GET","articlecode.php?id="+article,true);
            xmlhttp.send();
        }
    </script>
    <script type="text/javascript">

        function getCodeifUnique() {
            var code = document.getElementById('code').value;
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp=new XMLHttpRequest();
            } else { // code for IE6, IE5
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange=function() {
                if (this.readyState==4 && this.status==200) {
                    document.getElementById("responseSpan").innerHTML=this.responseText;
                }
            }
            xmlhttp.open("GET","checkIfCodeUnique.php?id="+code,true);
            xmlhttp.send();
        }
    </script>
    <?php
    require_once('includes/footer.html');
    ?>
