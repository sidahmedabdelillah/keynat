<?php
session_start();
require_once('includes/function_inc.php');
if(!isset($_SESSION['prenom'])){
  $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
$page_title="Codebar à Eviter";
require_once('includes/header.html');
require_once('connect_hanout.php');
?>


<div class="col-md-12">


      <table class="table table-hover" style="table-layout:fixed;" id="articleTable">
        <tr>
          <th>Codebar</th>
        </tr>
            <?php
            $q="select * from codebar_eviter";
            $r = mysqli_query($dbc,$q);
            while($codebar = mysqli_fetch_assoc($r)){
              ?>
        <tr>
          <td><?= $codebar['val'] ?></td>
        </tr>

        <?php
      }

       ?>

      </table>

  </div>

<?php
require_once('includes/footer.html');
 ?>
