<?php
require_once('connect_hanout.php');
require_once('includes/function_inc.php');
$art1 = $_GET['des1'];
$art2 = $_GET['des2'];
$art3 = $_GET['des3'];
$cle = $_GET['cle'];
if (isset($_GET['vente'])) {
    if (is_numeric($cle)) {
        $q = "select * from article where (cle=$cle OR code=$cle AND quantite>0) and designiation is not null order by cle";
    } else {
        $q = "select * from article where (designiation like '%$art1%' AND  designiation like '%$art2%' AND designiation like '%$art3%' AND quantite>0) and designiation is not null order by prix_achat";
    }
    addToLog($q);

    $r = mysqli_query($dbc, $q);
    ?>
<table class="table table-hover" style="table-layout:fixed;" id="articleTable">
    <tr>
        <th>1Clé</th>
        <th class="col-md-5">2Désigniation</th>
        <th>3Quantité</th>
        <th>4Prix Vente</th>
        <th>5Info</th>
    </tr>
    <?php
        while ($articles = mysqli_fetch_assoc($r)) {
            $cle = $articles['cle'];
            $prix_vente = $articles['prix_vente']+$articles['prix_achat'];
            $prix_vente2 = $articles['prix_vente2']+$articles['prix_achat'];
            $prix_vente3 = $articles['prix_vente3']+$articles['prix_achat'];
            $designiation = str_replace("eliminer", "<em>eliminer</em>", $articles['designiation']);
            if(strstr( mb_strtolower($designiation), 'rouge' )){
                $bg = "red";
                $color = "white";
            }elseif(strstr( mb_strtolower($designiation), 'bleu' )){
                $bg = "blue";
                $color = "white";
            }elseif(strstr( mb_strtolower($designiation), 'jaune' )){
                $bg = "yellow";
                $color="black";
            }elseif(strstr( mb_strtolower($designiation), 'noir' )){
                $bg = "black";
                $color = "white";
            }else{
                $bg = "";
                $color = "black";
            }
            ?>
    <tr>
        <td><?= $articles['cle'] ?></td>
        <td style="word-wrap: break-word;color:<?= $color ?>;background-color:<?= $bg ?>"><?= $designiation ?></td>
        <td><?= $articles['quantite'] ?></td>
        <td><?= $prix_vente ?></td>
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
                        <li class="list-group-item">Quantité Restante<span class="badge"
                                id="qteres<?= $articles['cle'] ?>"><?= $articles['quantite'] ?></span>
                        </li>
                        <li class="list-group-item">Prix Achat<span class="badge"
                                id="prix_achat<?= $articles['cle'] ?>"><?= $articles['prix_achat'] ?></span></li>
                        <li class="list-group-item">Prix Vente<span class="badge"><?=$prix_vente ?></span></li>
                        <li class="list-group-item">Prix Vente 2<span class="badge"><?=$prix_vente2  ?></span></li>
                        <li class="list-group-item">Prix Vente 3<span class="badge"><?=$prix_vente3  ?></span></li>
                        <li class="list-group-item">Date d `Achat <span
                                class="badge"><?=$articles['date_achat']  ?></span></li>
                        Quantité A vendre:
                        <li class="list-group-item"><input type="number" id="qte<?= $articles['cle'] ?>" value="1"
                                class="form-control"></li>
                        Prix Vente:
                        <li class="list-group-item"><input type="number" id="prix_vente<?= $articles['cle'] ?>"
                                value="<?= $prix_vente ?>" class="form-control"></li>
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
    <?php
        }

        ?>

</table>

<?php }  ?>