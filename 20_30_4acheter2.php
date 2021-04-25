<?php
session_start();
if (!isset($_SESSION['prenom'])) {
    $url = $_SERVER['REQUEST_URI'];
    ?>
    <script type="text/javascript">
      location.href='login.php?continue=<?= $url ?>';
    </script>
    <?php
}
require_once('includes/function_inc.php');
siPermis($_SESSION['type'], basename(__FILE__, '.php'));
require_once('connect_hanout.php');
require_once('includes/header.html');
calculerStock();
if (isset($_GET['pasfournisseur'])) {
    echo "<script>alert('Pas de fournisseur choisi');</script>";
}
?>


<div class="col-md-12">
    <h1 align="center"><?= NOM ?> Vente</h1><br>
    <div class="col-md-12 text-center">
        <?php
        if (isset($_SESSION['fournisseur'])) {
        echo '<h3 style="color: blue;">Fournisseur choisi: ' . getFournisseurInfo($_SESSION['fournisseur'])['f_id'] 
        . getFournisseurInfo($_SESSION['fournisseur'])['f_nom']
        . '</h3>';
        }
        ?>
        <button class="btn btn-default" data-toggle="modal" data-target="#fourModal">Choisir Fournisseur</button>
        <br><br>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Ajouter un Produit</h4>
                </div>
                <div class="modal-body">
                    <p>
                    <form onsubmit="return false;">

                        <ul class="list-group">
                            Choisir un article:<input type="text" id="article" class="form-control"
                                                      onchange="getInfo();getInfo();" autofocus required>
                            Quantite:<input type="number" id="qte" value="1" class="form-control"><br>

                            <span id="spanpv">
            Prix Vente:<input type="number" id="pv" value="0" class="form-control">
              Prix Vente 2:<input type="number" id="pv2" value="0" class="form-control">
              Prix Vente 3:<input type="number" id="pv3" value="0" class="form-control">
                </span>
                        </ul>
                    </form>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" style="float:left;" onclick="ajouter();">Ajouter
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

                </div>
            </div>


        </div>
    </div>
    <div id="fourModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Choisir le fournisseur</h4>
                </div>
                <div class="modal-body">
                    <p>
                    <ul class="list-group">
                        <select class="form-control" id="fournisseur" autofocus>
                            <option value="">Choisir un Fournisseur</option>
                            <?php
                            $q = "SELECT * from fournisseur;";
                            addToLog($q);
                            $r = mysqli_query($dbc, $q);
                            while ($row = mysqli_fetch_assoc($r)) {
                                echo '<option value="' . $row['f_id'] . '">' . $row['f_id'] . ' / ' 
                                . $row['f_nom'] . '</option>';
                            }
                            ?>

                        </select>
                    </ul>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" style="float:left;" onclick="choix_four();">Valider
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

                </div>
            </div>


        </div>
    </div>
    <?php
    $vendeur = $_SESSION['v_id'];
    $qa = "SELECT * FROM `achat_temp` where v_id='$vendeur' order by date asc;";
    $ra = mysqli_query($dbc, $qa);
    $num = mysqli_num_rows($ra);
    if ($num > 0) { ?>
        <div class="col-md-12 text-center">
            <button class="btn btn-success btn-lg <?php if (!isset($_SESSION['fournisseur'])) {
                echo 'disabled';
            } ?>" <?php if (isset($_SESSION['fournisseur'])) {
                echo 'data-toggle="modal" data-target="#confirm-valider"';
            } ?> >Valider tous les achats
            </button>
            <br><br>
        </div>
        <table class="table table-hover" style="table-layout:fixed;" id="achatListTable">
            <tr>
                <th>Num Article</th>
                <th>Designiation</th>
                <th>Cle Fournisseur</th>
                <th>Quantite</th>
                <th>Prix Achat</th>
                <th>Prix Vente</th>
                <th>Prix Vente 2</th>
                <th>Prix Vente 3</th>
                <th>Total</th>
                <th>Supprimer</th>
            </tr>
            <?php
            $sum = 0;

            while ($row = mysqli_fetch_assoc($ra)) {
                $sum += $row['qte'] * $row['pa'];
                echo '<tr>';
                echo '<td>' . $row['cle'] . '</td>';
                echo '<td style="word-wrap: break-word;">' . getArticleInfo($row['cle'])['designiation'] . '</td>';
                echo '<td>' . $row['cle_four'] . '</td>'; // update moulay 04
                echo '<td>' . $row['qte'] . '</td>';
                echo '<td>' . $row['pa'] . '</td>';
                echo '<td>' . $row['pv'] . '</td>';
                echo '<td>' . $row['pv2'] . '</td>';
                echo '<td>' . $row['pv3'] . '</td>';
                echo '<td>' . $row['pa'] * $row['qte'] . '</td>';
                echo '<td><button data-href="includes/supprimer_achat.php?id=' . $row['cle'] . '" class="btn btn-danger" data-toggle="modal" data-target="#confirm-delete">Supprimer</button></td>
      <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                ' . getArticleInfo($row['cle'])['designiation'] . '
            </div>
            <div class="modal-body">
                Vous voulez annuler l\'achat ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                <a class="btn btn-danger btn-ok">Supprimer</a>
            </div>
        </div>
    </div>
</div>';
                echo '</tr>';
            }

            ?>
        </table>

        <div class="col-md-3" style="float:right;margin-top:60px;font-size:30px;">
            <b>Total :<?= number_format($sum, 2) ?></b>
        </div>
        <div class="col-md-12 text-center">
            <a href="includes/annuler.php?achat">
                <button type="button" class="btn btn-danger" style="float:right">Annuler L'achat</button>
            </a>
        </div>

        <div class="modal fade" id="confirm-valider" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        Confirmation
                    </div>
                    <div class="modal-body">
                        Le Total est de <?= number_format($sum, 2) ?> <br>
                        Confirmer tous les Achats (<?= $num ?>) dans cette fiche?
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-success btn-ok" href="valider.php?achat">Valider Tous</a>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                    </div>
                </div>
            </div>
        </div>

    <?php } ?>
    <div class="col-md-12 text-center">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" autofocus>Ajouter Un
            Article
        </button>
        <br><br><br>
    </div>


    <script type="text/javascript">
        function ajouter() {
            var article = document.getElementById('article').value;
            var clefour = document.getElementById('clefour').value;
            var qte = document.getElementById('qte').value;
            var pa = parseFloat(document.getElementById('pa').value);
            var pv = document.getElementById('pv').value;
            var pv2 = document.getElementById('pv2').value;
            var pv3 = document.getElementById('pv3').value;
            $.ajax({
                url: 'acheter3.php',
                type: 'get',
                data: 'article=' + article + '&qte=' + qte + '&pa=' + pa + '&pv=' + pv + '&pv2=' + pv2 + '&pv3=' + pv3 + '&clefour=' + clefour,
                success: function (output) {
                    //alert('Le produit a été ajouté'+output);
                    location.reload();
                }, error: function () {
                    alert('something went wrong, rating failed');
                }
            });
        }

        function getInfo() {
            var article = document.getElementById('article').value;
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else { // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("spanpv").innerHTML = this.responseText;
                }
            }
            xmlhttp.open("GET", "20_30_4articleinfor_achat.php?ahat&id=" + article, true);
            xmlhttp.send();
        }
    </script>
    <?php
    require_once('includes/footer.html');
    ?>
