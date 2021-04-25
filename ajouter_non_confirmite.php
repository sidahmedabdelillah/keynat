<?php
session_start();
if (!isset($_SESSION['prenom'])) {
    $url = $_SERVER['REQUEST_URI'];
  ?><script type="text/javascript">location.href='login.php?continue=<?= $url ?>';</script><?php
}
require_once('includes/function_inc.php');
siPermis($_SESSION['type'], basename(__FILE__, '.php'));
require_once('connect_hanout.php');

if (isset($_GET['submitted'])) {
    $impact = mysqli_real_escape_string($dbc,trim($_GET['impact']));
    $vendeur = mysqli_real_escape_string($dbc,trim($_SESSION['v_id']));
    $description = mysqli_real_escape_string($dbc,trim($_GET['description']));
    $sur = $_GET['sur'];
    $q = "INSERT INTO `non_confirmite` (`v_id`, `description`, `impact`,`qui_fait_action`) VALUES ('$vendeur', '$description', '$impact', '$sur');";
    addToLog($q);
    $r = mysqli_query($dbc, $q);
    if ($r) {
        echo "<script>alert('Non Confirmité Ajouté sous la clé " . mysqli_insert_id($dbc) . "');</script>";
        header('location:list_non_conformite.php');
    } else {
        echo mysqli_error($dbc);
    }
}else{
include('includes/header.html');
?>

<div class="col-md-offset-5">
    <h2>Ajouter un non confirmite</h2><br>
</div>
<form class="form-horizontal" action="ajouter_non_confirmite.php">

    <div class="form-group">
        <label class="control-label col-md-2" for="description">Description:</label>
        <div class="col-md-9">
            <input type="text" class="form-control" id="description" placeholder="Description" name="description"
                   required autofocus>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-2" for="impact">Impact:</label>
        <div class="col-md-9">
            <input type="number" class="form-control" id="impact" placeholder="Impact" name="impact" value="0" required>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-2" for="sur">Sur:</label>
        <div class="col-md-9">
            <select class="form-control" name="sur">
                <option value="3">Bessafi hamel</option>
                <option value="2">Wail Skanderi</option>
                <option value="6">Torkia Hamraoui</option>
                <option value="7">Abdennour Mehdi</option>
                <option value="10">Wassim Makhlouf</option>
            </select>
        </div>
    </div>


    <div class="form-group">
        <div class="col-sm-offset-2 col-md-9">
            <input type="hidden" name="submitted" value="TRUE">
            <button type="submit" class="btn btn-default">Ajouter Non Confirmité</button>
        </div>
    </div>
</form>
<div class="col-md-6 col-md-offset-3">
  <table class="table table-bordered table-striped" style="width:100%;">
    <tr>
      <th>Point</th>
      <th>Impact</th>
    </tr>
    <tr>
      <td>Planification</td>
      <td>15</td>
    </tr>
    <tr>
      <td>Entretien Stockage Nettoyage</td>
      <td>10</td>
    </tr>
    <tr>
      <td>Reclamation Client</td>
      <td>50</td>
    </tr>
    <tr>
      <td>Autre a identifié</td>
      <td>25</td>
    </tr>
  </table>
</div>

<?php
include('includes/footer.html');
}

?>
