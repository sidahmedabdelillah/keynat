<?php
session_start();
if (!isset($_SESSION['prenom'])) {
    $url = $_SERVER['REQUEST_URI'];
  ?><script type="text/javascript">location.href='login.php?continue=<?= $url ?>';</script><?php
}
require_once('includes/function_inc.php');
siPermis($_SESSION['type'], basename(__FILE__, '.php'));
require_once('includes/header.html');
require_once('connect_hanout.php');
if (isset($_GET['done'])) {
    ?>
    <script type="text/javascript">
        //alert('Caisse Modifié');
        //window.location.href = "ajouter_gain.php";
    </script>
    <?php
}
$gain = getGainTotal();
$vendeur = $_SESSION['v_id'];
if (isset($_GET['submitted'])) {
    $pa = $_GET['pa'];
    $pe = $_GET['pe'];
    $gain = $pa - $pe;
    if (isset($_GET['explication'])) {
        $explication = $_GET['explication'];
    } else {
        $explication = "";
    }
    $q = "INSERT INTO `gain` (`val`) VALUES ('$gain');";
    addToLog($q);
    $r = mysqli_query($dbc, $q);
    if ($r) {
        //
    } else {
        echo mysqli_error($dbc);
        exit();
    }
    $q2 = "INSERT INTO `gain_modification` (`pa`, `pe`, `v_id`, `explication`) VALUES ('$pa', '$pe', '$vendeur', '$explication');";
    addToLog($q2);
    $r2 = mysqli_query($dbc, $q2);
    if ($r2) {
        //
    } else {
        echo mysqli_error($dbc);
        exit();
    }
    ?>
    <script type="text/javascript">
        window.location.href = "ajouter_gain.php?done";
    </script>
    <?php
}
?>

<div class="col-md-10 col-md-offset-1" style="margin-top:150px;">
    <h2>Gain actuel: <?= $gain ?></h2>
    <form method="get" action="ajouter_gain.php">
        <div class="form-group">
            <label for="pa">Prix a ajouter:</label>
            <input type="number" name="pa" class="form-control" id="pa" value="0" required>
        </div>

        <div class="form-group">
            <label for="pe">Prix a enlever:</label>
            <input type="number" name="pe" class="form-control" id="pe" value="0" required>
        </div>

        <div class="form-group">
            <label for="explication">Explication:</label>
            <input type="text" name="explication" class="form-control" id="explication">
        </div>

        <input type="hidden" name="submitted" value="TRUE">
        <button type="submit" class="btn btn-primary btn-block">Modifier</button>
    </form>

</div>
<?php
require_once('includes/footer.html');
?>
