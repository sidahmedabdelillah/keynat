<?php
session_start();
require_once('includes/function_inc.php');
require_once('connect_hanout.php');
if(isset($_GET['vente'])){
  $total_paye=(int)$_GET['total'];
  $q="SELECT * FROM vente_temp;";
  $r=mysqli_query($dbc,$q);
  if(isset($_SESSION['client'])){
    $client=$_SESSION['client'];
  }else{
    $client=1;
  }
  $clientInfo=getClientInfo($client);
  $vendeur=$_SESSION['v_id'];
  $caisse = getCaisseInfo();
  $val_caisse = $caisse['val'];
  $val_caisse2=$caisse['val'];
  $gain=$caisse['gain'];
  $val_stock=$caisse['val_stock'];
  $total_a_paye=0;
  $total_a_paye2=0;
  $t=date("Y-m-d H:i:s");
  $q1="INSERT INTO `facture_vente` (`client_id`, `v_id`, `date_vente`, `reglement`) VALUES ('$client', '$vendeur', '$t', 'cache');";
  addToLog($q1);
  $r1=mysqli_query($dbc,$q1);
  if($r1){
    $gain_vente=0;
    $id=mysqli_insert_id($dbc);
    while($value=mysqli_fetch_assoc($r)) {
      if($value['v_id']==$vendeur){
        $cle=$value['cle'];
        $qte=$value['qte'];
        $pv=$value['pv'];
        $qte_anciennce=getArticleInfo($cle)['quantite'];
        $qte_nouvelle=$qte_anciennce-$qte;
        if($cle<10000){
          $total_a_paye=$total_a_paye+($qte*$pv);
        }else{
          $total_a_paye2=$total_a_paye2+($qte*$pv);
        }
        $gain_net=$qte*($pv-getArticleInfo($cle)['prix_achat']);
        $gain_vente+=$gain_net;
        $q2="INSERT INTO `vente` (`facture_vente_id`, `article_id`, `qte_vente`, `prix_vente`, `vendeur_id`) VALUES ('$id', '$cle', '$qte', '$pv', '$vendeur');";
        addToLog($q2);
        $r2=mysqli_query($dbc,$q2);
        if($r2){
          //
        }else{
          echo mysqli_error($dbc);
          exit();
        }
        $q3="UPDATE `article` SET `quantite` = '$qte_nouvelle' WHERE `article`.`cle` = $cle;";
        addToLog($q3);
        $r3=mysqli_query($dbc,$q3);
        if($r3){
          //
        }else{
          echo mysqli_error($dbc);
          exit();
        }


        $q5="INSERT INTO `gain` (`val`, `date`,`vendeur`,`article_id`) VALUES ('$gain_net', CURRENT_TIMESTAMP, $vendeur, $cle);";
        addToLog($q5);
      	$r5=mysqli_query ($dbc, $q5);
      	if($r5){
          //
        }else{
          echo mysqli_error($dbc);
          exit();
        }

        $vendeurInfo=getVendeurInfo($vendeur);
        $gain_net_vendeur=$gain_net*0.125;
        $q6="INSERT INTO `gain_vendeur` (`v_id`, `date`, `val`) VALUES ('$vendeur', CURRENT_TIMESTAMP, '$gain_net_vendeur');";
        addToLog($q6);
        $r6=mysqli_query ($dbc, $q6);
      	if($r6){
          //
        }else{
          echo mysqli_error($dbc);
          exit();
        }
          $q7="SELECT * from inventaire WHERE `article_id`='$cle' limit 1;";
          addToLog($q7);
          $r7=mysqli_query($dbc,$q7);
          if(mysqli_num_rows($r7)==1){
            $q8="UPDATE `inventaire` SET `article_id` = '$cle', `vendeur` = '$vendeur', `responsable` = '$vendeur', `etat` = 'En Attente', `date` = CURRENT_TIMESTAMP WHERE `inventaire`.`article_id` = $cle;";
          }else{
            $q8="INSERT INTO `inventaire` (`article_id`, `vendeur`, `responsable`) VALUES ('$cle', '$vendeur', '$vendeur');";
          }
          addToLog($q8);
          $r8=mysqli_query($dbc,$q8);
          if($r8){
            //
          }else{
            echo mysqli_error($dbc);
          }

      }

    }
    $val_caisse=$val_caisse+$total_paye;
    $credit_client=$clientInfo['credit'];
    $credit_client2=$clientInfo['credit2'];
    $prio=$_GET['prio'];
    if($prio=='tel'){
      if($total_a_paye2>=$total_paye){
        $credit_client2+=$total_paye-$total_a_paye2;
        $val_a_ajouter_caisse2=$total_paye;
        $credit_client-=$total_a_paye;
        $val_a_ajouter_caisse=0;
      }else{
        if($total_a_paye+$total_a_paye2>=$total_paye){
          $val_a_ajouter_caisse2=$total_a_paye2;
          $credit_client+=$total_paye-($total_a_paye+$total_a_paye2);
          $val_a_ajouter_caisse=$total_paye-$total_a_paye2;
        }
      }
    }elseif($prio=='info'){
      if($total_a_paye>=$total_paye){
        $credit_client+=$total_paye-$total_a_paye;
        $val_a_ajouter_caisse=$total_paye;
        $credit_client2-=$total_a_paye2;
        $val_a_ajouter_caisse2=0;
      }else{
        if($total_a_paye2+$total_a_paye>=$total_paye){
          $val_a_ajouter_caisse=$total_a_paye;
          $credit_client2+=$total_paye-($total_a_paye2+$total_a_paye);
          $val_a_ajouter_caisse2=$total_paye-$total_a_paye;
        }
      }
    }else{
      $val_a_ajouter_caisse=$total_a_paye;
      $val_a_ajouter_caisse2=$total_a_paye2;
    }
    $val_caisse = $caisse['val']+$val_a_ajouter_caisse;
    $val_caisse2=$caisse['val2']+$val_a_ajouter_caisse2;
    $val_caisse_gain=$val_caisse+($total_paye-$gain_vente/2);

    newCaisse($val_caisse,$val_caisse2);
    $q5="UPDATE `client` SET `credit` = '$credit_client', `credit2` = '$credit_client2' WHERE `client`.`client_id` = $client;";
    addToLog($q5);
    $r5=mysqli_query($dbc,$q5);
    if($r5){
      //
    }else{
      echo mysqli_error($dbc);
    }
    $q6="INSERT INTO `versement` (`ver_id`, `date`, `v_id`, `c_id`, `val` ,`fv_id`) VALUES (NULL, CURRENT_TIMESTAMP, '$vendeur', '$client', '$total_paye','$id');";
    addToLog($q6);
    $r6=mysqli_query($dbc,$q6);
    if($r6){
      //
    }else{
      echo mysqli_error($dbc);
    }
    $q="DELETE FROM `vente_temp` WHERE `v_id`=$vendeur;";
    addToLog($q);
    $r=mysqli_query($dbc,$q);
    if($r){
      echo $id;
    }else{
      echo mysqli_error($dbc);
    }
    unset($_SESSION['client']);
  }
}elseif (isset($_GET['achat'])) {
  $q="SELECT * FROM achat_temp;";
  addToLog($q);
  $r=mysqli_query($dbc,$q);
  if(isset($_SESSION['fournisseur'])){
    $four=$_SESSION['fournisseur'];
  }else{
    header('location:acheter2.php?pasfournisseur');
  }
  $vendeur=$_SESSION['v_id'];
  $caisse = getCaisseInfo();
  $val_caisse = $caisse['val'];
  $gain=$caisse['gain'];
  $val_stock=$caisse['val_stock'];
  $q1="INSERT INTO `facture_achat` (`fournisseur_id`, `acheteur_id`) VALUES ($four, $vendeur);";
  addToLog($q1);
  $r1=mysqli_query($dbc,$q1);
  if($r1){
    $id=mysqli_insert_id($dbc);
    while($value=mysqli_fetch_assoc($r)) {
      if($value['v_id']==$vendeur){
        $cle=$value['cle'];
        $cle_four = $value['cle_four'];
        $qte=$value['qte'];
        $pa=$value['pa'];
        $pv=$value['pv'];
        $pv2=$value['pv2'];
        $pv3=$value['pv3'];
        $qte_anciennce=getArticleInfo($cle)['quantite'];
        $qte_nouvelle=$qte_anciennce+$qte;
        $val_caisse=$val_caisse-($qte*$pa);
        // if($cle>=6000 and $cle<7000){
        //
        //     for ($i=1;$i<=$qte;$i++){
        //         $n_serie=generateRandomNumber(8);
        //         $q="INSERT INTO `batterie`(`n_serie`, `article_id`) VALUES ('$n_serie','$cle')";
        //         $r=mysqli_query($dbc,$q);
        //     }
        // }
        $q2="INSERT INTO `achat` (`facture_achat_id`, `article_id`, `qte_achat`, `prix_achat_fournisseur`
        , `prix_vente`, `fournisseur_id`) VALUES ($id, $cle, $qte, $pa, $pv, $four);";
        addToLog($q2);
        $r2=mysqli_query($dbc,$q2);
        if($r2){
          //
        }else{
          echo mysqli_error($dbc);
          exit();
        }
        $q3="UPDATE `article` SET `quantite` = '$qte_nouvelle', `prix_achat` = '$pa', `prix_vente` = '$pv'
        , `prix_vente2` = '$pv2', `prix_vente3` = '$pv3', `date_achat` = CURRENT_TIMESTAMP WHERE `article`.`cle` = $cle;";
        addToLog($q3);
        $r3=mysqli_query($dbc,$q3);
        if($r3){
          //
        }else{
          echo mysqli_error($dbc);
          exit();
        }
        newCaisse($val_caisse);
        //calclulerMeilleurFour();


        /**
         * moulay start update 03
         */
        $q9 = "SELECT * FROM `article_four` WHERE `cle_article` = '$cle' AND `id_four` = '$cle'";
        $r9 = mysqli_query($dbc,$q9);
        if(mysqli_num_rows($r9) == 0){
          $q10 = "INSERT INTO `article_four`(`cle_article`, `cle_article_four`, `prix_achat`, `id_four`,`date_achat`) VALUES ('$cle','$cle_four',$pa,'$four',CURRENT_TIMESTAMP)";
          $r10 = mysqli_query($dbc,$q10);
          if(!$r10) echo mysqli_error($dbc);
        }else{
          $cle_art_four = mysqli_fetch_assoc($r9)['cle'];
          $q11 = "UPDATE `article_four` SET `cle_article_four`='$cle_four',`prix_achat`='$pa',`date_achat`= CURRENT_TIMESTAMP WHERE `cle` = '$cle_art_four'";
          $r11 = mysqli_query($dbc,$q11);
          if(!$r11) echo mysqli_error($dbc);
        }

         /**
          * mouly fin update 03
          */

         calclulerMeilleurFour($cle);
         calclulerMeilleurFour_moulay($cle);
        $q7="SELECT * from inventaire WHERE `article_id`='$cle' limit 1;";
        addToLog($q7);
        $r7=mysqli_query($dbc,$q7);
        if(mysqli_num_rows($r7)==1){
          $q8="UPDATE `inventaire` SET `article_id` = '$cle', `vendeur` = '$vendeur', `responsable` = '$vendeur', `etat` = 'En Attente', `date` = CURRENT_TIMESTAMP WHERE `inventaire`.`article_id` = $cle;";
        }else{
          $q8="INSERT INTO `inventaire` (`article_id`, `vendeur`, `responsable`) VALUES ('$cle', '$vendeur', '$vendeur');";
        }
        addToLog($q8);
        $r8=mysqli_query($dbc,$q8);
        if($r8){
          //
        }else{
          echo mysqli_error($dbc);
        }
      }
  }
  }
  $q="DELETE FROM `achat_temp` WHERE `v_id`=$vendeur;";
  addToLog($q);
  $r=mysqli_query($dbc,$q);
  if($r){
    echo $id;
  }else{
    echo mysqli_error($dbc);
  }
  unset($_SESSION['fournisseur']);
}
elseif (isset($_GET['rendu'])){
  $q="SELECT * FROM rendu_temp;";
  addToLog($q);
  $r=mysqli_query($dbc,$q);
  if(isset($_SESSION['client'])){
    $client=$_SESSION['client'];
  }else{
    $client=1;
  }
  $vendeur=$_SESSION['v_id'];
  $q1="INSERT INTO `facture_rendu` (client_id`, `v_id`) VALUES ('$client', '$vendeur');";
  addToLog($q1);
  $r1=mysqli_query($dbc,$q1);
  if($r1){
    $id=mysqli_insert_id($dbc);
    while($value=mysqli_fetch_assoc($r)) {
      $cle=$value['cle'];
      $qte=$value['qte'];
      $pv=$value['pv'];
      $q2="INSERT INTO `rendu` (`facture_achat_id`, `article_id`, `qte_achat`, `prix_achat_fournisseur`, `prix_vente`, `fournisseur_id`) VALUES ($id, $cle, $qte, $pa, $pv, $four);";
      addToLog($q2);
      $r2=mysqli_query($dbc,$q2);
      if($r2){
        //
      }else{
        echo mysqli_error($dbc);
        exit();
      }
      $q3="UPDATE `article` SET `quantite` = '$qte_nouvelle', `prix_achat` = '$pa', `prix_vente` = '$pv', `prix_vente2` = '$pv2', `prix_vente3` = '$pv3' WHERE `article`.`cle` = $cle;";
      addToLog($q3);
      $r3=mysqli_query($dbc,$q3);
      if($r3){
        //
      }else{
        echo mysqli_error($dbc);
        exit();
      }
      newCaisse($val_caisse);

        $q7="SELECT * from inventaire WHERE `article_id`='$cle' limit 1;";
        addToLog($q7);
        $r7=mysqli_query($dbc,$q7);
        if(mysqli_num_rows($r7)==1){
          $q8="UPDATE `inventaire` SET `article_id` = '$cle', `vendeur` = '$vendeur', `responsable` = '$vendeur', `etat` = 'En Attente', `date` = CURRENT_TIMESTAMP WHERE `inventaire`.`article_id` = $cle;";
        }else{
          $q8="INSERT INTO `inventaire` (`article_id`, `vendeur`, `responsable`) VALUES ('$cle', '$vendeur', '$vendeur');";
        }
        addToLog($q8);
        $r8=mysqli_query($dbc,$q8);
        if($r8){
          //
        }else{
          echo mysqli_error($dbc);
        }
    }

  }
  $q="TRUNCATE `rendu_temp`;";

  addToLog($q);
  $r=mysqli_query($dbc,$q);
  if($r){
    header('location:rendu_a.php');
  }else{
    echo mysqli_error($dbc);
    exit();
  }
  unset($_SESSION['client']);
}

 ?>
