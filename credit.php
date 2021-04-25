<?php
session_start();
if(!isset($_SESSION['prenom'])){
    $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('connect_hanout.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
require_once('includes/function_inc.php');
if(isset($_POST['submitted'])){
  $client=$_SESSION['client'];
  $somme=$_POST['somme'];
  $q="INSERT INTO `credit` (`c_id`, `client`, `val`, `date`) VALUES ('', '1', '200', CURRENT_TIMESTAMP);";
  addToLog($q);
  $r=mysqli_query($dbc,$q);
  if($r){
    enleverDeCaisse($somme);
  }else{
    echo mysqli_error($dbc);
  }
}
require_once('includes/header.html');
calculerStock();
?>


<div class="col-md-12">
    <h1 align="center"><?= NOM ?> Crédit</h1><br>
    <div class="col-md-12 text-center">
        <?php
        if(isset($_SESSION['client'])){
            echo'<h3 style="color: blue;">Client choisi: '.getClientInfo($_SESSION['client'])['nom'].'</h3>';
        }
        ?>
        <button class="btn btn-default" data-toggle="modal" data-target="#fourModal">Choisir client</button>
        <a href="includes/annuler.php?client&credit"><button class="btn btn-danger">Supprimer Client</button></a>
        <br><br><br>
        <form class="form-horizontal" method="post" action="credit.php">
          <div class="form-group">
            <label class="control-label col-md-2" for="somme">Somme:</label>
            <div class="col-md-9">
              <input type="number" class="form-control" id="client" placeholder="Somme" name="somme" required>
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-offset-2 col-md-9">
              <input type="hidden" name="submitted" value="TRUE">
              <button type="submit" class="btn btn-default" <?php if(!isset($_SESSION['client'])){echo 'disabled';} ?>>Ajouter Client</button>
            </div>
          </div>
        </form>
    </div>
    <div id="fourModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Choisir le client</h4>
                </div>
                <div class="modal-body">
                    <p>
                    <ul class="list-group">
                        <select class="form-control" id="client" autofocus>
                            <option value="">Choisir un client</option>
                            <?php
                            $q="SELECT * from client;";
                            addToLog($q);
                            $r=mysqli_query($dbc,$q);
                            while($row=mysqli_fetch_assoc($r)){
                                echo'<option value="'.$row['client_id'].'">'.$row['nom'].'</option>';
                            }
                            ?>

                        </select>
                    </ul>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" style="float:left;" onclick="choix_client();">Valider</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

                </div>
            </div>


        </div>
    </div>
</div>

<?php
include('includes/footer.html') ?>
