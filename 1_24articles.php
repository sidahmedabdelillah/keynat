<?php
require_once('connect_hanout.php');
require_once('includes/function_inc.php');
$art4 = $_GET['des4'];
$art5 = $_GET['des5'];
$art6 = $_GET['des6'];
$cle = $_GET['cle'];
if (isset($_GET['vente'])) {
    ?>
    <table class="table table-hover" style="table-layout:fixed;" id="articleTable">
            </table>
<?php } elseif (isset($_GET['zero'])) {
    if (is_numeric($cle)) {
        $q = "select * from article where (cle=$cle OR code=$cle) and designiation is not null order by cle";
    } else {
        $q = "select * from article where (obser1 like '%$art4%' AND obser1 like '%$art5%' AND obser1 like '%$art6%' ) and quantite >0 and designiation is not null order by cle";
    }
    addToLog($q);
    $r = mysqli_query($dbc, $q);
    ?>
    <table class="table table-hover" style="table-layout:fixed;" id="articleTable">
    <tr>
        <th onclick="sortTable(0);">Clé</th>
        <th class="col-md-5">Désigniation</th>
        <th>Quantité</th>
        <th>Prix Vente</th>
        <th>Categorie</th>
        <th>InformationHamel</th>
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
            <td><?= $articles['obser1'] ?></td>
            <td>
                <button type="button" class="btn btn-primary btn-md" data-toggle="modal"
                        data-target="#dialog<?= $articles['cle'] ?>">Information
                </button>
            </td>
        </tr>
        </div>
        <?php
    }
    ?>
    </table><?php
}  ?>
