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
$q1 = "SELECT * from article WHERE quantite < seuil order by cle";
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
                    <th class="">P F 2</th>
                    <!-- <th class="">D 2</th> -->
                    <th class="" onclick="sortTable(articleTable1,10)">F 3</th>
                    <th class="">P F 3</th>
                    <!-- <th class="">D 3</th> -->
                    <th class="">Seuil 1</th>
                </tr>
                <?php
                while ($articles = mysqli_fetch_assoc($r1)){
                  $designiation = str_replace("eliminer","<em>eliminer</em>",$articles['designiation']);
                  $cle=$articles['cle'];

                  $four1=calculerFour1($articles['cle']);
                  $f_id=$four1['four_id'];
                  $q="INSERT INTO `seuil` (`s_n`, `cle`, `f_id`, `f_n`) VALUES ('1', '$cle', '$f_id', '1');";
                  $r=mysqli_query($dbc,$q);
                  $four1_nom=@getFournisseurInfo($four1['four_id'])['f_nom'];
                  $prix_four1=@getAchatInfo($four1['achat_id'])['prix_achat_fournisseur'];
                  $date_four1=@getAchatInfo($four1['achat_id'])['date'];

                  $four2=calculerFour2($articles['cle']);
                  $f_id=$four2['four_id'];
                  $q="INSERT INTO `seuil` (`s_n`, `cle`, `f_id`, `f_n`) VALUES ('1', '$cle', '$f_id', '2');";
                  $r=mysqli_query($dbc,$q);
                  $four2_nom=@getFournisseurInfo($four2['four_id'])['f_nom'];
                  $prix_four2=@getAchatInfo($four2['achat_id'])['prix_achat_fournisseur'];
                  $date_four2=@getAchatInfo($four2['achat_id'])['date'];

                  $four3=calculerFour3($articles['cle']);
                  $f_id=$four3['four_id'];
                  $q="INSERT INTO `seuil` (`s_n`, `cle`, `f_id`, `f_n`) VALUES ('1', '$cle', '$f_id', '3');";
                  $r=mysqli_query($dbc,$q);
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
                    <td style="color:red;" class="col-xs-1"><b><?= $articles['seuil'] ?></b></td>
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
              <th class="" onclick="sortTable(articleTable2,7)">F 2</th>
              <th class="">P F 2</th>
              <th class="" onclick="sortTable(articleTable2,10)">F 3</th>
              <th class="">P F 3</th>
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
              <td class="">
                  <?= $four2['four_id'] ?>
              </td>
              <td class="">
                <?= $prix_four2 ?>
              </td>
              <td class="">
                  <?= $four3['four_id'] ?>
              </td>
              <td class="">
                <?= $prix_four3 ?>
              </td>
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
              <th class="">F 1</th>
              <th class="">P F 1</th>
              <th class="">F 2</th>
              <th class="">P F 2</th>
              <th class="">F 3</th>
              <th class="">P F 3</th>
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
              <td class="">
                  <?= $four2['four_id'] ?>
              </td>
              <td class="">
                <?= $prix_four2 ?>
              </td>
              <td class="">
                  <?= $four3['four_id'] ?>
              </td>
              <td class="">
                <?= $prix_four3 ?>
              </td>
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
