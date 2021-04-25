<?php
session_start();
if(!isset($_SESSION['prenom'])){
  $url=$_SERVER['REQUEST_URI'];
  ?><script type="text/javascript">location.href='login.php?continue=<?= $url ?>';</script><?php
}
require_once('includes/function_inc.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
require_once('connect_hanout.php');

if(isset($_POST['submitted'])){
  $nom=$_POST['nom'];
  $prenom=$_POST['prenom'];
  $adress=$_POST['adress'];
  $telephone1=$_POST['telephone1'];
  $telephone2=$_POST['telephone2'];
  $obser1=$_POST['obser1'];
  $obser2=$_POST['obser2'];
  $type=$_POST['type'];
  $password=$_POST['password'];
  $pourcentage=$_POST['pourcentage'];

  $q="INSERT INTO `vendeur`(`nom`, `prenom`, `adress`, `telephone1`, `telephone2`, `obser1`, `obser2`, `type`, `password`, `pourcentage`) VALUES ('$nom', '$prenom', '$adress', '$telephone1', '$telephone2', '$obser1', '$obser2', '$type', md5('$password'), '$pourcentage')";
  addToLog($q);
  $r=mysqli_query($dbc,$q);
  if ($r){
    echo "<script>alert('Vendeur ajouté');</script>";
  }else{
    echo mysqli_error($dbc);
  }
}
$page_title="Ajouter Vendeur";
include('includes/header.html');
?>

<div class="col-md-offset-5">
  <h2>Ajouter un vendeur</h2><br>
</div>
<form class="form-horizontal" method="post" action="ajouter_vendeur.php">
  <div class="form-group">
    <label class="control-label col-md-2" for="nom">Nom D'utilisateur:</label>
    <div class="col-md-9">
      <input type="text" class="form-control" id="nom" placeholder="Nom D'utilisateur" name="nom" required>
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-2" for="prenom">Nom Complet:</label>
    <div class="col-md-9">
      <input type="text" class="form-control" id="prenom" placeholder="Nom Complet" name="prenom" required>
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-2" for="adress">Adresse:</label>
    <div class="col-md-9">
      <input type="text" class="form-control" id="adress" placeholder="Adresse" name="adress">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-2" for="telephone1">Telephone 1:</label>
    <div class="col-md-9">
      <input type="text" class="form-control" id="telephone1" placeholder="Telephone 1" name="telephone1">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-2" for="telephone2">Telephone 2:</label>
    <div class="col-md-9">
      <input type="text" class="form-control" id="telephone2" placeholder="Telephone 2" name="telephone2">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-2" for="type">Type:</label>
    <div class="col-md-9">
      <select class="form-control" name="type" required>
        <option value="">Choisir le type d'utilisateur</option>
        <option value="1">Administrateur</option>
        <option value="2">Vendeur type 1</option>
        <option value="3">Vendeur type 2</option>
      </select>
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-2" for="password">Mot de passe:</label>
    <div class="col-md-9">
      <input type="password" class="form-control" id="password" placeholder="Mot de passe" name="password" required>
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-2" for="pourcentage">Pourcentage:</label>
    <div class="col-md-9">
      <input type="text" class="form-control" id="pourcentage" placeholder="Pourcentage" name="pourcentage" required>
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-2" for="obser1">Observation 1:</label>
    <div class="col-md-9">
      <input type="text" class="form-control" id="obser1" placeholder="Observation 1" name="obser1">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-2" for="obser2">Observation 2:</label>
    <div class="col-md-9">
      <input type="text" class="form-control" id="obser2" placeholder="Observation 2" name="obser2">
    </div>
  </div>

  <div class="form-group">
    <div class="col-sm-offset-2 col-md-9">
      <input type="hidden" name="submitted" value="TRUE">
      <button type="submit" class="btn btn-default">Ajouter Vendeur</button>
    </div>
  </div>
</form>


<?php
include('includes/footer.html');
?>
