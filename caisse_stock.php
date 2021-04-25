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
        <h1 align="center">Caisse - Stock - Capital
        <?php
        if(isset($_GET['informatique'])){
          echo 'Informatique';
        }elseif(isset($_GET['telephonie'])){
          echo 'Téléphonie';
        }else{
        echo 'Total';
        }
        ?></h1><br>
        <table class="table table-hover">
            <tr>
                <th>Jour</th>
                <th>Caisse</th>
                <th>Prix Quantite Manquante Total</th>
                <th>Stock</th>
                <th>Capital</th>
                <!--<th>Information</th>-->
            </tr>
            <?php
            $q = "SELECT * FROM `caisse` order by caisse_id desc;";
            addToLog($q);
            $r = mysqli_query($dbc, $q);
            if ($r) {
                while ($row = mysqli_fetch_assoc($r)) {
                    $timestamp = strtotime($row['date']);
                    if(isset($_GET['informatique'])){
                      $val=$row['val'];
                      $val_stock=$row['val_stock'];
                      $capital=$row['val']+$row['val_stock'];
                    }elseif (isset($_GET['telephonie'])) {
                      $val=$row['val2'];
                      $val_stock=$row['val_stock2'];
                      $capital=$row['val2']+$row['val_stock2'];
                    }else{
                      $val=$row['val']+$row['val2'];
                      $val_stock=$row['val_stock']+$row['val_stock2'];
                      $capital=$row['val']+$row['val_stock']+$row['val2']+$row['val_stock2'];
                    }
                    ?>
                    <tr>
                        <td><?= date("d/m/Y   -   H:m:s", $timestamp) ?></td>
                        <td><?= $val ?></td>
                        <td><?= $row['somme_quantite_manquante'] ?></td>
                        <td><?= $val_stock ?></td>
                        <td><?= $capital ?></td>
                    </tr>
                    <?php
                }
            } ?>
        </table>
    </div>

<?php
  include('includes/footer.html');
?>
