<?php
session_start();
if(!isset($_SESSION['prenom'])){
    $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
require_once('connect_hanout.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
$page_title="Changer Place";
require_once('includes/header.html');

if(isset($_GET['premier']) && isset($_GET['deux'])){
$tmp=generateRandomNumber(5);
$premier=$_GET['premier'];
$deux=$_GET['deux'];
$vendeur=$_SESSION['v_id'];


// table achat
$q="UPDATE achat set article_id = '$tmp' where article_id='$premier';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}

$q="UPDATE achat set article_id = '$premier' where article_id='$deux';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}

$q="UPDATE achat set article_id = '$tmp' where article_id='$tmp';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}

// table article
$q="UPDATE article set cle = '$tmp' where cle='$premier';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}

$q="UPDATE article set cle = '$premier' where cle='$deux';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}

$q="UPDATE article set cle = '$deux' where cle='$tmp';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}


// table charge

$q="UPDATE charge set a_id = '$tmp' where a_id='$premier';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}

$q="UPDATE charge set a_id = '$premier' where a_id='$deux';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}

$q="UPDATE charge set a_id = '$deux' where a_id='$tmp';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}


// table inventaire

$q="UPDATE inventaire set article_id = '$tmp' where article_id='$premier';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}

$q="UPDATE inventaire set article_id = '$premier' where article_id='$deux';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}

$q="UPDATE inventaire set article_id = '$deux' where article_id='$tmp';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}


// table panier

$q="UPDATE panier set a_id = '$tmp' where a_id='$premier';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}

$q="UPDATE panier set a_id = '$premier' where a_id='$deux';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}

$q="UPDATE panier set a_id = '$deux' where a_id='$tmp';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}


// table perte

$q="UPDATE perte set article_id = '$tmp' where article_id='$premier';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}

$q="UPDATE perte set article_id = '$premier' where article_id='$deux';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}

$q="UPDATE perte set article_id = '$deux' where article_id='$tmp';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}


// table achat_temp

$q="UPDATE achat_temp set cle = '$tmp' where cle='$premier';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}

$q="UPDATE achat_temp set cle = '$premier' where cle='$deux';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}

$q="UPDATE achat_temp set cle = '$deux' where cle='$tmp';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}


// table rendu

$q="UPDATE rendu set article_id = '$tmp' where article_id='$premier';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}

$q="UPDATE rendu set article_id = '$premier' where article_id='$deux';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}

$q="UPDATE rendu set article_id = '$deux' where article_id='$tmp';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}


// table vente

$q="UPDATE vente set article_id = '$tmp' where article_id='$premier';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}

$q="UPDATE vente set article_id = '$premier' where article_id='$deux';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}

$q="UPDATE vente set article_id = '$deux' where article_id='$tmp';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}


// table vente_temp

$q="UPDATE vente_temp set cle = '$tmp' where cle='$premier';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}

$q="UPDATE vente_temp set cle = '$premier' where cle='$deux';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}

$q="UPDATE vente_temp set cle = '$deux' where cle='$tmp';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}

// table gain

$q="UPDATE gain set article_id = '$tmp' where article_id='$premier';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}

$q="UPDATE gain set article_id = '$premier' where article_id='$deux';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}

$q="UPDATE gain set article_id = '$deux' where article_id='$tmp';";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}

$q="INSERT INTO `changer_place` (`v_id`, `article1`, `article2`) VALUES ('$vendeur', '$premier', '$deux')";
$r=mysqli_query($dbc,$q);
if($r){
  //
}else{
  echo mysqli_error($dbc);
  exit();
}


echo '<h1>Changé avec succés</h1>';
include('includes/footer.html');
}else{
  ?>
  <h1 align="center"><?= NOM ?> Changer de place</h1><br>
  <form class="" action="changer_place.php">
  <div class="col-md-6 text-center">
        Article 1:<input type="number" id="premier" name="premier" class="form-control" autofocus required onchange="getDesigniation('premier','spanv1');">
  </div>


      <div class="col-md-6">
        Article 2:<input type="number" id="deux" name="deux" class="form-control" required onchange="getDesigniation('deux','spanv2');">
        <br>
      </div>
      <div class="col-md-6" id='spanv1'>

      </div>
      <div class="col-md-6" id='spanv2'>

      </div><br><br><br>
  <div class="form-actions col-md-12">
    <br>
    <input type="submit" class="btn btn-primary" value="Modifier">
  </div>
  </div>
</form>



  <?php
  require_once('includes/footer.html');
}
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
