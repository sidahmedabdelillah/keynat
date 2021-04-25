<script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/0.0.11/push.min.js"></script>
<script src="dist/js/iziToast.min.js" type="text/javascript"></script>

<?php
$parts = explode('/', $_SERVER["SCRIPT_NAME"]);
$file = $parts[count($parts) - 1];
$file= basename($file,'.php');


$array=['facture_achat','facture_vente','facture_pc_nadir','facture_livraison','facture_opgi','facture_route','print_code','print_maintenance','print_pc_nadir','print_pcs_nadir','print_inventaire','commande'];
$array=['vente','index'];
if(in_array($file,$array)){
$vendeur=$_SESSION['v_id'];
$vendeur_nom=$_SESSION['prenom'];

//non conformite en attente
$q="select * from non_confirmite where qui_fait_action=$vendeur and seen=0;";
$r=mysqli_query($dbc,$q);
while($row=mysqli_fetch_assoc($r)){
  ?>
  <script type="text/javascript">
  iziToast.warning({
    title: 'Salut <?= $vendeur_nom ?>',


    message: 'Non Conformité: <?= $row['description'] ?>',
  });
  </script>

<?php
}

//inventaire en attente
$q="select * from inventaire where responsable=$vendeur;";
$r=mysqli_query($dbc,$q);
$num=mysqli_num_rows($r);
if($num>0){
  $row=mysqli_fetch_assoc($r);
    ?>
    <script type="text/javascript">
    iziToast.error({
      title: 'Salut <?= $vendeur_nom ?>',
      message: 'Vous avez <?= '('.$num.')' ?> inventaire en attente'
    });
    </script>
  <?php
}

//maintenance en attente
$q="select * from maintenance where responsable=$vendeur and etat='En Attente';";
$r=mysqli_query($dbc,$q);
$num=mysqli_num_rows($r);
if($num>0){
  $row=mysqli_fetch_assoc($r);
    ?>
    <script type="text/javascript">
    iziToast.error({
      title: 'Salut <?= $vendeur_nom ?>',


      message: 'Vous avez <?= '('.$num.')' ?> maintenance en attente'
    });
    </script>
  <?php
}

//vérification de caisse
$q="SELECT * FROM `caisse_verification` order by id desc limit 1;";
$r=mysqli_query($dbc,$q);
$row=mysqli_fetch_assoc($r);
$date_vérification=$row['date'];
$date_now=date("Y-m-d H:i:s");
$difference=strtotime($date_now)-strtotime($date_vérification);
if($difference > 7200){
    ?>
    <script type="text/javascript">
    iziToast.error({
      title: 'Salut <?= $vendeur_nom ?>',
      position: 'center',

      <?php
      if($difference > 10800){?>
      close : false,

      message: 'ça fait déjà plus que 4 heures, Veuillez Vérifier la caisse'
    <?php }else{?>

      message: 'ça fait déjà plus que 2 heures, Veuillez Vérifier la caisse'
      <?php
    }?>
    });
    </script>
    <?php
}

//vente temporaire en double
$q="SELECT count(*) FROM vente_temp where v_id=$vendeur group by cle having count(*)>1";
$r=mysqli_query($dbc,$q);
$num=mysqli_num_rows($r);
if($num>0){
  $row=mysqli_fetch_assoc($r);
    ?>
    <script type="text/javascript">
    iziToast.warning({
      title: 'Salut <?= $vendeur_nom ?>',
      position: 'center',


      message: 'Vous avez un article en vente plus q\'une fois'
    });
    </script>
  <?php
}

//gain à enlever
if($_SESSION['type']==1){
  $q="SELECT * FROM `caisse_modification` WHERE day(datetime)=day(CURRENT_TIMESTAMP) and month(datetime)=month(CURRENT_TIMESTAMP) and YEAR(datetime)=year(CURRENT_TIMESTAMP) and gain50=1";
  $r=mysqli_query($dbc,$q);
  $num=mysqli_num_rows($r);
  if($num==0){
    $row=mysqli_fetch_assoc($r);
      ?>
      <script type="text/javascript">
      iziToast.warning({
        title: 'Salut <?= $vendeur_nom ?>',
        position: 'center',


        message: 'Vous devez enlever les gains d\'aujoud\'hui'
      });
      </script>
    <?php
  }
}
}?>
