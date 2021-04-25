<?php
session_start();
if (!isset($_SESSION['prenom'])) {
    $url = $_SERVER['REQUEST_URI'];
  ?><script type="text/javascript">location.href='login.php?continue=<?= $url ?>';</script><?php
}
$page_title = 'Vente';
require_once('includes/function_inc.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
require_once('connect_hanout.php');
require_once('includes/header.html');
calculerStock();
?>


<div class="col-md-12">
    <h1 align="center"><?= NOM ?> Vente + article en Quantité Zéro</h1><br>

    <div class="col-md-9">
        <form>
            <div class="input-group">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Recherche par Désignation" id="searchBar"
                           onblur="getArticles();">
                </div>

                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Recherche par Désignation" id="searchBar2"
                           onblur="getArticles();">
                </div>

                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Recherche par Désignation" id="searchBar3"
                           onblur="getArticles();">
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
    <div class="col-md-3">
        <form>
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Code" name="searchBarCode" id="searchBarCode"
                       onkeyup="getArticles();" autofocus>
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
        $q = "select * from article where (cle='$cle' OR codebar='$cle' or code='$cle') and designiation is not null order by cle";
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
                $prix_vente = $articles['prix_vente']+$articles['prix_achat'];
                $prix_vente2 = $articles['prix_vente2']+$articles['prix_achat'];
                $prix_vente3 = $articles['prix_vente3']+$articles['prix_achat'];
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
                                    <li class="list-group-item">Clé <span class="badge"><?= $articles['cle'] ?></span>
                                    </li>
                                    <li class="list-group-item">Quantité Restante<span class="badge" id="qteres<?= $articles['cle'] ?>"><?= $articles['quantite'] ?></span>
                                    </li>
                                    <li class="list-group-item">Prix Achat<span class="badge"><?= $articles['prix_achat'] ?></span></li>
                                    <li class="list-group-item">11Prix Vente<span class="badge"><?= $prix_vente ?></span></li>
                                    <li class="list-group-item">11Prix Vente 2<span class="badge"><?=$prix_vente2 ?></span></li>
                                    <li class="list-group-item">Prix Vente 3<span class="badge"><?=$prix_vente3?></span></li>
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
      var qteres = document.getElementById('qteres' + article).innerHTML;
        var qte = document.getElementById('qte' + article).value;
        var pv = document.getElementById('prix_vente' + article).value;
        console.log('qteres:'+qteres);
        console.log('qte:'+qte);
        console.log('pv:'+pv);
        if(qteres<qte){
          alert('quantite insiffusante');
        }else{
          $.ajax({
              url: 'vente2.php',
              type: 'get',
              data: 'article=' + article + '&qte=' + qte + '&pv=' + pv,
              success: function (output) {
                  //alert('Le produit a été ajouté'+output);
                  window.location.href = "index.php";
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
        xmlhttp.open("GET", "articles.php?zero&des1=" + article1 + "&des2=" + article2 + "&des3=" + article3 + "&cle=" + cle, true);
        xmlhttp.send();
    }
</script>
<script type="text/javascript">
    /*$.notify({
        // options
        icon: 'glyphicon glyphicon-warning-sign',
        title: 'Bootstrap notify',
        message: 'Turning standard Bootstrap alerts into "notify" like notifications'
    }, {
        // settings
        element: 'body',
        position: null,
        type: "success",
        allow_dismiss: true,
        newest_on_top: true,
        showProgressbar: true,
        placement: {
            from: "top",
            align: "right"
        },
        offset: 20,
        spacing: 10,
        z_index: 1031,
        delay: 5000,
        timer: 1000,
        url_target: '_blank',
        mouse_over: null,
        animate: {
            enter: 'animated fadeInDown',
            exit: 'animated fadeOutUp'
        },
        onShow: null,
        onShown: null,
        onClose: null,
        onClosed: null,
        icon_type: 'class',
        template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
        '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
        '<span data-notify="icon"></span> ' +
        '<span data-notify="title">{1}</span> ' +
        '<span data-notify="message">{2}</span>' +
        '<div class="progress" data-notify="progressbar">' +
        '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
        '</div>' +
        '<a href="{3}" target="{4}" data-notify="url"></a>' +
        '</div>'
    });*/
</script>
