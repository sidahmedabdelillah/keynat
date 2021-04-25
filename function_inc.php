<?php
function addToLog($text){

}
function getFournisseurInfo($id){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
  $q = "SELECT * FROM `fournisseur` WHERE `f_id`= $id limit 1;";
  $r = mysqli_query($dbc,$q);
  $fournisseur = mysqli_fetch_assoc($r);
  return ($fournisseur);
}
function getMaintenanceInfo($id){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
  mysqli_set_charset( $dbc, 'utf8');
  $q = "SELECT * FROM `maintenance` WHERE `m_id`= $id limit 1;";
  $r = mysqli_query($dbc,$q);
  $maintenance = mysqli_fetch_assoc($r);
  return ($maintenance);
}

function getPCNadirInfo($id){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
  mysqli_set_charset( $dbc, 'utf8');
  $q = "SELECT * FROM `pc_nadir` WHERE `pn_id`= $id limit 1;";
  $r = mysqli_query($dbc,$q);
  $pc = mysqli_fetch_assoc($r);
  return ($pc);
}

function getTotalFactureVente($id){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
  mysqli_set_charset( $dbc, 'utf8');
  $q = "SELECT sum(qte_vente*prix_vente) FROM `vente` WHERE `facture_vente_id`=$id";
  $r = mysqli_query($dbc,$q);
  $row = mysqli_fetch_assoc($r);
  return ($row['sum(qte_vente*prix_vente)']);
}

function getFactureInfo($id){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
  mysqli_set_charset( $dbc, 'utf8');
  $q = "SELECT * FROM `facture_vente` WHERE `facture_vente_id`=$id";
  $r = mysqli_query($dbc,$q);
  $row = mysqli_fetch_assoc($r);
  return $row;
}

function getVersem($id){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
  mysqli_set_charset( $dbc, 'utf8');
  $q = "SELECT * FROM `facture_vente` WHERE `facture_vente_id`=$id";
  $r = mysqli_query($dbc,$q);
  $row = mysqli_fetch_assoc($r);
  return $row;
}


function getVersementFacture($id){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
  mysqli_set_charset( $dbc, 'utf8');
  $q = "SELECT * FROM `versement` WHERE `fv_id`=$id";
  $r = mysqli_query($dbc,$q);
  $row = mysqli_fetch_assoc($r);
  return $row;
}

function getClientInfo($id){
    $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
    $q = "SELECT * FROM `client` WHERE `client_id`= $id limit 1;";
    $r = mysqli_query($dbc,$q);
    $clientInfo = mysqli_fetch_assoc($r);
    return ($clientInfo);
}
function getNbrTtPCNadir(){
    $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
    $q = "SELECT * FROM `pc_nadir`";
    $r=mysqli_query($dbc,$q);
    $num=mysqli_num_rows($r);
    return $num;
}
function getNbrPCNadirEnVente(){
    $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
    $q = "SELECT * FROM `pc_nadir` where date_vendu is null AND date_rendu_nadir is null";
    $r=mysqli_query($dbc,$q);
    $num=mysqli_num_rows($r);
    return $num;
}
function getNbrPCNadirVenduNONRendu(){
    $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
    $q = "SELECT * FROM `pc_nadir` where date_vendu is not null AND date_rendu_nadir is null";
    $r=mysqli_query($dbc,$q);
    $num=mysqli_num_rows($r);
    return $num;
}
function getNbrPCNadirVenduRendu(){
    $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
    $q = "SELECT * FROM `pc_nadir` where date_vendu is not null AND date_rendu_nadir is not null";
    $r=mysqli_query($dbc,$q);
    $num=mysqli_num_rows($r);
    return $num;
}
function getNbrPCNadirAnnule(){
    $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
    $q = "SELECT * FROM `pc_nadir` where date_vendu is null AND date_rendu_nadir is not null";
    $r=mysqli_query($dbc,$q);
    $num=mysqli_num_rows($r);
    return $num;
}
function getNbrAnnule(){
    $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
    $q = "SELECT * FROM `maintenance` WHERE `etat`='Annule' AND rendu is null";
    $r=mysqli_query($dbc,$q);
    $num=mysqli_num_rows($r);
    return $num;
}
function getNbrPerteAnnule(){
    $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
    $q = "SELECT * FROM `perte_temp` WHERE `etat`='Annule'";
    $r=mysqli_query($dbc,$q);
    $num=mysqli_num_rows($r);
    return $num;
}
function getNbrPerteValide(){
    $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
    $q = "SELECT * FROM `perte_temp` WHERE `etat`='Validé'";
    $r=mysqli_query($dbc,$q);
    $num=mysqli_num_rows($r);
    return $num;
}
function getNbrPerteTemp(){
    $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
    $q = "SELECT * FROM `perte_temp`";
    $r=mysqli_query($dbc,$q);
    $num=mysqli_num_rows($r);
    return $num;
}
function getNbrPerteEnAttente(){
    $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
    $q = "SELECT * FROM `perte_temp` WHERE `etat`='En Attente'";
    $r=mysqli_query($dbc,$q);
    $num=mysqli_num_rows($r);
    return $num;
}
function getNbrTermine(){
    $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
    $q = "SELECT * FROM `maintenance` WHERE `etat`='Terminé' AND rendu is null";
    $r=mysqli_query($dbc,$q);
    $num=mysqli_num_rows($r);
    return $num;
}
function getNbrRendu(){
    $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
    $q = "SELECT * FROM `maintenance` WHERE `rendu`is not null";
    $r=mysqli_query($dbc,$q);
    $num=mysqli_num_rows($r);
    return $num;
}
function getChargeMois(){
    $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
    mysqli_set_charset( $dbc, 'utf8');
    $q = "SELECT sum(pa*qte) FROM `charge` WHERE month(date)=month(CURRENT_DATE);";
    $r=mysqli_query($dbc,$q);
    $row=mysqli_fetch_assoc($r);
    return $row['sum(pa*qte)'];
}
function getNbrOran(){
    $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
    $q = "SELECT * FROM `maintenance` WHERE `etat`='Oran'";
    $r=mysqli_query($dbc,$q);
    $num=mysqli_num_rows($r);
    return $num;
}
function getNbrMaintenance(){
    $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
    $q = "SELECT * FROM `maintenance`";
    $r=mysqli_query($dbc,$q);
    $num=mysqli_num_rows($r);
    return $num;
}

