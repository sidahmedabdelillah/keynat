<?php
session_start();
if (!isset($_SESSION['prenom'])) {
    $url = $_SERVER['REQUEST_URI'];
  ?><script type="text/javascript">location.href='login.php?continue=<?= $url ?>';</script><?php
}
if(isset($_GET['restored'])){?>
  <script type="text/javascript">
  window.open('','_parent','');
window.close();
  </script>
<?php }
$page_title = 'Articles des fournisseurs';
require_once('includes/function_inc.php');
require_once('connect_hanout.php');
require_once('includes/header.html');
?>


<div class="col-md-12">
    <h1 align="center"><?= NOM ?> Articles des fournisseurs</h1><br>

    <div class="col-md-9">
        <form>
            <div class="input-group">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Recherche par Désignation" id="searchBar">
                </div>

                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Recherche par Désignation" id="searchBar2">
                </div>

                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Recherche par Désignation" id="searchBar3">
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
        if($cle=="*"){
            $q = "select * from article_four order by designiation";
        }else{
            $q = "select * from article_four where cle=$cle order by designiation";
        }
        addToLog($q);
        $r = mysqli_query($dbc, $q);
        ?>
        <table class="table table-hover" style="table-layout:fixed;" id="articleTable">
            <tr>
                <th onclick="sortTable(0);">Clé Fournisseur</th>
                <th>Fournisseur</th>
                <th class="col-md-5">Désigniation</th>
                <th>Prix Achat</th>
                <th>Prix Vente</th>
                <th>Info</th>
            </tr>
            <?php
            while ($articles = mysqli_fetch_assoc($r)) {
                $cle = $articles['cle'];
                $designiation = str_replace("eliminer", "<em>eliminer</em>", $articles['designiation']);
                ?>
                <tr>
                    <td><?= $articles['cle_four'] ?></td>
                    <td><?= getFournisseurInfo($articles['fournisseur'])['f_nom'] ?></td>
                    <td style="word-wrap: break-word;"><?= $designiation ?></td>
                    <td><?= $articles['prix_achat'] ?></td>
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
                                    <li class="list-group-item">Clé Fournisseur<span class="badge"><?= $articles['cle_four'] ?></span></li>
                                    <li class="list-group-item">Prix Achat<span class="badge"><?= $articles['prix_achat'] ?></span></li>
                                    <li class="list-group-item">Prix Vente<span class="badge"><?= $articles['prix_vente'] ?></span></li>
                                    <li class="list-group-item">Fournisseur<span class="badge"><?= $articles['prix_vente'] ?></span></li>
                                </ul>
                            </div>
                            <div class="modal-footer">
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
        xmlhttp.open("GET", "articles.php?fournisseur&des1=" + article1 + "&des2=" + article2 + "&des3=" + article3 + "&cle=" + cle, true);
        xmlhttp.send();
    }
</script>
