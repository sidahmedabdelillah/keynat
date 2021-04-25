<?php
require_once('includes/function_inc.php');
require_once('connect_hanout.php');
@session_start();
$id = $_GET['id'];


/**
 * update moulay start 01
 */
$id_four = getFournisseurInfo($_SESSION['fournisseur'])['f_id'];

$q = "SELECT  `cle_article` FROM `article_four` WHERE `cle_article_four` = '$id' AND `id_four` = '$id_four'";
$r = mysqli_query($dbc,$q);
if (mysqli_num_rows($r) > 0)  {
  $cle_four = mysqli_fetch_assoc($r)['cle_article'];
}else {
  $cle_four = "";
}
/**
 * update moulay fin 02
 */
?>

<h3>Information</h3>


Cle Fournisseur: <input type="text" id="clefour" value="<?= $cle_four ?>" class="form-control">
<br><br>
