<?php
session_start();
if (!isset($_SESSION['prenom'])) {
    $url = $_SERVER['REQUEST_URI'];
  ?><script type="text/javascript">location.href='login.php?continue=<?= $url ?>';</script><?php
}
$page_title = 'Vente';
require_once('includes/function_inc.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
require_once('connect_hanout.php');
require_once('includes/header.html');
calculerStock();
?>


<div class="col-md-12">
    <h1 align="center"><?= NOM ?> Vente</h1><br>

    <div class="col-md-9">
        <form>
            <div class="input-group">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Recherche par Désignation" id="searchBar"
                           onkeyup="getArticles();">
                </div>

                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Recherche par Désignation" id="searchBar2"
                           onkeyup="getArticles();">
                </div>

                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Recherche par Désignation" id="searchBar3"
                           onkeyup="getArticles();">
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
    <div class="col-md-3">
        <form>
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Code" name="searchBarCode" id="searchBarCode"
                       onkeyup="getArticles();" autofocus>
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
    <div id='articleTableDiv'>
        <table class="table table-hover" style="table-layout:fixed;" id="articleTable">
            <tr>
                <th onclick="sortTable(0);">Clé</th>
            </tr>
            <?php
            $q="select * from article";
            $r=mysqli_query($dbc,$q);
            $array=[];
            while($row=mysqli_fetch_assoc($r)){
              $array[]=$row['cle'];
            }
            for ($i=1; $i < 12000; $i++) {
              if(!in_array($i,$array)){
                ?>
                <tr>
                    <td><?= $i ?></td>
                </tr>

            <?php
          }else{
            ?>
            <tr>
                <td>Rempli</td>
            </tr>

        <?php
          }
            }
                ?>
    </div>



    </table>
</div>
<?php include('includes/footer.html'); ?>
