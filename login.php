<?php
$page_title="Se Connecter";
require_once('includes/function_inc.php');
require_once('includes/header.html');
require_once('connect_hanout.php');
if(isset($_POST['nom'])AND(isset($_POST['password']))){
    check_login($_POST['nom'],$_POST['password']);
}
?>

<div class="col-md-10 col-md-offset-1" style="margin-top:200px;">

    <form method="post"
    <?php
    if(isset($_GET['continue'])){
      $continue=$_GET['continue'];
      echo 'action="login.php?continue='.$continue.'"';
    }else{?>
    action="login.php"
  <?php }?>
    >
  <div class="form-group">
    <label for="name">Votre Nom:</label>
    <input type="text" name="nom" class="form-control" id="name" autofocus>
  </div>
  <div class="form-group">
    <label for="password">Votre Mot de passe:</label>
    <input type="password" name="password" class="form-control" id="password">
  </div>

  <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
</form>

</div>
<?php
require_once('includes/footer.html');
 ?>
