<?php
session_start();
if (!isset($_SESSION['prenom'])) {
    $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
require_once('includes/header.html');
require_once('connect_hanout.php');
calculerStock();
$q1 = "SELECT * from article where `designiation` !='' order by cle";
addToLog($q1);
$r1 = mysqli_query($dbc, $q1);
$num1 = mysqli_num_rows($r1);
?>
<div class="col-md-12">
    <h1 align="center">Les Meilleurs Fournisseurs</h1>
    <br>
<div class="col-md-8">
  <form>
    <div class="input-group">
      <div class="col-md-4">
        <input type="text" class="form-control" placeholder="Recherche par Désignation" id="searchBar" onkeydown="filterTable()">
      </div>

      <div class="col-md-4">
        <input type="text" class="form-control" placeholder="Recherche par Désignation" id="searchBar2" onkeydown="filterTable()">
      </div>

      <div class="col-md-4">
        <input type="text" class="form-control" placeholder="Recherche par Désignation" id="searchBar3" onkeydown="filterTable()">
      </div>

      <div class="input-group-btn">
        <button class="btn btn-default" type="submit">
          <i class="glyphicon glyphicon-search"></i>
          <button class="btn btn-default" type="button" onclick="resetBtn();">Reset</button>
        </button>
      </div>

    </div>
  </form>
</div>
<div class="col-md-4">
  <form>
    <div class="input-group">
      <input type="text" class="form-control" placeholder="Recherche par Code" id="searchBarCode" onkeydown="filterTableCode()">
      <div class="input-group-btn">
        <button class="btn btn-default" type="submit">
          <i class="glyphicon glyphicon-search"></i>
            <button class="btn btn-default" type="button" onclick="resetBtn();">Reset</button>
        </button>
      </div>
    </div>

  </form>
</div>

<br><br><br>



            <table class="table table-hover" id="articleTable">
                <tr>
                    <th>Clé</th>
                    <th class="desrow">Désigniation</th>
                    <th>Qte</th>
                    <th>P A</th>
                    <th>F 1</th>
                    <th class="col-md-1">P F 1</th>
                    <th>F 2</th>
                    <th class="col-md-1">P F 2</th>
                    <th>F 3</th>
                    <th class="col-md-1">P F 3</th>
                </tr>
                <?php
                while ($articles = mysqli_fetch_assoc($r1)){
                  $four1=@calculerFour1($articles['cle']);
                  $four1_nom=@getFournisseurInfo($four1['four_id'])['f_nom'];
                  $prix_four1=@getAchatInfo($four1['achat_id'])['prix_achat_fournisseur'];

                  $four2=calculerFour2($articles['cle']);
                  $four2_nom=@getFournisseurInfo($four2['four_id'])['f_nom'];
                  $prix_four2=@getAchatInfo($four2['achat_id'])['prix_achat_fournisseur'];

                  $four3=calculerFour3($articles['cle']);
                  $four3_nom=@getFournisseurInfo($four3['four_id'])['f_nom'];
                  $prix_four3=@getAchatInfo($four3['achat_id'])['prix_achat_fournisseur'];
                ?>
                <tr>
                    <td><?= $articles['cle'] ?></td>
                    <td style="word-wrap: break-word;" class="desrow"><?= $articles['designiation'] ?></td>
                    <td><?= $articles['quantite'] ?></td>
                    <td><?= $articles['prix_achat'] ?></td>
                    <td>
                        <?php
                          echo @$four1_nom;
                          ?>
                    </td>
                    <td class="col-md-1">
                      <?php
                      echo @$prix_four1;
                       ?>
                    </td>
                    <td>
                      <?php
                        echo @$four2_nom;
                        ?>
                    </td>
                    <td class="col-md-1">
                      <?php
                        echo @$prix_four2;
                       ?>
                    </td>
                    <td>
                      <?php
                        echo @$four3_nom;
                        ?>
                    </td>
                    <td class="col-md-1">
                      <?php
                        echo @$prix_four3;
                       ?>
                    </td>
                </tr>


        </div>
        <?php
        }

        ?>

        </table>
</div>

<?php
require_once('includes/footer.html');
?>
