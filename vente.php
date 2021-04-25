<?php
session_start();
if(!isset($_SESSION['prenom'])){
  $url=$_SERVER['REQUEST_URI'];
  ?><script type="text/javascript">location.href='login.php?continue=<?= $url ?>';</script><?php
}
require_once('includes/function_inc.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
require_once('connect_hanout.php');
$page_title="Vente";
require_once('includes/header.html');
calculerStock();
?>

<div class="col-md-12">
    <h1 align="center"><?= NOM ?> Vente</h1><br>
    <div class="col-md-12 text-center">
        <?php
        if(isset($_SESSION['client'])){
            echo'<h3 style="color: blue;">Client choisi: '.getClientInfo($_SESSION['client'])['nom']." ".getClientInfo($_SESSION['client'])['prenom'].'</h3>';
        }
        ?>
        <button class="btn btn-default" data-toggle="modal" data-target="#fourModal">Choisir client</button>
        <a href="includes/annuler.php?client"><button class="btn btn-danger">Supprimer Client</button></a>
        <br><br>
        <button type="button" class="btn btn-primary" id="ajouterbtn" onclick="ajouterVente();checkAjouter();" formnovalidate>Ajouter</button><br><br>

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
                            //addToLog($q);
                            $r=mysqli_query($dbc,$q);
                            while($row=mysqli_fetch_assoc($r)){
                                echo'<option value="'.$row['client_id'].'">'.$row['prenom'].' '.$row['nom'].'</option>';
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
    $q="select * from vente_temp where v_id=$vendeur;";
    addToLog($q);
    $r=mysqli_query($dbc,$q);
    $num=mysqli_num_rows($r);
    if($num>0){ ?>
        <table class="table table-hover" style="table-layout:fixed;" id="achatListTable">
            <tr>
                <th>Clé</th>
                <th>Des</th>
                <th>Qte</th>
                <th>PV</th>
                <th>Total</th>
                <th>Supprimer</th>
            </tr>
            <?php
            while($row=mysqli_fetch_assoc($r)){
                $designiation = getArticleInfo($row['cle'])['designiation'];
                if(strstr( mb_strtolower($designiation), 'rouge' )){
                    $bg = "red";
                    $color = "white";
                }elseif(strstr( mb_strtolower($designiation), 'bleu' )){
                    $bg = "blue";
                    $color = "white";
                }elseif(strstr( mb_strtolower($designiation), 'jaune' )){
                    $bg = "yellow";
                    $color="black";
                }elseif(strstr( mb_strtolower($designiation), 'noir' )){
                    $bg = "black";
                    $color = "white";
                }else{
                    $bg = "";
                    $color = "black";
                }
                $sum+=$row['qte']*$row['pv'];
                echo'<tr>';
                echo '<td>'.$row['cle'].'</td>';
                echo '<td style="word-wrap: break-word;background-color:'.$bg.';color:'.$color.';">'.getArticleInfo($row['cle'])['designiation'].'</td>';
                echo '<td>'.$row['qte'].'</td>';
                echo '<td>'.$row['pv'].'</td>';
                echo '<td>'.$row['qte']*$row['pv'].'</td>';
                echo '<td><button data-href="includes/supprimer.php?vente&id='.$row['vt_id'].'" class="btn btn-danger" data-toggle="modal" data-target="#confirm-delete">Supprimer</button></td>
      <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                '.getArticleInfo($row['cle'])['designiation'].'
            </div>
            <div class="modal-body">
                Vous voulez annuler la vente ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                <a class="btn btn-danger btn-ok">Supprimer</a>
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
            <b>Total à payer: <span id="total_a_paye"><?= $sum ?></span></b>
          </div>
          <?php if(isset($_SESSION['client'])){?>
            <br>
            <b style="font-size:20px;">Crédit Précédent: <?= getClientInfo($_SESSION['client'])['credit'] ?>DA</b><br>
          <label class="control-label col-md-6" for="total">Versement:</label>
          <div class="col-md-6">
            <input type="number" class="form-control" id="total" value="<?= $sum ?>" name="total" required style="font-size:30px;">
          </div>
        <?php }else{?>
          <input type="hidden" name="total" id="total" value="<?= $sum ?>">
        <?php }?>
         </div>
        <div class="col-md-12 text-center">
            <button class="btn btn-success" btn-lg data-toggle="modal" data-target="#confirm-valider" onclick="verifierVersement();">Valider tous les ventes</button><br><br>
            <a href="includes/annuler.php?vente"><button type="button" class="btn btn-danger" style="float:right">Annuler tous les ventes</button></a>
            <br>
        </div>

        <div class="modal fade" id="confirm-valider" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        Confirmation
                    </div>
                    <div class="modal-body">
                        Le Total est de <?= number_format($sum,2) ?> <br>
                        Confirmer tous les ventes dans cette fiche?
                    </div>
                    <div class="modal-footer">
                      <span id="confirmation"></span>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>



    <script type="text/javascript">
    function verifierVersement(){
      var total_a_paye=parseFloat(document.getElementById('total_a_paye').innerHTML);
      var total=parseFloat(document.getElementById('total').value);
      if(total_a_paye==total){
        document.getElementById('confirmation').innerHTML='<button class="btn btn-success btn-ok" onclick="validerVenteTS();">Valider Tous</button>';
      }else if(total_a_paye>total){
        document.getElementById('confirmation').innerHTML='<button class="btn btn-primary btn-ok" onclick="validerVenteInfo();">Valider Tous Info</button><button class="btn btn-success btn-ok" onclick="validerVenteTel();">Valider Tous Tél</button>';
      }else{
        document.getElementById('confirmation').innerHTML='Validation Impossible';
      }
    }
    function checkAjouter(){
      // var designiation = document.getElementById('designiation').innerHTML;
      // var ajouterbtn = document.getElementById('ajouterbtn');
      // if(designiation==""){
      //   ajouterbtn.setAttribute("disabled","disabled");
      // }else{
      //   ajouterbtn.removeAttribute("disabled");
      // }
    }
        function ajouterVente(){
            var article = document.getElementById('article').value;
            var qte = parseFloat(document.getElementById('qte').value);
            var qter = parseFloat(document.getElementById('qter').value);
            var designiation = document.getElementById('designiation').innerHTML;
            var pv = document.getElementById('pv').value;
            var pv2 = document.getElementById('pv2').value;
            var pv3 = document.getElementById('pv3').value;
            var prix_achat = parseFloat(document.getElementById('pa').value);

            if(qte>qter){
              alert('Quantité insiffusante');
              return;
            }else if (prix_achat>pv) {
              alert('Prix inférieur au prix d\'achat');
            }else if(designiation==""){
              alert('Article n\'a pas de nom');
              return;
            }else{
            $.ajax({
                url: 'vente2.php',
                type: 'get',
                data: 'article='+article+'&qte='+qte+'&pv='+pv+'&pv2='+pv2+'&pv3='+pv3,
                success: function(output)
                {
                    //alert('Le produit a été ajouté'+output);
                    location.reload();
                }, error: function()
                {
                    alert('something went wrong, rating failed');
                }
            });
            }
        }

        function validerVenteTS(){
            var total = parseInt(document.getElementById('total').value);
            $.ajax({
                url: 'valider.php',
                type: 'get',
                data: 'vente&prio=ts&total='+total,
                success: function(output)
                {
                    window.location.href="facture_vente.php?id="+output;
                }, error: function()
                {
                    alert('something went wrong, rating failed');
                }
            });
        }
    function validerVenteTel(){
        var total = parseInt(document.getElementById('total').value);
        $.ajax({
            url: 'valider.php',
            type: 'get',
            data: 'vente&prio=tel&total='+total,
            success: function(output)
            {
                window.location.href="facture_vente.php?id="+output;
            }, error: function()
            {
                alert('something went wrong, rating failed');
            }
        });
    }

    function validerVenteInfo(){
        var total = parseInt(document.getElementById('total').value);
        $.ajax({
            url: 'valider.php',
            type: 'get',
            data: 'vente&prio=info&total='+total,
            success: function(output)
            {
                window.location.href="facture_vente.php?id="+output;
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script>
            $(document).ready(function() {
                $("#client").select2({
                        placeholder: "Choisir un client",
                        allowClear: true
                 });
            });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/0.0.11/push.min.js"></script>
    <script src="dist/js/iziToast.min.js" type="text/javascript"></script>
