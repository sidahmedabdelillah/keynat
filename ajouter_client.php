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

if (isset($_POST['submitted']) AND $_POST['client']!=="") {
    $client = ucfirst(strtolower($_POST['client']));
    $wilaya = $_POST['wilaya'];
    $q = "INSERT INTO `client` (`nom`, `prenom`) VALUES ('$client', '$wilaya');";
    addToLog($q);
    $r = mysqli_query($dbc, $q);
    if ($r) {
        echo "<script>alert('Client ajouté');</script>";
    } else {
        echo mysqli_error($dbc);
    }
}
$page_title = "Ajouter Client";
include('includes/header.html');
?>

<div class="col-md-offset-5">
    <h2>Ajouter un article</h2><br>
</div>
<form class="form-horizontal" method="post" action="ajouter_client.php">
    <div class="form-group">
        <label class="control-label col-md-2" for="client">Nom Client:</label>
        <div class="col-md-9">
            <input type="text" class="form-control" id="client" placeholder="Designiation" name="client" required>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-2" for="wilaya">Wilaya Client:</label>
        <div class="col-md-9">
            <input type="text" class="form-control" id="wilaya" placeholder="Votre Lieu de Naissance" name="wilaya"
                   required>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-md-9">
            <input type="hidden" name="submitted" value="TRUE">
            <button type="submit" class="btn btn-default">Ajouter Client</button>
        </div>
    </div>
</form>


<?php
include('includes/footer.html');
?>