function getNbrInventaire(){
    $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
    mysqli_set_charset( $dbc, 'utf8');
    $vendeur=$_SESSION['v_id'];
    $q = "SELECT * from inventaire WHERE etat='En Attente' ";
    if($_SESSION['type']<>1){
      $q.="AND responsable=$vendeur";
    }
    $r=mysqli_query($dbc,$q);
    $num=mysqli_num_rows($r);
    return $num;
}
function getNbrMaintenanceAffichage(){
    $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
    $q = "SELECT * FROM `maintenance` WHERE `rendu` is null AND `etat`='En Attente';";
    $r=mysqli_query($dbc,$q);
    $num=mysqli_num_rows($r);
    return $num;
}
function getNbrEnCours(){
    $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
    $q = "SELECT * FROM `maintenance` WHERE `etat`='En Cours'";
    $r=mysqli_query($dbc,$q);
    $num=mysqli_num_rows($r);
    return $num;
}
function getNbrCommandeMain(){
    $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
    $q = "SELECT * FROM `maintenance` WHERE `etat`='Commande' AND rendu is null";
    $r=mysqli_query($dbc,$q);
    $num=mysqli_num_rows($r);
    return $num;
}
function getNbrEnAttente(){
    $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
    $q = "SELECT * FROM `maintenance` WHERE `etat`='En Attente'";
    $r=mysqli_query($dbc,$q);
    $num=mysqli_num_rows($r);
    return $num;
}
function getGainVendeur($id){
    $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
    $q = "SELECT sum(val)*0.125 FROM `gain` WHERE `vendeur`=$id AND month(date)=month(CURRENT_DATE)";
    $r = mysqli_query($dbc,$q);
    $GainVendeur = mysqli_fetch_assoc($r);
    if($GainVendeur['sum(val)*0.125']){
      return ($GainVendeur['sum(val)*0.125']);
    }else{
      return 0;
    }
}
function getPerteVendeur($vendeur){
    $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
    mysqli_set_charset( $dbc, 'utf8');
    $q = "SELECT * FROM `perte` WHERE ";
    if($vendeur==4){
      $q.= "article_id<'2000' ";
    }elseif($vendeur==6){
      $q.= "article_id>'2001' ";
    }
    $q.=" AND month(date)=month(CURRENT_DATE)";
    $r = mysqli_query($dbc,$q);
    $perte_vendeur=0;
    while($row=mysqli_fetch_assoc($r)){
      $article=getArticleInfo($row['article_id']);
      $perte_vendeur+=($article['prix_achat']*$row['qte_perdu']);
      //echo $perte_vendeur;
      //echo '<br>';
    }
    return $perte_vendeur;
}
function getCapitalInfo(){
    $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
    mysqli_set_charset( $dbc, 'utf8');
    $q = "SELECT val+val_stock, day(date),month(date) FROM caisse WHERE `caisse_id` IN ( SELECT min(`caisse_id`) FROM caisse group by date(`date`) );";
    $r = mysqli_query($dbc,$q);
    return ($r);
}

