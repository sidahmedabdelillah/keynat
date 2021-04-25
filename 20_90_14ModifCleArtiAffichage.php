<?php
session_start();
require_once('includes/function_inc.php');
if(!isset($_SESSION['prenom'])){
  $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
$page_title="Historique Vente";

require_once('includes/header.html');

require_once('connect_hanout.php');

// Debut php Ajouter article
if (isset($_POST['submitted'])) {
   $cle = $_POST['cle'];
  $designiation = strtolower($_POST['designiation']);
  $categorie = $_POST['categorie'];
  $q7="SELECT * from article WHERE `cle`='$cle' ;";
  addToLog($q7);
  $r = mysqli_query($dbc, $q7);
  if(mysqli_num_rows($r)>0){
   $q8="UPDATE `article` SET `designiation` = '$designiation', `obser1` = '$categorie' WHERE `article`.`cle` = $cle;";
          }else{
    $q8="INSERT INTO `article`( `cle`,`designiation`) VALUES ('$cle','$designiation');";
                }
                addToLog($q8);
                $r8=mysqli_query($dbc,$q8);
                if($r8){
                  echo "<script>alert('cle article is inserted or updated    ');</script>";
                }else{
                  echo "<script>alert('Article not inserted   ');</script>";
                }
  
}
// fin php ajouter article

?>
<!-- debur artic -->
<br><br><br><br>
  <form class="form-horizontal" method="post" action="20_90_14ModifCleArtiAffichage.php">
      <div class="form-group">  
          <label class="control-label col-md-2" for="cle">cle:</label>
          <div class="col-md-9">
             <input type="text" class="form-control" id="designiation" placeholder="cle" name="cle" >
          </div>
      </div>
      <div class="form-group">
            <label class="control-label col-md-2" for="designiation">Designiation:</label>
          <div class="col-md-9">
            <input type="text" class="form-control" id="designiation" placeholder="Designiation" name="designiation">
          </div>
      </div>
      <div class="form-group">
            <label class="control-label col-md-2" for="categorie">Categorie:</label>
          <div class="col-md-9">
              <input type="text" class="form-control" id="categorie" placeholder="categorie" name="categorie">
          </div>
      </div>
     
     
      <div class="form-group">
          <div class="col-sm-offset-2 col-md-9">
            <input type="hidden" name="submitted" value="TRUE">
            <button type="submit" class="btn btn-default">Ajouter Article</button>
          </div>
      </div>
  </form>
<!-- fin articlw -->
<!-- debut filtre date  -->
<div class="col-md-12">
        <?php
        if(isset($_GET['startdate']) AND isset($_GET['finishtdate'])){
         $startdate=$_GET['startdate'];
        $finishdate=$_GET['finishtdate'];
        $q = "SELECT * from article where cle BETWEEN '$startdate' AND '$finishdate' ";
        addToLog($q);
      }
       ?>
    <div class="col-md-12">
                 <br>
          <form action="20_90_14ModifCleArtiAffichage.php" method="get">
            <div class="col-md-5 form-group">
              <input type="text" name="startdate"  class="form-control">
            </div>
            <div class="col-md-5 form-group">
                <input type="text" name="finishtdate"  class="form-control">
            </div>
            <div class="col-md-2 form-group">
                <button type="submit" class="btn btn-primary btn-block">Filter</button>
            </div>
          </form>
    </div>
            <br><br>
      <table class="table table-hover" style="table-layout:fixed;" id="articleTable">
        <tr>
          <th>cle</th>
          <th>Designiation</th>
          <th>Quantité</th>
          <th>categorie</th>
        </tr>
            <?php
            $r = mysqli_query($dbc,$q);
            $summ=0;
            while($historique = mysqli_fetch_assoc($r)){
              ?>
        <tr>
          <td><?= $historique['cle'] ?></td>
          <td style="word-wrap: break-word;"><?= str_replace("eliminer","<em>eliminer</em>",$historique['designiation']) ?></td>
          <td><?= $historique['quantite'] ?></td>
          <td><?= $historique['obser1'] ?></td>
        </tr>

        <?php
      }
       ?>

      </table>
</div>
<!-- fin filtr -->
<?php
require_once('includes/footer.html');
 ?>
