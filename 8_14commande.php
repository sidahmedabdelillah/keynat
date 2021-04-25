<?php
session_start();
if (!isset($_SESSION['prenom'])) {
    $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
$page_title="Les Commandes A faire";
require_once('includes/header.html');
require_once('connect_hanout.php');
calculerStock();
if(isset($_GET['reduite'])){
  $reduit=TRUE;
}
$q1 = "SELECT * from article WHERE (quantite < seuil or quantite < seuil2 or quantite < seuil3 ) 
order by cle";
addToLog($q1);
$r1 = mysqli_query($dbc, $q1);
$num1 = mysqli_num_rows($r1);
$q2 = "SELECT * from article WHERE quantite < seuil2 order by cle";
addToLog($q2);
$r2 = mysqli_query($dbc, $q2);
$num2 = mysqli_num_rows($r2);
$q3 = "SELECT * from article WHERE quantite < seuil3 order by cle";
addToLog($q3);
$r3 = mysqli_query($dbc, $q3);
$num3 = mysqli_num_rows($r3);
$q4="SELECT * FROM `maintenance` WHERE `etat`='Commande' AND `rendu` is null order by `m_id`";
$r4=mysqli_query($dbc,$q4);
$num4 = mysqli_num_rows($r4);
?>


<div class="col-md-12">
    <h1 align="center" class="nottoprint">Les Commandes <a href="commande.php" class="nottoprint"> <button class="btn btn-default">Detaillés</button></a>
    <a href="commande.php?reduite" class="nottoprint"><button class="btn btn-primary">Reduite</button></a></h1>

    <div class="form-actions">
        <button class="btn btn-primary nottoprint" onclick="print();">Imprimer</button>
    </div>
    <br>
    <ul class="nav nav-tabs nav-justified nottoprint">
        <li class="active"><a data-toggle="tab" href="#seuil1">Seuil 1 (<?= $num1 ?>)</a></li>
        <li><a data-toggle="tab" href="#seuil2">Seuil 2 (<?= $num2 ?>)</a></li>
        <li><a data-toggle="tab" href="#seuil3">Seuil 3 (<?= $num3 ?>)</a></li>
        <li><a data-toggle="tab" href="#commandes">Les Commandes des clients (<?= $num4 ?>)</a></li>

    </ul>

    <div class="tab-content">

        <div id="seuil1" class="tab-pane fade in active">

            <table class="table table-hover table-striped" id="articleTable1">
                <tr>
                    <th class="">Cle</th>
                    <th class="col-xs-6">Des</th>
                    <th class="">Qte</th>
                    <th class="">P A</th>
                    <th class="" onclick="sortTable(articleTable1,4)">F 1</th>
                    <th class="">P F 1</th>
                    <!-- <th class="">D 1</th> -->
                    <th class="" onclick="sortTable(articleTable1,7)">F 2</th>
                    <th class="">Prix ?</th>
                    <!-- <th class="">D 2</th> -->
                    <th class="" onclick="sortTable(articleTable1,10)">Seui1</th>
                    <th class="">Seuil2</th>
                    <!-- <th class="">D 3</th> -->
                    <th class="">Seuil 3</th>
                </tr>
                <?php
                while ($articles = mysqli_fetch_assoc($r1)){
                  $designiation = str_replace("eliminer","<em>eliminer</em>",$articles['designiation']);

                  $four1=calculerFour1($articles['cle']);
                  $four1_nom=@getFournisseurInfo($four1['four_id'])['f_nom'];
                  $prix_four1=@getAchatInfo($four1['achat_id'])['prix_achat_fournisseur'];
                  $date_four1=@getAchatInfo($four1['achat_id'])['date'];

                  $four2=calculerFour2($articles['cle']);
                  $four2_nom=@getFournisseurInfo($four2['four_id'])['f_nom'];
                  $prix_four2=@getAchatInfo($four2['achat_id'])['prix_achat_fournisseur'];
                  $date_four2=@getAchatInfo($four2['achat_id'])['date'];

                  $four3=calculerFour3($articles['cle']);
                  $four3_nom=@getFournisseurInfo($four3['four_id'])['f_nom'];
                  $prix_four3=@getAchatInfo($four3['achat_id'])['prix_achat_fournisseur'];
                  $date_four3=@getAchatInfo($four3['achat_id'])['date'];
                ?>
                <tr>
                    <td class=""><?= $articles['cle'] ?></td>
                    <td style="word-wrap: break-word;" class="col-xs-5"><?= substr($designiation,0,50) ?></td>
                    <td class=""><?= $articles['quantite'] ?></td>
                    <td class=""><?= $articles['prix_achat'] ?></td>
                    <td class="">
                        <?= $four1['four_id'] ?>
                    </td>
                    <td class="">
                      <?= $prix_four1 ?>
                    </td>
                    <!-- <td class=" nottoprint">
                      <?= date("d-m", strtotime($date_four1)) ?>
                    </td> -->

                    <td class="">
                        <?= $four2['four_id'] ?>
                    </td>
                    <td class="">
                      <?= $prix_four2 ?>
                    </td>
                    <!-- <td class=" nottoprint">
                      <?= date("d-m", strtotime($date_four2)) ?>
                    </td> -->

                    <!-- <td class=""><?= $four3['four_id'] ?></td> -->
                    <!-- <td class=""><?= $prix_four3 ?></td> -->
                    <!-- <td class=" nottoprint"> -->
                      <!-- <?= date("d-m", strtotime($date_four3)) ?> -->
                    <!-- </td> -->
                   
                    <td style="color:red;" class="col-xs-1"><b><?= $articles['seuil'] ?></b></td>
                
                    <td style="color:red;" class="col-xs-1"><b><?= $articles['seuil2'] ?></b></td>
                
                    <td style="color:red;" class="col-xs-1"><b><?= $articles['seuil3'] ?></b></td>
                
                  </tr>


        </div>
        <?php
        }

        ?>

        </table>

    </div>
    <div id="commandes" class="tab-pane fade in">
      <table class="table table-hover table-striped">
        <tr>
          <th style="width:5%">N°</th>
          <th>Mrq</th>
          <th>Tél</th>
          <th class="nottoprint">Rem</th>
        </tr>

        <?php

        while($row=mysqli_fetch_assoc($r4)){?>
          <tr>
            <td style="width:5%"><?= $row['m_id'] ?></td>
            <td><?= $row['marque'] ?></td>
            <td><?= $row['telephone'] ?></td>
            <td class="nottoprint"><?= $row['remarque'] ?></td>
          </tr><?php
        }
        ?>
      </table>
    </div>
    <div id="seuil2" class="tab-pane fade in">

      <table class="table table-hover table-striped" id="articleTable2">
          <tr>
              <th class="">Cle</th>
              <th class="col-xs-6">Des</th>
              <th class="">Qte</th>
              <th class="">P A</th>
              <th class="" onclick="sortTable(articleTable2,4)">F 1</th>
              <th class="">P F 1</th>
              <!-- <th class="">D 1</th> -->
              <th class="" onclick="sortTable(articleTable2,7)">F 2</th>
              <th class="">P F 2</th>
              <!-- <th class="">D 2</th> -->
              <th class="" onclick="sortTable(articleTable2,10)">F 3</th>
              <th class="">P F 3</th>
              <!-- <th class="">D 3</th> -->
              <th class="">Seuil 1</th>
          </tr>
          <?php
          while ($articles = mysqli_fetch_assoc($r2)){
            $designiation = str_replace("eliminer","<em>eliminer</em>",$articles['designiation']);

            $four1=calculerFour1($articles['cle']);
            $four1_nom=@getFournisseurInfo($four1['four_id'])['f_nom'];
            $prix_four1=@getAchatInfo($four1['achat_id'])['prix_achat_fournisseur'];
            $date_four1=@getAchatInfo($four1['achat_id'])['date'];

            $four2=calculerFour2($articles['cle']);
            $four2_nom=@getFournisseurInfo($four2['four_id'])['f_nom'];
            $prix_four2=@getAchatInfo($four2['achat_id'])['prix_achat_fournisseur'];
            $date_four2=@getAchatInfo($four2['achat_id'])['date'];

            $four3=calculerFour3($articles['cle']);
            $four3_nom=@getFournisseurInfo($four3['four_id'])['f_nom'];
            $prix_four3=@getAchatInfo($four3['achat_id'])['prix_achat_fournisseur'];
            $date_four3=@getAchatInfo($four3['achat_id'])['date'];
          ?>
          <tr>
              <td class=""><?= $articles['cle'] ?></td>
              <td style="word-wrap: break-word;" class="col-xs-5"><?= substr($designiation,0,50) ?></td>
              <td class=""><?= $articles['quantite'] ?></td>
              <td class=""><?= $articles['prix_achat'] ?></td>
              <td class="">
                  <?= $four1['four_id'] ?>
              </td>
              <td class="">
                <?= $prix_four1 ?>
              </td>
              <!-- <td class=" nottoprint">
                <?= date("d-m", strtotime($date_four1)) ?>
              </td> -->

              <td class="">
                  <?= $four2['four_id'] ?>
              </td>
              <td class="">
                <?= $prix_four2 ?>
              </td>
              <!-- <td class=" nottoprint">
                <?= date("d-m", strtotime($date_four2)) ?>
              </td> -->

              <td class="">
                  <?= $four3['four_id'] ?>
              </td>
              <td class="">
                <?= $prix_four3 ?>
              </td>
              <!-- <td class=" nottoprint"> -->
                <!-- <?= date("d-m", strtotime($date_four3)) ?> -->
              <!-- </td> -->
              <td style="color:red;" class="col-xs-1"><b><?= $articles['seuil2'] ?></b></td>
          </tr>


  </div>
  <?php
  }

  ?>

  </table>
    </div>

    <div id="seuil3" class="tab-pane fade in">

      <table class="table table-hover table-striped" id="articleTable3">
          <tr>
              <th class="">Cle</th>
              <th class="col-xs-6">Des</th>
              <th class="">Qte</th>
              <th class="">P A</th>
              <th class="" onclick="sortTable(articleTable3,4)">F 1</th>
              <th class="">P F 1</th>
              <!-- <th class="">D 1</th> -->
              <th class="" onclick="sortTable(articleTable3,7)">F 2</th>
              <th class="">P F 2</th>
              <!-- <th class="">D 2</th> -->
              <th class="" onclick="sortTable(articleTable3,10)">F 3</th>
              <th class="">P F 3</th>
              <!-- <th class="">D 3</th> -->
              <th class="">Seuil 1</th>
          </tr>
          <?php
          while ($articles = mysqli_fetch_assoc($r3)){
            $designiation = str_replace("eliminer","<em>eliminer</em>",$articles['designiation']);

            $four1=calculerFour1($articles['cle']);
            $four1_nom=@getFournisseurInfo($four1['four_id'])['f_nom'];
            $prix_four1=@getAchatInfo($four1['achat_id'])['prix_achat_fournisseur'];
            $date_four1=@getAchatInfo($four1['achat_id'])['date'];

            $four2=calculerFour2($articles['cle']);
            $four2_nom=@getFournisseurInfo($four2['four_id'])['f_nom'];
            $prix_four2=@getAchatInfo($four2['achat_id'])['prix_achat_fournisseur'];
            $date_four2=@getAchatInfo($four2['achat_id'])['date'];

            $four3=calculerFour3($articles['cle']);
            $four3_nom=@getFournisseurInfo($four3['four_id'])['f_nom'];
            $prix_four3=@getAchatInfo($four3['achat_id'])['prix_achat_fournisseur'];
            $date_four3=@getAchatInfo($four3['achat_id'])['date'];
          ?>
          <tr>
              <td class=""><?= $articles['cle'] ?></td>
              <td style="word-wrap: break-word;" class="col-xs-5"><?= substr($designiation,0,50) ?></td>
              <td class=""><?= $articles['quantite'] ?></td>
              <td class=""><?= $articles['prix_achat'] ?></td>
              <td class="">
                  <?= $four1['four_id'] ?>
              </td>
              <td class="">
                <?= $prix_four1 ?>
              </td>
              <!-- <td class=" nottoprint">
                <?= date("d-m", strtotime($date_four1)) ?>
              </td> -->

              <td class="">
                  <?= $four2['four_id'] ?>
              </td>
              <td class="">
                <?= $prix_four2 ?>
              </td>
              <!-- <td class=" nottoprint">
                <?= date("d-m", strtotime($date_four2)) ?>
              </td> -->

              <td class="">
                  <?= $four3['four_id'] ?>
              </td>
              <td class="">
                <?= $prix_four3 ?>
              </td>
              <!-- <td class=" nottoprint"> -->
                <!-- <?= date("d-m", strtotime($date_four3)) ?> -->
              <!-- </td> -->
              <td style="color:red;" class="col-xs-1"><b><?= $articles['seuil3'] ?></b></td>
          </tr>


  </div>
  <?php
  }

  ?>

  </table>
    </div>
</div>

<?php
require_once('includes/footer.html');
?>
<script type="text/javascript">
function sortTable(n) {
var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
table = document.getElementById("articleTable1");
switching = true;
// Set the sorting direction to ascending:
dir = "asc";
/* Make a loop that will continue until
no switching has been done: */
while (switching) {
  // Start by saying: no switching is done:
  switching = false;
  rows = table.getElementsByTagName("TR");
  /* Loop through all table rows (except the
  first, which contains table headers): */
  for (i = 1; i < (rows.length - 1); i++) {
    // Start by saying there should be no switching:
    shouldSwitch = false;
    /* Get the two elements you want to compare,
    one from current row and one from the next: */
    x = rows[i].getElementsByTagName("TD")[n];
    y = rows[i + 1].getElementsByTagName("TD")[n];
    /* Check if the two rows should switch place,
    based on the direction, asc or desc: */
    if (dir == "asc") {
      if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
        // If so, mark as a switch and break the loop:
        shouldSwitch= true;
        break;
      }
    } else if (dir == "desc") {
      if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
        // If so, mark as a switch and break the loop:
        shouldSwitch= true;
        break;
      }
    }
  }
  if (shouldSwitch) {
    /* If a switch has been marked, make the switch
    and mark that a switch has been done: */
    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
    switching = true;
    // Each time a switch is done, increase this count by 1:
    switchcount ++;
  } else {
    /* If no switching has been done AND the direction is "asc",
    set the direction to "desc" and run the while loop again. */
    if (switchcount == 0 && dir == "asc") {
      dir = "desc";
      switching = true;
    }
  }
}
}

</script>
