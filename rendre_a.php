<?php
session_start();
if(!isset($_SESSION['prenom'])){
    $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
require_once('connect_hanout.php');
$page_title="Rendu";
require_once('includes/header.html');
?>


<div class="col-md-12">
    <h1 align="center"><?= NOM ?> Rendu</h1><br>
    <div class="col-md-12 text-center">
        <?php
        if(isset($_SESSION['client'])){
            echo'<h3 style="color: blue;">Client choisi: '.getClientInfo($_SESSION['client'])['nom'].'</h3>';
        }
        ?>
        <button class="btn btn-default" data-toggle="modal" data-target="#fourModal">Choisir client</button>
        <a href="includes/annuler.php?client"><button class="btn btn-danger">Supprimer Client</button></a>
        <br><br>
        <button type="button" class="btn btn-primary" id="ajouterbtn" onclick="ajouterRendu();checkAjouter();" formnovalidate>Ajouter</button><br><br>
        <div class="col-md-6">
          Choisir un article:<input type="number" id="article" class="form-control" onchange="checkAjouter();getInfo();getInfo();" autofocus required>
        </div>
        <div class="col-md-6">
          Quantite:<input type="number" id="qte" value="1" class="form-control"><br>
        </div>
        <div class="col-md-12">
              <span id="spanpv">
                <ul class="list-group">
                  <li class="list-group-item"><b>Designiation:</b>  <span id="designiation"></span></li>
                </ul>
              </span>
        </div><br>
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
    <?php
    $sum=0;
    $vendeur=$_SESSION['v_id'];
    $q="select * from Rendu_temp where v_id=$vendeur;";
    addToLog($q);
    $r=mysqli_query($dbc,$q);
    $num=mysqli_num_rows($r);
    if($num>0){ ?>
        <table class="table table-hover" style="table-layout:fixed;" id="achatListTable">
            <tr>
                <th>Num Article</th>
                <th>Designiation</th>
                <th>Quantite</th>
                <th>Prix Rendu</th>
                <th>Prix Total</th>
                <th>Supprimer</th>
            </tr>
            <?php
            while($row=mysqli_fetch_assoc($r)){
                $sum+=$row['qte']*$row['pv'];
                echo'<tr>';
                echo '<td>'.$row['cle'].'</td>';
                echo '<td style="word-wrap: break-word;">'.getArticleInfo($row['cle'])['designiation'].'</td>';
                echo '<td>'.$row['qte'].'</td>';
                echo '<td>'.$row['pv'].'</td>';
                echo '<td>'.$row['qte']*$row['pv'].'</td>';
                echo '<td><button data-href="includes/supprimer.php?Rendu&id='.$row['cle'].'" class="btn btn-danger" data-toggle="modal" data-target="#confirm-delete">Supprimer</button></td>
      <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                '.getArticleInfo($row['cle'])['designiation'].'
            </div>
            <div class="modal-body">
                Vous voulez annuler la Rendu ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>';
                echo'</tr>';
            }

            ?>
        </table>

        <div class="col-md-4" style="float:right;margin-top:60px;font-size:30px;">

          <div class="col-md-10">
            <b>Total ?? payer: <?= $sum ?></b>
          </div>
          <?php if(isset($_SESSION['client'])){?>
            <br>
            <b style="font-size:20px;">Cr??dit Pr??c??dent: <?= getClientInfo($_SESSION['client'])['credit'] ?>DA</b><br>
          <label class="control-label col-md-6" for="total">Versement:</label>
          <div class="col-md-6">
            <input type="number" class="form-control" id="total" value="<?= $sum ?>" name="total" required style="font-size:30px;">
          </div>
        <?php }else{?>
          <input type="hidden" name="total" id="total" value="<?= $sum ?>">
        <?php }?>
         </div>
        <div class="col-md-12 text-center">
            <button class="btn btn-success" btn-lg data-toggle="modal" data-target="#confirm-valider">Valider tous les Rendus</button><br><br>
            <a href="includes/annuler.php?Rendu"><button type="button" class="btn btn-danger" style="float:right">Annuler tous les Rendus</button></a>
            <br>
        </div>

        <div class="modal fade" id="confirm-valider" tabindex="-1" role="dialog"aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        Confirmation
                    </div>
                    <div class="modal-body">
                        Le Total est de <?= number_format($sum,2) ?> <br>
                        Confirmer tous les Rendus dans cette fiche?
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success btn-ok" onclick="validerRendu();">Valider Tous</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>



    <script type="text/javascript">
    function checkAjouter(){
      // var designiation = document.getElementById('designiation').innerHTML;
      // var ajouterbtn = document.getElementById('ajouterbtn');
      // if(designiation==""){
      //   ajouterbtn.setAttribute("disabled","disabled");
      // }else{
      //   ajouterbtn.removeAttribute("disabled");
      // }
    }
        function ajouterRendu(){
            var article = document.getElementById('article').value;
            var qte = parseFloat(document.getElementById('qte').value);
            var qter = parseFloat(document.getElementById('qter').value);
            var designiation = document.getElementById('designiation').innerHTML;
            var pv = document.getElementById('pv').value;

          if(designiation==""){
              alert('Article n\'a pas de nom');
              return;
            }
            $.ajax({
                url: 'rendu2.php',
                type: 'get',
                data: 'article='+article+'&qte='+qte+'&pv='+pv,
                success: function(output)
                {
                    //alert('Le produit a ??t?? ajout??'+output);
                    location.reload();
                }, error: function()
                {
                    alert('something went wrong, rating failed');
                }
            });
        }

        function validerRendu(){
            var total = parseInt(document.getElementById('total').value);
            $.ajax({
                url: 'valider.php',
                type: 'get',
                data: 'rendu&total='+total,
                success: function(output)
                {
                    //window.location.href="facture_Rendu.php?id="+output;
                }, error: function()
                {
                    alert('something went wrong, rating failed');
                }
            });
        }

        function getInfo() {
            var article = document.getElementById('article').value;
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp=new XMLHttpRequest();
            } else { // code for IE6, IE5
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange=function() {
                if (this.readyState==4 && this.status==200) {
                    document.getElementById("spanpv").innerHTML=this.responseText;
                }
            }
            xmlhttp.open("GET","articleinfor.php?id="+article,true);
            xmlhttp.send();
        }
    </script>
    <?php
    require_once('includes/footer.html');
    ?>