function getCapitalOFDay($id){
    $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
    $q = "SELECT val+val_stock FROM `caisse` WHERE month(date)=month(CURRENT_DATE) AND day(date)='$id' ORDER by date limit 1;";
    $r = mysqli_query($dbc,$q);
    $row=mysqli_fetch_assoc($r);
    return ($row['val+val_stock']);
}

function getCapitalOFMonth($id){
    $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
    $q = "SELECT val+val_stock FROM `caisse` WHERE month(date)='$id' ORDER by date limit 1;";
    $r = mysqli_query($dbc,$q);
    $row=mysqli_fetch_assoc($r);
    return ($row['val+val_stock']);
}

function getFactureVenteInfo($id){
    $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
    $q = "SELECT * FROM `facture_vente` WHERE `facture_vente_id`=$id;";
    $r = mysqli_query($dbc,$q);
    $facture = mysqli_fetch_assoc($r);
    return ($facture);
}
function calculerGainVendeur($id){
    $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
    $q = "SELECT sum(val)*0.125 FROM `gain` WHERE vendeur=$id AND month(date)=month(CURRENT_DATE)";
    $r = mysqli_query($dbc,$q);
    $gain = mysqli_fetch_assoc($r);
    return ($gain['sum(val)*0.125']);
}
function calculerGainAdminParMois(){
    $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
    mysqli_set_charset( $dbc, 'utf8');
    $q = "SELECT sum(val) FROM `gain` WHERE month(`date`)=month(CURRENT_DATE)";
    $r = mysqli_query($dbc,$q);
    $gain = mysqli_fetch_assoc($r);
    $gain=$gain['sum(val)']*0.125;
    return ($gain);
}
function calculerFour1($id){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
  $q="SELECT * FROM `achat` WHERE `article_id`= $id ORDER BY `date` DESC, `prix_achat_fournisseur` limit 0,1";
  $r=mysqli_query($dbc,$q);
  if(mysqli_num_rows($r)>0){
  	$row=mysqli_fetch_assoc($r);
    return($row);
  }
  return FALSE;
}
function calculerFour2($id){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
  $q="SELECT * FROM `achat` WHERE `article_id`= $id ORDER BY `date` DESC, `prix_achat_fournisseur` limit 1,1";
  $r=mysqli_query($dbc,$q);
  if(mysqli_num_rows($r)>0){
  	$row=mysqli_fetch_assoc($r);
    return($row);
  }
  return FALSE;
}
function calculerFour3($id){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
  $q="SELECT * FROM `achat` WHERE `article_id`= $id ORDER BY `date` DESC, `prix_achat_fournisseur` limit 2,1";
  $r=mysqli_query($dbc,$q);
  if(mysqli_num_rows($r)>0){
  	$row=mysqli_fetch_assoc($r);
    return($row);
  }
  return FALSE;
}
function getLastBuyInfo($id,$f){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
  $q="SELECT * FROM `achat` WHERE `article_id`=$id AND `fournisseur_id`=$f ORDER BY `date` limit 1;";
  $r=mysqli_query($dbc,$q);
  $info = mysqli_fetch_assoc($r);
  return ($info);
}
function getArticleInfo($id){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
  $q = "SELECT * FROM `article` WHERE `cle`='$id' OR `code`='$id' limit 1;";
  $r = mysqli_query($dbc,$q);
  $article = mysqli_fetch_assoc($r);
  return ($article);
}

function rendu_fun($article,$qte,$vendeur,$pv){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
  $q2="INSERT INTO `rendu` (`article_id`, `vendeur_id`, `qte_rendu`, `prix_vente`) VALUES ('$article', '$vendeur', '$qte', '$pv');";
  $r2=mysqli_query ($dbc, $q2);
  if($r2){
    //
  }else{
    echo mysqli_error($dbc);
  }
}

function getCaisseInfo(){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
  $q = "SELECT * FROM `caisse` order by `caisse_id` DESC limit 1;";
  $r = mysqli_query($dbc,$q);
  $caisse = mysqli_fetch_assoc($r);
  return ($caisse);
}

