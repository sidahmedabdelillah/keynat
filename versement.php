<?php
session_start();
if(!isset($_SESSION['prenom'])){
    $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
require_once('connect_hanout.php');
$page_title="Versement";
require_once('includes/header.html');

if(isset($_GET['prix'])){
  $prix=$_GET['prix'];
  $vendeur=$_SESSION['v_id'];
  if(isset($_SESSION['client'])){
    $client=$_SESSION['client'];
  }else{
    header('location:versement.php');
  }
  $client_info=getClientInfo($client);
  $credit_client=$client_info['credit']+$prix;
  $q5="UPDATE `client` SET `credit` = '$credit_client' WHERE `client`.`client_id` = $client;";
  addToLog($q5);
  $r5=mysqli_query($dbc,$q5);
  if($r5){
    //
  }else{
    echo mysqli_error($dbc);
  }
  $caisse=getCaisseInfo();
  $val_caisse=$caisse['val']+$prix;
  newCaisse($val_caisse);
  $q="INSERT INTO `versement` (`v_id`, `c_id`, `val`) VALUES ('$vendeur', '$client', '$prix');;";
  addToLog($q);
  $r=mysqli_query($dbc,$q);
  if($r){
    ?>
    <script type="text/javascript">
      alert('Versement Ajouté');
    </script>
    <?php
    unset($_SESSION['client']);
  }else{
    echo mysqli_error($dbc);
    exit();
  }

}
?>


<div class="col-md-12">
    <h1 align="center"><?= NOM ?> Vente</h1><br>
    <div class="col-md-12 text-center">
        <?php
        if(isset($_SESSION['client'])){
            echo'<h3 style="color: blue;">Client choisi: '.getClientInfo($_SESSION['client'])['nom'].'</h3>';
        }
        ?>
        <button class="btn btn-default" data-toggle="modal" data-target="#fourModal">Choisir client</button>
        <a href="includes/annuler.php?client"><button class="btn btn-danger">Supprimer Client</button></a>
        <br><br>
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
                    Nouveau Client:<li class="list-group-item"><input id="nouv_client" class="form-control"></li>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" style="float:left;" onclick="choix_client();">Valider</button>
                    <button type="submit" class="btn btn-default" style="float:left;" onclick="nouv_client();">Nouveau Client</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

                </div>
            </div>


        </div>
    </div>
    <br><br>
    <form class="" action="versement.php">
    <div class="col-md-6 text-center">
          Versement:<input type="number" id="prix" name="prix" class="form-control" autofocus required>
    </div>
    <div class="form-actions">
      <input type="submit" class="btn btn-primary">
    </div>
    </div>
</form>
</div>
    <?php
    require_once('includes/footer.html');
    ?>
