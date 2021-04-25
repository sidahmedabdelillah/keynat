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

if (isset($_POST['submitted'])) {
    $designiation = strtolower($_POST['designiation']);
    $quantite = 0;
    $pa = 0;
    $pv = 0;
    $seuil1 = $_POST['seuil1'];
    $seuil2 = $_POST['seuil2'];
    $seuil3 = $_POST['seuil3'];
    $q = "INSERT INTO `article`(`designiation`,`quantite`, `prix_achat`, `prix_vente`, `seuil`, `seuil2`, `seuil3`) VALUES ('$designiation',$quantite,$pa,$pv,$seuil1,$seuil2,$seuil3);";
    addToLog($q);
    $r = mysqli_query($dbc, $q);
    if ($r) {
        echo "<script>alert('Article Ajouté sous la clé " . mysqli_insert_id($dbc) . "');</script>";
    } else {
        echo mysqli_error($dbc);
    }
}
include('includes/header.html');
?>

<div class="col-md-offset-5">
    <h2>Ajouter un article</h2><br>
</div>
<form class="form-horizontal" method="post" action="ajouter_article.php">
    <div class="form-group">
        <label class="control-label col-md-2" for="designiation">Designiation:</label>
        <div class="col-md-9">
            <input type="text" class="form-control" id="designiation" placeholder="Designiation" name="designiation"
                   required autofocus>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-2" for="seuil1">Seuil 1:</label>
        <div class="col-md-9">
            <input type="number" class="form-control" id="seuil1" placeholder="Votre Lieu de Naissance" name="seuil1"
                   value="0" required>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-2" for="seuil2">Seuil 2:</label>
        <div class="col-md-9">
            <input type="number" class="form-control" id="seuil2" placeholder="Votre Lieu de Naissance" name="seuil2"
                   value="0" required>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-2" for="seuil3">Seuil 3:</label>
        <div class="col-md-9">
            <input type="number" class="form-control" id="seuil3" placeholder="Votre Lieu de Naissance" name="seuil3"
                   value="0" required>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-md-9">
            <input type="hidden" name="submitted" value="TRUE">
            <button type="submit" class="btn btn-default">Ajouter Article</button>
        </div>
    </div>
</form>


<?php
include('includes/footer.html');
?>
