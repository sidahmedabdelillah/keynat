<?php
session_start();
require_once('includes/function_inc.php');
if(!isset($_SESSION['prenom'])){
  $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
$page_title="Les Problems à Résoudre";
require_once('includes/header.html');
require_once('connect_hanout.php');
if(isset($_GET['resoudre'])){
  switch ($_GET['resoudre']) {
    case 'sameCodebar':
      codebarEviter();
      supprimerSameCodebar();
      ?>
      <script type="text/javascript">
        window.location="problem.php";
      </script>
      <?php
    case 'quantiteNegative':
      viderArticleNegative();
      ?>
      <script type="text/javascript">
        window.location="problem.php";
      </script>
      <?php
    case 'articleVide':
      viderArticleVide();
      ?>
      <script type="text/javascript">
        window.location="problem.php";
      </script>
      <?php
    case 2:
      echo "i égal 2";
      break;
}
}
?>


<div class="col-md-12">
<h1 align="center">Les Problems à Résoudre</h1>

      <table class="table table-hover" style="table-layout:fixed;" id="articleTable">
        <tr>
          <th>Nom</th>
          <th>Reamrque</th>
          <th>Résolution</th>
        </tr>
        <tr>
          <td>Produit avec le meme codebar</td>
          <td><?= getSameCodebar() ?> codebars</td>
          <td><a href="problem.php?resoudre=sameCodebar" class="btn btn-primary">Résoudre</a></td>
        </tr>
        <tr>
          <td>Produit avec quantité négative</td>
          <td><?= articleQuantiteNegative() ?> articles</td>
          <td><a href="problem.php?resoudre=quantiteNegative" class="btn btn-primary">Résoudre</a></td>
        </tr>
        <tr>
          <td>Article Sans Désigniation</td>
          <td><?= getNbrArticleSansNom() ?> articles</td>
          <td><a href="problem.php?resoudre=articleVide" class="btn btn-primary">Résoudre</a></td>
        </tr>


      </table>

  </div>

<?php
require_once('includes/footer.html');
 ?>
