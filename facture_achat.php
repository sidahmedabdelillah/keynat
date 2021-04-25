<?php
session_start();
if(!isset($_SESSION['prenom'])){
    $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
$page_title="Facture Achat";
require_once('includes/header.html');
require_once('connect_hanout.php');
calculerStock();
?>


<div class="col-md-12">
    <h1 align="center"><?= NOM ?> facture achat</h1><br>
    <div class="col-md-12 text-center">
        <?php
        if(isset($_SESSION['f_a'])){
            echo'<h3 style="color: blue;">Facture Achat choisi: '.$_SESSION['f_a'].'</h3>';
        }
        ?>
        <button class="btn btn-default" data-toggle="modal" data-target="#fourModal">Choisir Facture Achat</button>
        <br><br>
    </div>

    <!-- Modal -->
    <div id="fourModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Choisir la facture</h4>
                </div>
                <div class="modal-body">
                    <p>
                    <ul class="list-group">
                        <select class="form-control" id="facture" autofocus>
                            <option value="">Choisir une facture</option>
                            <?php
                            $q="SELECT * from facture_achat;";
                            addToLog($q);
                            $r=mysqli_query($dbc,$q);
                            while($row=mysqli_fetch_assoc($r)){?>
                                <option value="<?= $row['facture_achat_id'] ?>" <?php if(isset($_SESSION['f_a']) AND $row['facture_achat_id']==$_SESSION['f_a']){echo 'selected';}?>><?= $row['facture_achat_id'] ?></option>
                            <?php }
                            ?>

                        </select>
                    </ul>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" style="float:left;" onclick="choix_f_a();">Valider</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

                </div>
            </div>


        </div>
    </div>

        <table class="table table-hover" style="table-layout:fixed;" id="achatListTable">
            <tr>
                <th>Num Article</th>
                <th>Designiation</th>
                <th>Quantite</th>
                <th>Prix Vente</th>
            </tr>
          <?php
          if(isset($_SESSION['f_a'])){
          $facture=$_SESSION['f_a'];
          $q="SELECT * FROM `achat` WHERE `facture_achat_id`=$facture;";
          addToLog($q);
          $r=mysqli_query($dbc,$q);
          $s=0;
          while($row=mysqli_fetch_assoc($r)){
            $s+=$row['qte_achat']*$row['prix_achat_fournisseur'];
            ?>
            <tr>
                <td><?= $row['article_id'] ?></td>
                <td><?= getArticleInfo($row['article_id'])['designiation'] ?></td>
                <td><?= $row['qte_achat'] ?></td>
                <td><?= $row['prix_achat_fournisseur'] ?></td>
            </tr>
            <?php
          }
          ?>
            <div class="col-md-3" style="float:right;margin-top:60px;font-size:30px;">
                <b>Total :<?= number_format($s,2) ?></b>
            </div>
          <?php
        }else{
            echo 'pas de facture choisi';
          }?>

    </table>


    <?php
    require_once('includes/footer.html');
    ?>
