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
    <h1 align="center"><php?= NOM ?> Vente</h1><br>

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
        $cle = $_GET['searchBarCode'];
        $q = "select * from article where (cle=$cle OR codebar='$cle' or code='$cle' AND quantite>0) and designiation is not null order by cle";
        addToLog($q);
        $r = mysqli_query($dbc, $q);
        ?>
        <table class="table table-hover" style="table-layout:fixed;" id="articleTable">
            <tr>
                <th onclick="sortTable(0);">Clé</th>
                <th class="col-md-5">Désigniation</th>
                <th>Quantité</th>
                <th>Prix Vente</th>
                <th>Info</th>
            </tr>
            <?php
            while ($articles = mysqli_fetch_assoc($r)) {
                $cle = $articles['cle'];
                $designiation = str_replace("eliminer", "<em>eliminer</em>", $articles['designiation']);
                ?>
                <tr>
                    <td><?= $articles['cle'] ?></td>
                    <td style="word-wrap: break-word;"><?= $designiation ?></td>
                    <td><?= $articles['quantite'] ?></td>
                    <td><?= $articles['prix_vente'] ?></td>
                    <td>
                        <button type="button" class="btn btn-primary btn-md" data-toggle="modal"
                                data-target="#dialog<?= $articles['cle'] ?>">Information
                        </button>
                    </td>
                </tr>
                <div class="modal fade" id="dialog<?= $articles['cle'] ?>" role="dialog" tabindex="-1">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title"><?= $designiation ?></h4>
                            </div>
                            <div class="modal-body">
                                <ul class="list-group">
                                    <li class="list-group-item">Clé <span class="badge"><?= $articles['cle'] ?></span></li>
                                    <?php
                                    $file='img/'.$articles['cle'].'.jpg';
                                    if(file_exists($file)){?>
                                      <img src="<?= $file ?>" class="img-responsive" style="margin-left: auto;margin-right: auto;display: block;" alt="">
                                        <?php
                                      }else{?>
                                          <img src="img/default.jpg" class="img-responsive" style="margin-left: auto;margin-right: auto;display: block;" alt="">
                                      <?php }
                                     ?>

                                    <li class="list-group-item">Quantité Restante<span class="badge" id="qteres<?= $articles['cle'] ?>"><?= $articles['quantite'] ?></span></li>
                                    <li class="list-group-item">11Prix Achat<span class="badge" id="prix_achat<?= $articles['cle'] ?>"><?= $articles['prix_achat'] ?></span></li>
                                    <li class="list-group-item">Prix Vente<span class="badge"><?= $articles['prix_vente'] ?></span></li>
                                    <li class="list-group-item">Prix Vente 2<span class="badge"><?= $articles['prix_vente2'] ?></span></li>
                                    <li class="list-group-item">Prix Vente 3<span class="badge"><?= $articles['prix_vente3'] ?></span></li>
                                    Quantité A vendre:
                                    <li class="list-group-item"><input type="number" id="qte<?= $articles['cle'] ?>" value="1" class="form-control"></li>
                                    Prix Vente: <li class="list-group-item"><input type="number" id="prix_vente<?= $articles['cle'] ?>" value="<?= $articles['prix_vente'] ?>" class="form-control" ></li>
                                </ul>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" style="float:left;"
                                        onclick="ajouterVente(<?= $articles['cle'] ?>);">Ajouter au Panier
                                </button>
                                <button type="button" class="btn btn-default" style="float:left;"
                                        onclick="print('print_code.php?id=<?= $articles['cle'] ?>');">Imprimer
                                </button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                            </div>
                        </div>

                    </div>
                </div>
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
if (isset($_GET['searchBarCode']) &&!empty($_GET['searchBarCode'])){
    ?>
    <script type="text/javascript">
        $(window).on('load', function () {
            $('#dialog<?= $cle ?>').modal('show');
        });
    </script>
<?php }
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
        xmlhttp.open("GET", "articles.php?vente&des1=" + article1 + "&des2=" + article2 + "&des3=" + article3 + "&cle=" + cle, true);
        xmlhttp.send();
    }
</script>