function enleverDeCaisse($somme){
  $caisse = getCaisseInfo();
  $val_caisse = $caisse['val'];
  $gain=$caisse['gain'];
  $val_stock=$caisse['val_stock'];
  $val_caisse=$val_caisse-$somme;
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
  mysqli_set_charset( $dbc, 'utf8');
  $q4="INSERT INTO `caisse` (`val`, `gain`, `val_stock`,`gain_net`) VALUES ('$val_caisse', '$gain', '$val_stock', '0');";
  $r4=mysqli_query ($dbc, $q4);
  if($r4){
    //
  }else{
    echo mysqli_error($dbc);

  }
  return ($caisse);
}

function getGainThisMonth($nbr){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
  $q = "SELECT * FROM `caisse` WHERE month(date)=$nbr;";
  $r = mysqli_query($dbc,$q);
  $gainToday= 0;
  while ($caisse = mysqli_fetch_assoc($r)) {
    $gainToday += $caisse['gain_net'];
  };
  return $gainToday;
}

function getGainTotal(){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
  $q = "SELECT sum(val) FROM `gain` WHERE month(date)=month(CURRENT_DATE);";
  $r = mysqli_query($dbc,$q);
  $gainToday = mysqli_fetch_assoc($r);
  return $gainToday['sum(val)'];

}

function getGainAEnlever(){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
  mysqli_set_charset( $dbc, 'utf8');
  $q = "SELECT sum(val) FROM `gain` WHERE month(date)=month(CURRENT_DATE);";
  $r=mysqli_query($dbc,$q);
  $valEnlever=(mysqli_fetch_assoc($r)['sum(val)'])/2;
  $q1="SELECT sum(pe) FROM `caisse_modification` WHERE `gain50`=1 AND month(`datetime`)=month(CURRENT_DATE)";
  $r1=mysqli_query($dbc,$q1);
  $peDejaEnlever=mysqli_fetch_assoc($r1)['sum(pe)'];
  return $valEnlever-$peDejaEnlever;
}

function getGainToday(){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
  $q = "SELECT sum(val) FROM `gain` WHERE month(date)=month(CURRENT_DATE) AND day(date)=day(CURRENT_DATE);";
  $r = mysqli_query($dbc,$q);
  $gainToday = mysqli_fetch_assoc($r);
  return $gainToday['sum(val)'];
}

function getPEJour($day){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
  $q = "SELECT sum(pe) FROM `caisse_modification` WHERE gain50=1 AND month(datetime)=month(CURRENT_DATE) AND day(datetime)=$day";
  $r = mysqli_query($dbc,$q);
  $peJour = mysqli_fetch_assoc($r);
  return $peJour['sum(pe)'];
}

function getGain50($day){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
  $q1="SELECT * FROM `gain` WHERE month(date)=month(CURRENT_DATE) AND day(`date`)=$day";
  $r1=mysqli_query($dbc,$q1);
  if(mysqli_num_rows($r1)>0){
    $q = "SELECT sum(val) FROM `gain` WHERE month(date)=month(CURRENT_DATE) AND day(`date`)<=$day";
    $r = mysqli_query($dbc,$q);
    $peJour = mysqli_fetch_assoc($r);
    return $peJour['sum(val)'];
}else{
  return NULL;
}
}

function getGainOFDay($nbr){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
  $q = "SELECT sum(val) FROM `gain` WHERE month(`date`)=month(CURRENT_DATE) and day(date)=$nbr;";
  $r = mysqli_query($dbc,$q);
  $gainDay = mysqli_fetch_assoc($r);
  return $gainDay['sum(val)'];
}

function getPerteOFDay($nbr){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
  mysqli_set_charset( $dbc, 'utf8');
  $q = "SELECT sum(qte_perdu*pa) FROM `perte` WHERE month(`date`)=month(CURRENT_DATE) and day(date)=$nbr;";
  $r = mysqli_query($dbc,$q);
  $perteDay = mysqli_fetch_assoc($r);
  return $perteDay['sum(qte_perdu*pa)'];
}

function getGainOFWeek($week){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
  $q = "SELECT sum(val) FROM `gain` WHERE week(`date`)=$week;";
  $r = mysqli_query($dbc,$q);
  $gainDay = mysqli_fetch_assoc($r);
  return $gainDay['sum(val)'];
}

function getWilayaInfo($id){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
  $q = "SELECT * FROM `wilaya` WHERE `wil_id`=$id limit 1;";
  $r = mysqli_query($dbc,$q);
  $wilaya = mysqli_fetch_assoc($r);
  return ($wilaya);
}


function getVendeurInfo($id){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
  $q = "SELECT * FROM `vendeur` WHERE `v_id`=$id limit 1;";
  $r = mysqli_query($dbc,$q);
  $vendeur = mysqli_fetch_assoc($r);
  return ($vendeur);
}

