<?php
session_start();
if (!isset($_SESSION['prenom'])) {
    $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
require_once('connect_hanout.php');
include('includes/header.html');
?>

<div class="col-md-12">
    <h1 align="center">Difference Caisse PC - Caisse Reel</h1>
    <table class="table table-hover">
        <tr>
            <th>Jour</th>
            <th>Difference</th>
            <th>Vendeur</th>
        </tr>
        <?php
        if($_SESSION['type']<>1){
          $q = "SELECT * FROM `caisse_verification` WHERE month(date)=month(CURDATE()) order by date DESC";
        }else{
          $q = "SELECT * FROM `caisse_verification` WHERE month(date)=month(CURDATE()) order by date DESC";
        }
        addToLog($q);
        $r = mysqli_query($dbc, $q);
        if ($r) {
            while ($row = mysqli_fetch_assoc($r)) {
                ?>
                <tr>
                    <td><?= $row['date'] ?></td>
                    <td><?= $row['diff'] ?></td>
                    <td><?= getVendeurInfo($row['v_id'])['prenom'] ?></td>
                </tr>

                <?php
            }
        } ?>
    </table>
</div>

<?php
include('includes/footer.html'); ?>
