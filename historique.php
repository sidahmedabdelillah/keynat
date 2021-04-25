<?php
session_start();
if(!isset($_SESSION['prenom'])){
  $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
require_once('includes/header.html');
require_once('connect_hanout.php');
?>


<div class="col-md-12">
<h1 align="center"><?= NOM ?> - Historique des ventes/achats</h1><br>

  <ul class="nav nav-tabs nav-justified">
    <li class="active"><a data-toggle="tab" href="#vente">Vente</a></li>
    <li><a data-toggle="tab" href="#achat">Achat</a></li>
    <li><a data-toggle="tab" href="#perte">Perte</a></li>
    <li><a data-toggle="tab" href="#rendu">Rendu</a></li>

  </ul>

  <div class="tab-content">
    <div id="vente" class="tab-pane fade in active">
      <br>
      <form>
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Recherche par Code" id="searchBarCodeVente" onkeydown="filterTableCodeHistorique('venteTable','searchBarCodeVente')">
          <div class="input-group-btn">
            <button class="btn btn-default" type="button" onclick="resetBtn();">Reset</button>
          </div>
        </div>

      </form>
      <br>
      <table class="table table-hover" style="table-layout:fixed;" id="venteTable">
        <tr>
          <th>Numéro article</th>
          <th>Article</th>
          <th>Quantité</th>
          <th>Restant</th>
          <th>Prix Vente</th>
          <th>Total</th>
          <th>Gain</th>
          <th>Date de vente</th>
          <th>Vendeur</th>
        </tr>
            <?php
            $q = "SELECT * from vente order by date DESC";
            $r = mysqli_query($dbc,$q);
            while($historique = mysqli_fetch_assoc($r)){
              ?>
        <tr>
          <td><?= $historique['article_id'] ?></td>
          <td style="word-wrap: break-word;"><?= getArticleInfo($historique['article_id'])['designiation'] ?></td>
          <td><?= $historique['qte_vente'] ?></td>
          <td><?= getArticleInfo($historique['article_id'])['quantite'] ?></td>
          <td><?= $historique['prix_vente'] ?></td>
          <td><?= $historique['qte_vente']*$historique['prix_vente'] ?></td>
          <td> <?= ($historique['prix_vente'] -getArticleInfo($historique['article_id'])['prix_achat'])*$historique['qte_vente'] ?></td>
          <td><?= $historique['date'] ?></td>
          <td><?= getVendeurInfo($historique['vendeur_id'])['prenom'] ?></td>
        </tr>

        <?php
      }

       ?>

      </table>
    </div>


    <div id="achat" class="tab-pane fade">
      <br>
      <form>
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Recherche par Code" id="searchBarCodeAchat" onkeydown="filterTableCodeHistorique('achatTable','searchBarCodeAchat')">
          <div class="input-group-btn">
            <button class="btn btn-default" type="button" onclick="resetBtn();">Reset</button>
          </div>
        </div>

      </form>
      <br>
      <table class="table table-hover" style="table-layout:fixed;" id="achatTable">
        <tr>
          <th>Numéro de Article</th>
          <th>Article</th>
          <th>Quantité</th>
          <th>Prix Achat</th>
          <th>Fournisseur</th>
          <th>Date de achat</th>
        </tr>
            <?php
              $q = "SELECT * from achat order by date DESC";
              $r = mysqli_query($dbc,$q);
              while($historique = mysqli_fetch_assoc($r)){
            ?>
        <tr>
          <td><?= $historique['article_id'] ?></td>
          <td style="word-wrap: break-word;"><?= getArticleInfo($historique['article_id'])['designiation'] ?></td>
          <td><?= $historique['qte_achat'] ?></td>
          <td><?= $historique['prix_achat_fournisseur'] ?></td>
          <td><?php if($historique['fournisseur_id']){ echo getFournisseurInfo($historique['fournisseur_id'])['f_nom'];} ?></td>
          <td><?= $historique['date'] ?></td>
        </tr>

        <?php
      }

       ?>

      </table>
    </div>

    <div id="perte" class="tab-pane fade">
      <br>
      <form>
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Recherche par Code" id="searchBarCodePerte" onkeydown="filterTableCodeHistorique('perteTable','searchBarCodePerte')">
          <div class="input-group-btn">
            <button class="btn btn-default" type="button" onclick="resetBtn();">Reset</button>
          </div>
        </div>

      </form>
      <br>

      <table class="table table-hover" style="table-layout:fixed;" id="perteTable">
        <tr>
          <th>Numéro Article</th>
          <th>Article</th>
          <th>Prix Achat</th>
          <th>Prix Vente</th>
          <th>Quantité</th>
          <th>Date de perte</th>
          <th>Vendeur</th>
        </tr>
            <?php
              $q = "SELECT * from perte order by date DESC";
              $r = mysqli_query($dbc,$q);
              while($historique = mysqli_fetch_assoc($r)){
            ?>
        <tr>
          <td><?= $historique['article_id'] ?></td>
          <td style="word-wrap: break-word;"><?= getArticleInfo($historique['article_id'])['designiation'] ?></td>
          <td><?= getArticleInfo($historique['article_id'])['prix_achat'] ?></td>
          <td><?= getArticleInfo($historique['article_id'])['prix_vente'] ?></td>
          <td><?= $historique['qte_perdu'] ?></td>
          <td><?= $historique['date'] ?></td>
          <td><?= getVendeurInfo($historique['vendeur_id'])['prenom'] ?></td>
        </tr>

        <?php
      }

       ?>

      </table>
    </div>


    <div id="rendu" class="tab-pane fade">
      <br>
      <form>
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Recherche par Code" id="searchBarCodeRendu" onkeydown="filterTableCodeHistorique('renduTable','searchBarCodeRendu')">
          <div class="input-group-btn">
            <button class="btn btn-default" type="button" onclick="resetBtn();">Reset</button>
          </div>
        </div>

      </form>
      <br>
      <table class="table table-hover" style="table-layout:fixed;" id="renduTable">
        <tr>
          <th>Numéro Article</th>
          <th>Article</th>
          <th>Prix Vente</th>
          <th>Quantité</th>
          <th>Date de rendu</th>
          <th>Vendeur</th>
        </tr>
            <?php
              $q = "SELECT * from rendu order by date DESC";
              $r = mysqli_query($dbc,$q);
              while($historique = mysqli_fetch_assoc($r)){
            ?>
        <tr>
          <td><?= $historique['article_id'] ?></td>
          <td style="word-wrap: break-word;"><?= getArticleInfo($historique['article_id'])['designiation'] ?></td>
          <td><?= getArticleInfo($historique['article_id'])['prix_vente'] ?></td>
          <td><?= $historique['qte_rendu'] ?></td>
          <td><?= $historique['date'] ?></td>
          <td><?= getVendeurInfo($historique['vendeur_id'])['prenom'] ?></td>
        </tr>

        <?php
      }

       ?>

      </table>
    </div>
  </div>
<script>
function filterTableCodeHistorique(table,search) {
  // Declare variables
  var input, filter, table, tr, td, i;
  input = document.getElementById(search);
  filter = input.value.toUpperCase();
  table = document.getElementById(table);
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) == 0) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
</script>
<?php
require_once('includes/footer.html');
 ?>