function calculerNbrVente($day){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
  $q = "SELECT * FROM `vente` WHERE month(date)=month(CURRENT_DATE) AND day(date)='$day';";
  $r = mysqli_query($dbc,$q);
  $num = mysqli_num_rows($r);
  return ($num);
}

function calculerPerteJour($month){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
  $q = "SELECT sum(qte_perdu*pa) FROM `perte` WHERE month(`date`)=$month";
  $r = mysqli_query($dbc,$q);
  $row=mysqli_fetch_assoc($r);
  return ($row['sum(qte_perdu*pa)']);
}

function calculerNbrVenteSemaine($week){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
  mysqli_set_charset( $dbc, 'utf8');
  $q = "SELECT * FROM `vente` WHERE week(date)=$week";
  $r = mysqli_query($dbc,$q);
  $num = mysqli_num_rows($r);
  return ($num);
}
function calculerNbrVenteMonth($month){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
  $q = "SELECT * FROM `vente` WHERE year(date)=year(CURRENT_DATE) AND month(date)='$month';";
  $r = mysqli_query($dbc,$q);
  $num = mysqli_num_rows($r);
  return ($num);
}

function calculerNbrVenteThisMonth($nbr){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
  $q = "SELECT * FROM `vente` WHERE month(date)='$nbr';";
  $r = mysqli_query($dbc,$q);
  $num = mysqli_num_rows($r);
  return ($num);
}

function calculerStock(){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
mysqli_set_charset( $dbc, 'utf8');
  $q = "SELECT * FROM `article`;";
  $r = mysqli_query($dbc,$q);
  $stock = 0;
  while($article = mysqli_fetch_assoc($r)){
    $stock += $article['prix_achat'] * $article['quantite'];
  }
  $stock+=203000;
  $caisse=getCaisseInfo();
  $val = $caisse['val'];
  $gain = $caisse['gain'];
  $ancien_stock=$caisse['val_stock'];
  if($stock<>$ancien_stock){
    $q2="INSERT INTO `caisse` (`val`, `gain`, `val_stock`) VALUES ($val, $gain, $stock);";
    $r2 = mysqli_query($dbc,$q2);
  }

}

function check_login($nom,$password){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
  $q = "SELECT * FROM `vendeur` WHERE `nom`='$nom' AND `password`=md5('$password') limit 1;";
  $r = mysqli_query($dbc,$q);
  if(mysqli_num_rows($r)>0){
    $vendeur = mysqli_fetch_assoc($r);
    session_start();
    $_SESSION['v_id']=$vendeur['v_id'];
    $_SESSION['nom']=$vendeur['nom'];
    $_SESSION['prenom']=$vendeur['prenom'];
    $_SESSION['adress']=$vendeur['adress'];
    $_SESSION['telephone1']=$vendeur['telephone1'];
    $_SESSION['telephone2']=$vendeur['telephone2'];
    $_SESSION['obser1']=$vendeur['obser1'];
    $_SESSION['obser2']=$vendeur['obser2'];
    $_SESSION['type']=$vendeur['type'];
    if(isset($_GET['continue'])){
      $continue=$_GET['continue'];
      header("location:$continue");
    }else{
      header('location:index.php');
    }
  }else{
    header('location:login.php?error');
  }
}


function calculerRecette($n){
  $dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
  $q="SELECT sum(prix_vente*qte_vente) FROM `vente` where month(`date`)=month(CURRENT_DATE) and day(date)=$n;";
  $r=mysqli_query($dbc,$q);
  $row=mysqli_fetch_assoc($r);
  return ($row['sum(prix_vente*qte_vente)']);
}
function convert_number_to_words($number) {

    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'un',
        2                   => 'deux',
        3                   => 'trois',
        4                   => 'quatre',
        5                   => 'cinq',
        6                   => 'six',
        7                   => 'sept',
        8                   => 'huit',
        9                   => 'neuf',
        10                  => 'dix',
        11                  => 'onze',
        12                  => 'douze',
        13                  => 'treize',
        14                  => 'quatorze',
        15                  => 'quinze',
        16                  => 'seize',
        17                  => 'dix-sept',
        18                  => 'dix-huit',
        19                  => 'dix-neuf',
        20                  => 'vingt',
        30                  => 'trente',
        40                  => 'quarante',
        50                  => 'cinquante',
        60                  => 'soixante',
        70                  => 'soixante-dix',
        80                  => 'quatre-vingts',
        90                  => 'quatre-vingt-dix',
        100                 => 'cent',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'milliard'    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}
 ?>
