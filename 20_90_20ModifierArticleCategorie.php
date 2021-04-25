<?php
session_start();
if(!isset($_SESSION['prenom'])){
  $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
$page_title="Modifier";
require_once('includes/header.html');
require_once('connect_hanout.php');
?>
<div class="col-md-12">
<h1 align="center">Modification + désigniation vide</h1><br>

<div class="col-md-8">
  <form>
    <div class="input-group">
      <div class="col-md-4">
        <input type="text" class="form-control" placeholder="Recherche par Désignation" id="searchBar" onkeydown="filterTable()">
      </div>
      <div class="col-md-4">
        <input type="text" class="form-control" placeholder="Recherche par Désignation" id="searchBar2" onkeydown="filterTable()">
      </div>
      <div class="col-md-4">
        <input type="text" class="form-control" placeholder="Recherche par Désignation" id="searchBar3" onkeydown="filterTable()">
      </div>

      <div class="input-group-btn">
        <button class="btn btn-default" type="submit">
          <i class="glyphicon glyphicon-search"></i>
          <button class="btn btn-default" type="button" onclick="resetBtn();">Reset</button>
        </button>
      </div>
    </div>
  </form>
</div>
<div class="col-md-4">
  <form>
    <div class="input-group">
      <input type="text" class="form-control" placeholder="Recherche par Code" id="searchBarCode" onkeydown="filterTableCode()">
      <div class="input-group-btn">
        <button class="btn btn-default" type="submit">
          <i class="glyphicon glyphicon-search"></i>
            <button class="btn btn-default" type="button" onclick="resetBtn();">Reset</button>
        </button>
      </div>
    </div>

  </form>
</div>
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
      <div class="col-md-8">
                    <input type="text" class="form-control" placeholder="categorie" id="catimodi">
      </div>

      <div class="input-group-btn">
      </div>
      <div  name="modifierCat" id="modcat" class="btn btn-primary"> modifier cat</i>
      </div> 
          
      <div class="input-group-btn">
        <button class="btn btn-default" type="submit">
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
    <th>Quantité</th>
    <th>Prix Achat</th>
    <th>Date Achat</th>
    <th>Categorie</th>
    <th>Modifier</th>
  </tr>
<?php
if((isset($_GET['debut']) and !empty($_GET['debut'])) and (isset($_GET['fin']) and !empty($_GET['fin']))){
  $debut=$_GET['debut'];
  $fin=$_GET['fin'];
  $q = "SELECT * from article where cle between $debut and $fin order by cle";
  addToLog($q);
  $r = mysqli_query($dbc,$q);
  while($articles=mysqli_fetch_array($r, MYSQLI_ASSOC)){
    ?>
    <tr>
      <td><?= $articles['cle'] ?></td>
      <td style="word-wrap: break-word;"><?= $articles['designiation'] ?></td>
      <td style="word-wrap: break-word;"><?= $articles['quantite'] ?></td>
      <td><?= $articles['prix_achat'] ?></td>
      <td><?= $articles['date_achat'] ?></td>
      <td><?= $articles['obser1'] ?></td>
      <td><button type="button" class="btn btn-primary btn-md" data-toggle="modal" data-target="#dialog<?= $articles['cle'] ?>">Modifier</button></td>
    </tr>
    <div class="modal fade" id="dialog<?= $articles['cle'] ?>" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title"><?= $articles['designiation'] ?></h4>
            </div>
            <div class="modal-body">
              <p>
                <ul class="list-group">
                  <li class="list-group-item">Designiation<input type="text" class="form-control" id="designiation_a<?= $articles['cle'] ?>" value="<?= $articles['designiation'] ?>" <?php if($articles['quantite']>0){echo 'required';}?>></li>
                  <li class="list-group-item">Codebar<input type="text" class="form-control" id="codebar<?= $articles['cle'] ?>" value="<?= $articles['codebar'] ?>"></li>
                  <li class="list-group-item">Clé <span class="badge"><?= $articles['cle'] ?></span></li>
                  <li class="list-group-item">Quantité Restante<span class="badge"><?= $articles['quantite'] ?></span></li>
                  <li class="list-group-item">Prix Achat<span class="badge"><?= $articles['prix_achat'] ?></span></li>
                  <li class="list-group-item">Prix Vente<span class="badge"><?= $articles['prix_vente'] ?></span></li>
                  <li class="list-group-item">Seuil<span class="badge"><?= $articles['seuil'] ?></span></li>
                  <li class="list-group-item">Date Achat<span class="badge"><?= $articles['date_achat'] ?></span></li>
                  <li class="list-group-item">Quantité<input type="number" class="form-control" id="quantite_a<?= $articles['cle'] ?>" value="<?= $articles['quantite'] ?>"></li>
                  <li class="list-group-item">Prix Achat<input type="text" class="form-control" id="prix_achat_a<?= $articles['cle'] ?>" value="<?= $articles['prix_achat'] ?>"></li>
                  <li class="list-group-item">Prix Vente<input type="text" class="form-control" id="prix_vente_a<?= $articles['cle'] ?>" value="<?= $articles['prix_vente'] ?>"></li>
                  <li class="list-group-item">Observation 1<input type="text" class="form-control" id="obser1_<?= $articles['cle'] ?>" value="<?= $articles['obser1'] ?>"></li>
                  <li class="list-group-item">Observation 1<input type="text" class="form-control" id="obser2_<?= $articles['cle'] ?>" value="<?= $articles['obser2'] ?>"></li>
                </ul>
              </p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" style="float:left;"   onclick="acheter(<?= $articles['cle'] ?>);">Modifier</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

            </div>
          </div>

        </div>
      </div>
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
 function getArticles() {
     var article1 = document.getElementById('searchBar').value;
     var article2 = document.getElementById('searchBar2').value;
     var article3 = document.getElementById('searchBar3').value;
     var cle = parseInt(document.getElementById('searchBarCode').value);
     if (window.XMLHttpRequest) {
         // code for IE7+, Firefox, Chrome, Opera, Safari
         xmlhttp=new XMLHttpRequest();
     } else { // code for IE6, IE5
         xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
     }
     xmlhttp.onreadystatechange=function() {
         if (this.readyState==4 && this.status==200) {
             document.getElementById("articleTableDiv").innerHTML=this.responseText;
         }
     }
     xmlhttp.open("GET","articles.php?vente&des1="+article1+"&des2="+article2+"&des3="+article3+"&cle="+cle,true);
     xmlhttp.send();
 }
 </script>
<script type="text/javascript">
$(document).ready(function() {
$("#modcat").click(function () {  
var debut_mod_cat =document.getElementById('debut_mod_cat').value;
var fin_mod_cat =document.getElementById('fin_mod_cat').value;
var catimodi =document.getElementById('catimodi').value;
$.ajax({
            url: '20_90_20modifier_plus_cat.php',
            type: 'post',
            data: {debut_mod_cat:debut_mod_cat,fin_mod_cat:fin_mod_cat,catimodi:catimodi},
            success: function (data) {
               alert('modification avec succée');
               location.reload();
            }
        });
    });//end click function
});//end ready function
  </script>

