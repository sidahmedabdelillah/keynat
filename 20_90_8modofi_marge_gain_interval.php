<?php
session_start();
if(!isset($_SESSION['prenom'])){
  $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
$page_title="Modifier";
require_once('includes/header.html');
require_once('connect_hanout.php');
?>
<div class="col-md-12">
<h1 align="center">Modification + désigniation vide</h1><br>

<br><br>
<div class="col-md-12">
  <form>
    <div class="input-group">
                <div class="col-md-2">
                    <input type="number" class="form-control" placeholder="Début" id="debut_mod_cat" name="debut" value="<?= @$_GET['debut'] ?>">
                </div>
                <div class="col-md-2">
                     <input type="number" class="form-control" placeholder="Fin" id="fin_mod_cat" name="fin" value="<?= @$_GET['fin'] ?>">
                 </div>
                 <div class="col-md-2">
                     <input type="text" class="form-control" placeholder="marge gains " id="marge_Gain20">
                 </div>
                 <div class="col-md-2">
                     <input type="text" class="form-control" placeholder="marge_prix_vente2 " id="marge_prix_vente2">
                 </div>
                 <div class="col-md-2">
                     <input type="text" class="form-control" placeholder="marge_prix_vente3 " id="marge_prix_vente3">
                 </div>
                 <div class="input-group-btn">
                <div  name="modifierCat" id="modcat" class="btn btn-primary"> modifier cat</i>
                </div>
              <style> #modcat{margin-bottom:15px;} </style>
             <div class="input-group-btn">
                      <button  class="btn btn-default" type="submit">
                        <i class="glyphicon glyphicon-search"></i>
                      </button>
             </div>
    </div>
  </form>
</div>
<br><br><br>
<table class="table table-hover" style="table-layout:fixed;" id="articleTable">
  <tr>
    <th>Clé</th>
    <th>Désigniation</th>
    <th>quantite </th>
    <th>marge prix vente detail </th>
    <th>marge Prix de vente Gros</th>
    <th>marge prix de vente super gros </th>
    <th>Modifier</th>
  </tr>
<?php
if((isset($_GET['debut']) and !empty($_GET['debut'])) and (isset($_GET['fin']) and !empty($_GET['fin']))){
  $debut=$_GET['debut'];
  $fin=$_GET['fin'];
  $q = "SELECT * from article where  cle between $debut and $fin order by cle";
  addToLog($q);
  $r = mysqli_query($dbc,$q);
  while($articles=mysqli_fetch_array($r, MYSQLI_ASSOC)){
    ?>
    
    <tr>
      <td><?= $articles['cle'] ?></td>
      <td style="word-wrap: break-word;"><?= $articles['designiation'] ?></td>
      <td><?= $articles['quantite'] ?></td>
       <td><?= $articles['prix_vente'] ?></td>
      <td><?= $articles['prix_vente2'] ?></td>
      <td><?= $articles['prix_vente3'] ?></td>
      <td><button type="button" class="btn btn-primary btn-md" data-toggle="modal" data-target="#dialog<?= $articles['cle'] ?>">Modifier</button></td>
    </tr>
    
    </div>
    <?php
  }
}
 ?>
</table>
<?php
require_once('includes/footer.html');
 ?>
<script type="text/javascript">
$(document).ready(function() {
$("#modcat").click(function () {  
var debut_mod_cat =document.getElementById('debut_mod_cat').value;
var fin_mod_cat =document.getElementById('fin_mod_cat').value;
var catimodi21 =document.getElementById('marge_Gain20').value;
var marge_prix_vente2 =document.getElementById('marge_prix_vente2').value;
var marge_prix_vente3 =document.getElementById('marge_prix_vente3').value;


$.ajax({
            url: '20_90_8modifi_marge_gain_suivi.php',
            type: 'post',
            data: {debut_mod_cat:debut_mod_cat,fin_mod_cat:fin_mod_cat,catimodi22:catimodi21 
            ,catimodi32:marge_prix_vente2 ,catimodi63:marge_prix_vente3},
            success: function (data) {
               alert('modification avec succée');
               location.reload();
            }
        });
    });//end click function
});//end ready function
  </script>






