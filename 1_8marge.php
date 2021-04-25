<?php
require_once('includes/header.html');
if (!isset($_SESSION['prenom'])) {
    $url = $_SERVER['REQUEST_URI'];
    ?>
    <script type="text/javascript">
      location.href='login.php?continue=<?= $url ?>';
    </script>
    <?php
}
if(isset($_GET['restored'])){?>
  <script type="text/javascript">
  window.open('','_parent','');
window.close();
  </script>
<?php }
$page_title = 'Vente';
require_once('includes/function_inc.php');
require_once('connect_hanout.php');
?>
<div class="col-md-12">
    <h1 align="center"><?= NOM ?> Vente</h1><br>
    <div class="col-md-9">
        <form>
            <div class="input-group">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Recherche par Désignation" id="searchBar" onblur="getArticles();">
                </div>

                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Recherche par Désignation" id="searchBar2" onblur="getArticles();">
                </div>

                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Recherche par Désignation" id="searchBar3" onblur="getArticles();">
                </div>

                <div class="input-group-btn">
                  <button class="btn btn-default" name="button" type="button" onclick="getArticles();"><i class="glyphicon glyphicon-search" ></i></button>
                  <button class="btn btn-default" type="button" onclick="resetBtn();">Reset</button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-3">
        <form>
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Code" name="searchBarCode" id="searchBarCode"
                       onchange="getArticles();" autofocus>
                <div class="input-group-btn">
                    <button class="btn btn-default" type="submit">
                        <i class="glyphicon glyphicon-search"></i>
                        <button class="btn btn-default" type="button" onclick="resetBtn();">Reset</button>
                    </button>
                </div>
            </div>

        </form>
    </div>

    <br><br><br>
    <div id='articleTableDiv'>
        <?php
        if (isset($_GET['searchBarCode']) &&!empty($_GET['searchBarCode'])){
       ?>
        <table class="table table-hover" style="table-layout:fixed;" id="articleTable">
            <?php
            while ($articles = mysqli_fetch_assoc($r)) {
              ?>
            <?php } ?>
    </div>
    <?php
    }
    ?>

    </table>
</div>
<script type="text/javascript">
    function ajouterVente(article) {
      var qteres = parseInt(document.getElementById('qteres' + article).innerHTML);
        var qte = parseInt(document.getElementById('qte' + article).value);
        var pv = parseFloat(document.getElementById('prix_vente' + article).value);
        var prix_achat = parseInt(document.getElementById('prix_achat' + article).innerHTML);
        if(qteres<qte){
          alert('quantite insiffusante');
        }else if (prix_achat>pv) {
          alert('Prix inférieur au prix d\'achat');
        }else{
          $.ajax({ 
              url: 'vente2.php',
              type: 'get',
              data: 'article=' + article + '&qte=' + qte + '&pv=' + pv,
              success: function (output) {
                  
                  $('#nombreDesVentes').text(parseInt($('#nombreDesVentes').text()) + 1);
                  $('#dialog'+article).modal('hide');
                  $('#venteLiVente').animate({opacity: '0'}, 80);
                  $('#venteLiVente').animate({opacity: '1'}, 80);
                  $('#venteLiVente').animate({opacity: '0'}, 80);
                  $('#venteLiVente').animate({opacity: '1'}, 80);
                  $('#venteLiVente').animate({opacity: '0'}, 80);
                  $('#venteLiVente').animate({opacity: '1'}, 80);
                  $('#venteLiVente').animate({opacity: '0'}, 80);
                  $('#venteLiVente').animate({opacity: '1'}, 80);
              }, error: function () {
                  alert('something went wrong, rating failed');
              }
          });
        }
    }
</script>
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
            xmlhttp = new XMLHttpRequest();
        } else { // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("articleTableDiv").innerHTML = this.responseText;
            }
        }
        xmlhttp.open("GET", "1_8articles.php?vente&des1=" + article1 + "&des2=" + article2 + "&des3=" + article3 + "&cle=" + cle, true);
        xmlhttp.send();
    }
</script>
