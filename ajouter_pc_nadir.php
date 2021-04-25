<?php
session_start();
if(!isset($_SESSION['prenom'])){
    $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
$page_title='Ajouter un PC';
require_once('includes/function_inc.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
require_once('connect_hanout.php');

if(isset($_GET['submitted'])){
  $marque=$_GET['marque'];
  $ram=$_GET['ram'];
  $processeur=$_GET['processeur'];
  $carte_graphique=$_GET['carte_graphique'];
  $dd= $_GET['dd'];
  $ecran=$_GET['ecran'];
  $batterie=$_GET['batterie'];
  $webcam=$_GET['webcam'];
  $graveur=$_GET['graveur'];
  $sd= $_GET['sd'];
  $sim= $_GET['sim'];
  $empreinte= $_GET['empreinte'];
  $wifi= $_GET['wifi'];
  $usb= $_GET['usb'];
  $hdmi= $_GET['hdmi'];
  $vga= $_GET['vga'];
  $poids= $_GET['poids'];
  $couleur= $_GET['couleur'];
  $etat= $_GET['etat'];
  $prix=$_GET['prix'];
  $chargeur=$_GET['chargeur'];

    $q="INSERT INTO `pc_nadir`(`marque`, `prix`, `processeur`, `carte_graphique`, `ram`, `dd`, `ecran`, `batterie`, `sd`, `graveur`, `sim`, `empreinte`, `poids`, `etat`, `webcam`, `wifi`, `hdmi`, `vga`, `usb`, `couleur`, `chargeur`) VALUES ('$marque', '$prix', '$processeur', '$carte_graphique', '$ram', '$dd', '$ecran', '$batterie', '$sd', '$graveur', '$sim', '$empreinte', '$poids', '$etat', '$webcam', '$wifi', '$hdmi', '$vga', '$usb', '$couleur', '$chargeur')";
    addToLog($q);
  //}
  $r=mysqli_query($dbc,$q);
  if ($r){?>
    <script>
      alert('PC Ajouté sous la clé <?=mysqli_insert_id($dbc)?>');
    </script>

    <?php
  }else{
    echo mysqli_error($dbc);
  }
}
$page_title="Ajouter PC Nadir";
include('includes/header.html');
?>

<div class="col-md-offset-5">
  <h2>Ajouter un PC</h2><br>
</div>
<form class="form-horizontal" method="get" action="ajouter_pc_nadir.php">


  <div class="form-group">
    <label class="control-label col-md-2" for="nom">Url:</label>
    <div class="col-md-9">
      <input class="form-control" id="url" placeholder="Url" name="url" onchange="getInfoPC();">
    </div>
  </div>

<span id="spanv">

  <div class="form-group">
    <label class="control-label col-md-2" for="nom">Marque:</label>
    <div class="col-md-9">
      <input class="form-control" id="marque" placeholder="Marque" name="marque" required>
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-2" for="telephone">Processeur:</label>
    <div class="col-md-9">
      <input class="form-control" id="processeur" placeholder="Processeur" name="processeur" required>
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-2" for="telephone">Prix:</label>
    <div class="col-md-9">
      <input class="form-control" id="prix" placeholder="Prix" name="prix" required>
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-2" for="marque">Carte Graphique:</label>
    <div class="col-md-9">
      <input type="text" class="form-control" id="carte_graphique" placeholder="Carte Graphique" name="carte_graphique" required>
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-2" for="problem">Ram:</label>
    <div class="col-md-9">
      <input class="form-control" id="ram" placeholder="Ram" name="ram" required>
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-2" for="remarque">Disque Dur:</label>
    <div class="col-md-9">
      <input class="form-control" id="dd" placeholder="Disque Dur" name="dd">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-2" for="remarque">Ecran:</label>
    <div class="col-md-9">
      <input class="form-control" id="ecran" placeholder="Ecran" name="ecran">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-2" for="remarque">Batterie:</label>
    <div class="col-md-9">
      <input class="form-control" id="batterie" placeholder="Batterie" name="batterie">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-2" for="graveur">Lecteur CD/DVD graveur:</label>
    <div class="col-md-9">
      <input class="form-control" id="graveur" placeholder="Graveur" name="graveur">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-2" for="graveur">Lecteur carte mémoire SD:</label>
    <div class="col-md-9">
      <input class="form-control" id="sd" placeholder="Lecteur carte mémoire SD" name="sd">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-2" for="graveur">Lecteur carte SIM:</label>
    <div class="col-md-9">
      <input class="form-control" id="sim" placeholder="Lecteur carte SIM" name="sim">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-2" for="graveur">Lecteur d’empreinte:</label>
    <div class="col-md-9">
      <input class="form-control" id="empreinte" placeholder="Lecteur d’empreinte" name="empreinte">
    </div>
  </div>


  <div class="form-group">
    <label class="control-label col-md-2" for="poids">Poids:</label>
    <div class="col-md-9">
      <input class="form-control" id="poids" placeholder="Poids" name="poids">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-2" for="remarque">Etat:</label>
    <div class="col-md-9">
      <input class="form-control" id="etat" placeholder="Etat" name="etat">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-2" for="remarque">Webcam:</label>
    <div class="col-md-9">
      <input class="form-control" id="webcam" placeholder="Webcam" name="webcam">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-2" for="remarque">Wi Fi / Bluetooth:</label>
    <div class="col-md-9">
      <input class="form-control" id="wifi" placeholder="Wi Fi / Bluetooth" name="wifi">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-2" for="remarque">Port HDMI:</label>
    <div class="col-md-9">
      <input class="form-control" id="hdmi" placeholder="Port HDMI" name="hdmi">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-2" for="remarque">Port VGA:</label>
    <div class="col-md-9">
      <input class="form-control" id="vga" placeholder="Port VGA" name="vga">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-2" for="remarque">Port USB:</label>
    <div class="col-md-9">
      <input class="form-control" id="usb" placeholder="Port USB" name="usb">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-2" for="remarque">Couleur:</label>
    <div class="col-md-9">
      <input class="form-control" id="couleur" placeholder="Couleur" name="couleur">
    </div>
  </div>

  </span>

  <div class="form-group">
    <label class="control-label col-md-2" for="remarque">Chargeur de nadir:</label>
    <div class="col-md-9">
      <label class="radio-inline"><input type="radio" name="chargeur" value="1">Oui</label>
      <label class="radio-inline"><input type="radio" name="chargeur" value="0">Non</label>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-md-9">
      <input type="hidden" name="submitted" value="TRUE">
      <button type="submit" class="btn btn-primary">Ajouter PC</button>
    </div>
  </div>
</form>


<?php
include('includes/footer.html');
?>
<script type="text/javascript">
function getInfoPC() {
    var url = document.getElementById('url').value;
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    } else { // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
            document.getElementById("spanv").innerHTML=this.responseText;
        }
    }
    xmlhttp.open("GET","getpcnadirinfo.php?url="+url,true);
    xmlhttp.send();
}
</script>
