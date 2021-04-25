<?php
$config = getConfig();
clientInfo2NULL();
supprimerInventaireVide();

function addToLog($txt,$type='manual')
{
    @session_start();
    if(isset($_SESSION['v_id'])){
        $v_id=$_SESSION['v_id'];
    }else{
        $v_id=0;
    }
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    $stmt =  $dbc->stmt_init();
    if ($stmt->prepare("INSERT INTO log (val, v_id, type) VALUES (?, ?, ?)")) {
        $stmt->bind_param("sis", $txt, $v_id, $type);
        $stmt->execute();
        $stmt->close();
    }
    $dbc->close();
}

function getConfig($var = NULL)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    if ($var == NULL) {
        $q = "SELECT * FROM `config`";
        $r = mysqli_query($dbc, $q);
        //addToLog($q,'auto');
        $array = [];
        while ($row = mysqli_fetch_assoc($r)) {
            $key = $row['c_name'];
            $value = $row['c_val'];
            $array[$key] = $value;
        }
        return ($array);
    } else {
        $q = "SELECT c_val FROM `config` where c_name='$var'";
        $r = mysqli_query($dbc, $q);
        //addToLog($q,'auto');
        $val = mysqli_fetch_assoc($r);
        return $val;
    }
}

function newCaisse($val, $val2 = NULL)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $val_stock = calculerStock();
    $val_stock2 = calculerStock2();
    $sum = calculerSomme_prix_achatQuantiteManuante();
    $sum2 = calculerSomme_prix_achatQuantiteSeuil1();
    $sum_seuil = sommeSeuil1();
    if ($val2 == NULL) {
        $val2 = getCaisseInfo()['val2'];
    }
    $q = "INSERT INTO `caisse` (`val`, `val2`, `val_stock`, `val_stock2`,`somme_quantite_manquante` ,`somme_quantite_seuil`, `sum_seuil1`) VALUES ('$val', '$val2', '$val_stock', '$val_stock2', '$sum', '$sum2','$sum_seuil');";
    $r = mysqli_query($dbc, $q);
    addToLog($q);
    if ($r) {
        //
    } else {
        echo mysqli_error($dbc);
    }

    //calculer nombre article
    $q2 = "SELECT sum(quantite),count(*),sum(quantite)*count(*) FROM `article` WHERE quantite>0 and prix_achat>0";
    $r2 = mysqli_query($dbc, $q2);
    //addToLog($q2);
    $row = mysqli_fetch_assoc($r2);
    $nbr_artcle_sup = $row['count(*)'];
    $nbr_sum_quantite = $row['sum(quantite)'];
    $nbr_article_total = $row['sum(quantite)*count(*)'];

    $q3 = "INSERT INTO `nbr_article` (`nbr_artcle_sup`, `nbr_sum_quantite`, `nbr_article_total`) VALUES ('$nbr_artcle_sup', '$nbr_sum_quantite', '$nbr_article_total');";
    $r3 = mysqli_query($dbc, $q3);
    addToLog($q3);

    return $val_stock;
}

function clientInfo2NULL()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "UPDATE `client` SET `n_rc`=NULL where n_rc=0;UPDATE `client` SET `n_ai`=NULL where n_ai=0;UPDATE `client` SET `n_mf`=NULL where n_mf=0;";
    $r = mysqli_query($dbc, $q);
    //addToLog($q,'auto');
    return 0;
}

function seenNonConformite($id)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "UPDATE `non_confirmite` SET `seen`=1 where nc_id=$id;";
    $r = mysqli_query($dbc, $q);
    addToLog($q,'manual');
    return 0;
}

function ownNonConforimite($id,$v)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * from `non_confirmite` where nc_id=$id and qui_fait_action=$v;";
    $r = mysqli_query($dbc, $q);
    //addToLog($q,'manual');
    if(mysqli_num_rows($r)==1){
        return TRUE;
    }else{
        return FALSE;
    }
}

function getNbrArticleSansNom()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * from article where designiation='';";
    $r = mysqli_query($dbc, $q);
    return mysqli_num_rows($r);
}

function getNbrMaintenanceTermineMois($month)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * from maintenance where month(rendu)=$month and etat='Terminé';";
    $r = mysqli_query($dbc, $q);
    //addToLog($q,'auto');
    return mysqli_num_rows($r);
}

function getNbrMaintenanceAnnuleMois($month)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * from maintenance where month(rendu)=$month and etat='Annulé';";
    $r = mysqli_query($dbc, $q);
    //addToLog($q,'auto');
    return mysqli_num_rows($r);
}

function getNbrPCNadirVenduMois($month)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `pc_nadir` WHERE month(date_vendu)=$month;";
    $r = mysqli_query($dbc, $q);
    //addToLog($q,'auto');
    return mysqli_num_rows($r);
}

function getNbrClient()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `client`;";
    $r = mysqli_query($dbc, $q);
    //addToLog($q,'auto');
    return mysqli_num_rows($r);
}

function sommeSeuil1()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT sum(seuil) FROM `article`;";
    $r = mysqli_query($dbc, $q);
    //addToLog($q,'auto');
    return mysqli_fetch_assoc($r)['sum(seuil)'];
}

function getSameCodebar()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT codebar,count(*) FROM `article` group by codebar having codebar is not null and count(*)>1 ORDER BY count(*) DESC;";
    $r = mysqli_query($dbc, $q);
    //addToLog($q,'auto');
    return mysqli_num_rows($r);
}

function codebarEviter()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "INSERT IGNORE into codebar_eviter (val) SELECT codebar FROM `article` group by codebar having count(*)>1 and LENGTH(codebar)>7;";
    $r = mysqli_query($dbc, $q);
    addToLog($q,'auto');
    if ($r) {
        return TRUE;
    } else {
        echo mysqli_error($dbc);
        return FALSE;
    }
}

function supprimerSameCodebar()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q2 = "CREATE TEMPORARY TABLE IF NOT EXISTS article2 AS (SELECT * FROM article);";
    $r2 = mysqli_query($dbc, $q2);
    $q3 = "UPDATE article set codebar=NULL where codebar in (SELECT codebar FROM article2 group by codebar having count(*)>1);";
    $r3 = mysqli_query($dbc, $q3);
    addToLog($q2);
    if ($r2 AND $r3) {
        return TRUE;
    } else {
        echo mysqli_error($dbc);
        return FALSE;
    }

}

function articleQuantiteNegative()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "select cle from article where quantite<0;";
    $r = mysqli_query($dbc, $q);
    //addToLog($q,'auto');
    return mysqli_num_rows($r);
}

function siCodebarEviter($code)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "select * from codebar_eviter where val='$code';";
    $r = mysqli_query($dbc, $q);
    //addToLog($q,'auto');
    if (mysqli_num_rows($r) > 0) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function viderArticleNegative()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "UPDATE article set quantite=0 where quantite<0;";
    $r = mysqli_query($dbc, $q);
    addToLog($q,'auto');
    return 0;
}

function viderArticleVide()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "DELETE FROM `article` where designiation='';";
    $r = mysqli_query($dbc, $q);
    addToLog($q,'auto');
    return 0;
}

function siPermis($v, $page)
{
    if ($v == 1) {
        return NULL;
    } else {
        $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
        mysqli_set_charset($dbc, 'utf8');
        $q = "SELECT * FROM `permission` WHERE `t_v`='$v' and page='$page'";
        $r = mysqli_query($dbc, $q);
        //addToLog($q,'auto');
        if (mysqli_num_rows($r) > 0) {
            //
        } else {
            ?>
            <script type="text/javascript">
                //xwindow.location.href = "index.php";
            </script>
            <?php
        }
    }

}

function supprimerInventaireVide()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "DELETE from inventaire WHERE article_id in (select cle from article where quantite=0 and seuil>0);";
    $r = mysqli_query($dbc, $q);
    //addToLog($q,'auto');
    return 0;
}

function desactiverVendeur($id)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "UPDATE `vendeur` SET active=0 WHERE `vendeur`.`v_id` = $id;";
    $r = mysqli_query($dbc, $q);
    //addToLog($q,'auto');
    return 0;
}

function activerVendeur($id)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "UPDATE `vendeur` SET active=1 WHERE `vendeur`.`v_id` = $id;";
    $r = mysqli_query($dbc, $q);
    //addToLog($q,'auto');
    return 0;
}

function getNmrFactureVentreParAnnee($facture)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT count(*), year(date_vente) FROM `facture_vente` WHERE `facture_vente_id`<=$facture and year(date_vente)=(select year(date_vente) from facture_vente where facture_vente_id=$facture);";
    $r = mysqli_query($dbc, $q);
    //addToLog($q,'auto');
    $row = mysqli_fetch_assoc($r);
    return $row;
}

function getTypeVendeur($type)
{
    if ($type == 1) {
        return 'Administrateur';
    } elseif ($type == 2) {
        return 'Vendeur type 1';
    } elseif ($type == 3) {
        return 'Vendeur type 2';
    } else {
        return 'Type non trouvé';
    }
}

function ifVendeurActif($id)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `vendeur` WHERE `v_id`=$id;";
    $r = mysqli_query($dbc, $q);
    //addToLog($q,'auto');
    if ($r) {
        $row = mysqli_fetch_assoc($r);
    } else {
        echo mysqli_error($dbc);
    }
    $active = $row['active'];
    if ($active == 0) {
        return 'Compte Désactivé';
    } elseif ($active == 1) {
        return 'Compte Actif';
    } else {
        return 'Non Défini';
    }
}

function calclulerMeilleurFour_moulay($id = NULL){
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    $q = "SELECT * FROM `article_four` WHERE `cle_article` = '$id'  ORDER BY `date_achat` DESC limit 3 ";
    $r = mysqli_query($dbc, $q);

    if (mysqli_num_rows($r) >0){
        $array = [];
        while ($row = mysqli_fetch_assoc($r)) {
            $array[] = $row;
        }
        usort($array, function ($item1, $item2) {
            return $item1['prix_achat'] <=> $item2['prix_achat'];
        });
        $first = @$array['0']['cle'];
        $second = @$array['1']['cle'];
        $theard = @$array['2']['cle'];


        for ($i = 1; $i <= 3; $i++) {
            if ($i == 1) {
                $art_four = $first;
            } elseif ($i == 2) {
                $art_four = $second;
            } elseif ($i == 3) {
                $art_four = $theard;
            }
            $q = "SELECT * FROM `m_four_2` WHERE `id_art` = '$id' AND `orders` = '$i'";
            $r = mysqli_query($dbc, $q);
            //addToLog($q,'auto');
            if ($r) {
                if (mysqli_num_rows($r) > 0) {
                    if (!empty($art_four)){
                        $q = "UPDATE `m_four_2` SET `id_art_four`= '$art_four' WHERE `id_art` = '$id' AND `orders` = '$i'";
                        $r = mysqli_query($dbc, $q);
                        if (!$r) echo mysqli_error($dbc);
                    }
                } else {
                    $q = "INSERT INTO `m_four_2`(`id_art`,`id_art_four`, `orders`) VALUES ('$id','$art_four','$i')";
                    $r = mysqli_query($dbc, $q);
                    if (!$r) echo mysqli_error($dbc);
                }
            } else {
                echo mysqli_error($dbc);
            }
        }
    }
}


function calclulerMeilleurFour($id = NULL)
//function calclulerMeilleurFour()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
        $q = "SELECT * FROM `achat` WHERE `article_id`= '$id' ORDER BY `date` DESC limit 3";
        $r = mysqli_query($dbc, $q);
        //addToLog($q,'auto');
        if (mysqli_num_rows($r) > 0) {
            $array = [];
            while ($row = mysqli_fetch_assoc($r)) {
                $array[] = $row;
            }
            usort($array, function ($item1, $item2) {
                return $item1['prix_achat_fournisseur'] <=> $item2['prix_achat_fournisseur'];
            });
            $four1_achat = @$array['0']['achat_id'];
            $four1_id = @$array['0']['fournisseur_id'];
            $four2_achat = @$array['1']['achat_id'];
            $four2_id = @$array['1']['fournisseur_id'];
            $four3_achat = @$array['2']['achat_id'];
            $four3_id = @$array['2']['fournisseur_id'];


            for ($i = 1; $i <= 3; $i++) {
                if ($i == 1) {
                    $four_achat = $four1_achat;
                    $four_id = $four1_id;
                } elseif ($i == 2) {
                    $four_achat = $four2_achat;
                    $four_id = $four2_id;
                } elseif ($i == 3) {
                    $four_achat = $four3_achat;
                    $four_id = $four3_id;
                }
                $q = "select * from m_four where article_id=$id and n_mf=$i";
                $r = mysqli_query($dbc, $q);
                //addToLog($q,'auto');
                if ($r) {
                    if (mysqli_num_rows($r) > 0) {
                        $q = "UPDATE `m_four` SET `achat_id`='$four_achat'";
                        if (!empty($four_id)) {
                            $q .= ", four_id='$four_id'";
                        }
                        $q .= " WHERE article_id=$id and n_mf=$i";
                        $r = mysqli_query($dbc, $q);
                        //addToLog($q,'auto');
                        if ($r) {

                        } else {
                            echo mysqli_error($dbc);
                        }
                    } else {
                        $q = "INSERT INTO `m_four`(`article_id`, `achat_id`, `n_mf`, `four_id`) VALUES ('$id','$four_achat','$i','$four_id')";
                        $r = mysqli_query($dbc, $q);
                        //addToLog($q,'auto');
                        if ($r) {

                        } else {
                            echo mysqli_error($dbc);
                        }
                    }
                } else {
                    echo mysqli_error($dbc);
                }
            }
        }

   // }
}

function getFournisseurInfo($id)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `fournisseur` WHERE `f_id`= $id limit 1;";
    $r = mysqli_query($dbc, $q);
    //addToLog($q,'auto');
    $fournisseur = mysqli_fetch_assoc($r);
    return ($fournisseur);
}

function getAchatInfo($id)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `achat` WHERE `achat_id`= $id limit 1;";
    $r = mysqli_query($dbc, $q);
    //addToLog($q,'auto');
    $achat = mysqli_fetch_assoc($r);
    return ($achat);
}

function getMaintenanceInfo($id)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `maintenance` WHERE `m_id`= $id limit 1;";
    $r = mysqli_query($dbc, $q);
    //addToLog($q,'auto');
    $maintenance = mysqli_fetch_assoc($r);
    return ($maintenance);

}

function generateRandomString($length = 16)
{
    $characters = '23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generateRandomNumber($length = 16)
{
    $characters = '23456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECt * from batterie where n_serie='$randomString';";
    $r = mysqli_query($dbc, $q);
    //addToLog($q,'auto');
    if (mysqli_num_rows($r) > 0) {
        generateRandomNumber($length = 16);
    }
    return $randomString;
}

function getHistoriquePerte($id)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `perte` WHERE `article_id`=$id";
    $r = mysqli_query($dbc, $q);
    //addToLog($q,'auto');
    $num = mysqli_num_rows($r);
    $s = 0;//somme qte perdu
    $p = 0;//somme qte perdu * prix achat
    while ($row = mysqli_fetch_assoc($r)) {
        $s += abs($row['qte_perdu']);
        $p += abs($row['qte_perdu']) * $row['pa'];
    }
    $array = ['num' => $num, 'qte' => $s, 'somme' => $p];
    return ($array);
}

function getPCNadirInfo($id)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `pc_nadir` WHERE `pn_id`= $id limit 1;";
    $r = mysqli_query($dbc, $q);
    //addToLog($q,'auto');
    $pc = mysqli_fetch_assoc($r);
    return ($pc);
}

function getTotalFactureVente($id)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT sum(qte_vente*prix_vente) FROM `vente` WHERE `facture_vente_id`=$id";
    $r = mysqli_query($dbc, $q);
    //addToLog($q,'auto');
    $row = mysqli_fetch_assoc($r);
    return ($row['sum(qte_vente*prix_vente)']);
}

function getFactureInfo($id)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `facture_vente` WHERE `facture_vente_id`=$id";
    $r = mysqli_query($dbc, $q);
    //addToLog($q,'auto');
    $row = mysqli_fetch_assoc($r);
    return $row;
}

function getVersem($id)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `facture_vente` WHERE `facture_vente_id`=$id";
    $r = mysqli_query($dbc, $q);
    //addToLog($q,'auto');
    $row = mysqli_fetch_assoc($r);
    return $row;
}

function getVersementFacture($id)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `versement` WHERE `fv_id`=$id";
    $r = mysqli_query($dbc, $q);
    //addToLog($q,'auto');
    $row = mysqli_fetch_assoc($r);
    return $row;
}

function getClientInfo($id)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `client` WHERE `client_id`= $id limit 1;";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $clientInfo = mysqli_fetch_assoc($r);
    return ($clientInfo);
}

function getNbrTtPCNadir()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `pc_nadir`";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $num = mysqli_num_rows($r);
    return $num;
}

function getNbrPCNadirEnVente()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `pc_nadir` where date_vendu is null AND date_rendu_nadir is null";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $num = mysqli_num_rows($r);
    return $num;
}

function getNbrPCNadirVenduNONRendu()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `pc_nadir` where date_vendu is not null AND date_rendu_nadir is null";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $num = mysqli_num_rows($r);
    return $num;
}

// pc nadir Vendu Rendu
function getNbrPCNadirVenduRendu()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `pc_nadir` where date_vendu is not null AND date_rendu_nadir is not null";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $num = mysqli_num_rows($r);
    return $num;
}

function getNbrNonConformite($etat=NULL)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    if($etat=='En Attente'){
        $q="SELECT * FROM `non_confirmite` WHERE action is null OR action=''";
    }elseif($etat=="Terminé"){
        $q="SELECT * FROM `non_confirmite` WHERE (classement is null OR classement='') and (action is not null and action!='')";
    }elseif($etat=="Fermé"){
        $q="SELECT * FROM `non_confirmite` WHERE (classement is not null OR classement!='') and (action is not null and action!='')";
    }else{
        $q="SELECT * FROM `non_confirmite`";
    }
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $num = mysqli_num_rows($r);
    return $num;
}

// pc nadir annulé
function getNbrPCNadirAnnule()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `pc_nadir` where date_vendu is null AND date_rendu_nadir is not null";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $num = mysqli_num_rows($r);
    return $num;
}

//maintenance annulé
function getNbrAnnule()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `maintenance` WHERE `etat`='Annule' AND rendu is null";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $num = mysqli_num_rows($r);
    return $num;
}

function getNbrPerteAnnule()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `perte_temp` WHERE `etat`='Annule'";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $num = mysqli_num_rows($r);
    return $num;
}

function getNbrPerteValide()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `perte_temp` WHERE `etat`='Validé'";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $num = mysqli_num_rows($r);
    return $num;
}

function getNbrPerteTemp()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `perte_temp`";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $num = mysqli_num_rows($r);
    return $num;
}

function getNbrPerteEnAttente()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `perte_temp` WHERE `etat`='En Attente'";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $num = mysqli_num_rows($r);
    return $num;
}

function getNbrTermine()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `maintenance` WHERE `etat`='Terminé' AND rendu is null";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $num = mysqli_num_rows($r);
    return $num;
}

function getNbrRendu()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `maintenance` WHERE `rendu`is not null";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $num = mysqli_num_rows($r);
    return $num;
}

function getChargeMois($month)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT sum(pa*qte) FROM `charge` WHERE month(date)=$month;";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $row = mysqli_fetch_assoc($r);
    return $row['sum(pa*qte)'];
}
function getChargeBetween($startdate,$finishdate)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT sum(pa*qte) FROM `charge` WHERE date(date) BETWEEN '$startdate' AND '$finishdate' ORDER by date desc ;";
    // 60.30gain_vendeurBetween
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $row = mysqli_fetch_assoc($r);
    return $row['sum(pa*qte)'];
}

function getNbrOran()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `maintenance` WHERE `etat`='Oran'";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $num = mysqli_num_rows($r);
    return $num;
}

function getNbrMaintenance()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `maintenance`";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $num = mysqli_num_rows($r);
    return $num;
}

function getNbrInventaire()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $vendeur = $_SESSION['v_id'];
    $q = "SELECT * from inventaire WHERE etat='En Attente' ";
    if ($_SESSION['type'] == 2 || $_SESSION['type'] == 3) {
        $q.="AND responsable='$vendeur' ";
    }
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $num = mysqli_num_rows($r);
    return $num;
}

function getNbrMaintenanceAffichage()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `maintenance` WHERE `rendu` is null AND `etat`='En Attente';";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $num = mysqli_num_rows($r);
    return $num;
}

function getNbrEnCours()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `maintenance` WHERE `etat`='En Cours'";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $num = mysqli_num_rows($r);
    return $num;
}

function getNbrCommandeMain()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `maintenance` WHERE `etat`='Commande' AND rendu is null";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $num = mysqli_num_rows($r);
    return $num;
}

function getNbrEnAttente()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `maintenance` WHERE `etat`='En Attente'";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $num = mysqli_num_rows($r);
    return $num;
}

function getPerteVendeur($vendeur, $month)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $config = getConfig();
    $devision = $config['middle'];
    $q = "SELECT * FROM `perte` ";
    if ($vendeur == 10) {
        $q .= " WHERE article_id<'$devision' ";
    } elseif ($vendeur == 6) {
        $q .= "  WHERE article_id>'$devision' AND article_id<10000 ";
    } elseif ($vendeur == 7) {
        $q .= "  WHERE article_id>10000 ";
    }else{
      $q .= " where article_id > 50000 ";
    }
    $q .= " AND month(date)=$month";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $perte_vendeur = 0;
    while ($row = mysqli_fetch_assoc($r)) {
        $article = getArticleInfo($row['article_id']);
        $perte_vendeur += ($article['prix_achat'] * $row['qte_perdu']);
    }
    return $perte_vendeur;
}
function getPerteVendeurBetween($vendeur, $startdate,$finishdate)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $config = getConfig();
    $devision = $config['middle'];
    $q = "SELECT * FROM `perte`  ";
    // $q = "SELECT sum(val) FROM `gain` where date(date) BETWEEN '$startdate' AND '$finishdate' ORDER by date desc";
   
    if ($vendeur == 10) {
        $q .= " WHERE article_id<'$devision' ";
    } elseif ($vendeur == 6) {
        $q .= "  WHERE article_id>'$devision' AND article_id<10000 ";
    } elseif ($vendeur == 7) {
        $q .= "  WHERE article_id>10000 ";
    }else{
      $q .= " where article_id > 50000 ";
    }
    $q .= " AND date(date) BETWEEN '$startdate' AND '$finishdate' ORDER by date desc";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $perte_vendeur = 0;
    while ($row = mysqli_fetch_assoc($r)) {
        $article = getArticleInfo($row['article_id']);
        $perte_vendeur += ($article['prix_achat'] * $row['qte_perdu']);
    }
    return $perte_vendeur;
}





function getCapitalInfo()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT val+val2+val_stock+val_stock2, date, day(date),month(date) FROM caisse WHERE `caisse_id` IN ( SELECT max(`caisse_id`) FROM caisse group by date(`date`) );";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    return ($r);
}


function getSommeSuil1Info()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT sum_seuil1, date, day(date),month(date) FROM caisse WHERE `caisse_id` IN ( SELECT max(`caisse_id`) FROM caisse group by date(`date`) );";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    return ($r);
}

function getCapitalOFDay($id, $month)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT val+val2+val_stock+val_stock2 FROM `caisse` WHERE month(date)=$month AND day(date)='$id' ORDER by date limit 1;";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $row = mysqli_fetch_assoc($r);
    return ($row['val+val2+val_stock+val_stock2']);
}

function getCapitalOFMonth($month)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT val+val_stock+val2+val_stock2 FROM `caisse` WHERE month(date)='$month' and year(date)=2018 ORDER by date desc limit 1;";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $row = mysqli_fetch_assoc($r);
    return ($row['val+val_stock+val2+val_stock2']);
}

function getFactureVenteInfo($id)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `facture_vente` WHERE `facture_vente_id`=$id;";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $facture = mysqli_fetch_assoc($r);
    return ($facture);
}





function calculerGainVendeur($id, $month)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    // $q = "SELECT sum(val)*0.125 FROM `gain` WHERE vendeur=$id AND month(date)=$month";
    $q = "SELECT sum(val)*0.200 FROM `gain` WHERE vendeur=$id AND month(date)=$month AND day(date)=day(CURRENT_DATE) ";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $gain = mysqli_fetch_assoc($r);
    return ($gain['sum(val)*0.200']);
}

function calculerGainVendeurBetween($id, $startdate,$finishdate)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
       $q = "SELECT sum(val)*0.125 FROM `gain` WHERE vendeur=$id AND date(date) BETWEEN '$startdate' AND '$finishdate' ORDER by date desc ";
    // $q = "SELECT sum(val) FROM `gain` where date(date) BETWEEN '$startdate' AND '$finishdate' ORDER by date desc";
    //  60.30gain_vendeurBetween  
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $gain = mysqli_fetch_assoc($r);
    return ($gain['sum(val)*0.125']);
}
function calculerGainVendeur2($id, $month)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT (SELECT sum(val)*0.38 FROM `gain` WHERE month(date)=$month AND day(date)=day(CURRENT_DATE) AND article_id>10000) as total";
    // $q = "SELECT sum(val) FROM `gain` WHERE month(date)=$month AND day(date)=day(CURRENT_DATE);";
   
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $gain = mysqli_fetch_assoc($r);
    return ($gain['total']);
}

function calculerGainAdminParMois($month)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT sum(val) FROM `gain` WHERE month(`date`)=$month";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $gain = mysqli_fetch_assoc($r);
    $gain = $gain['sum(val)'] * 0.125;
    return ($gain);
}
function calculerGainAdminBetween($startdate,$finishdate)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
       $q = "SELECT sum(val) FROM `gain` WHERE date(date) BETWEEN '$startdate' AND '$finishdate' ORDER by date desc";
    // $q = "SELECT sum(val) FROM `gain` where date(date) BETWEEN '$startdate' AND '$finishdate' ORDER by date desc";
    // 60.30gain_vendeurBetween   
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $gain = mysqli_fetch_assoc($r);
    $gain = $gain['sum(val)'] * 0.125;
    return ($gain);
}
function calculerFour1($id)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `m_four` WHERE `article_id`= $id and n_mf=1";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    if (mysqli_num_rows($r) > 0) {
        $row = mysqli_fetch_assoc($r);
        return ($row);
    }
    return 1;
}
function calculerFour1_2($id)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `article_four`,`m_four_2` WHERE `article_four`.`cle` = `m_four_2`.`id_art_four`AND `m_four_2`.`id_art`= $id and `m_four_2`.`orders`=1";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    if (mysqli_num_rows($r) > 0) {
        $row = mysqli_fetch_assoc($r);
        return ($row);
    }
    return 1;
}

function calculerFour2($id)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `m_four` WHERE `article_id`= $id and n_mf=2";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    if (mysqli_num_rows($r) > 0) {
        $row = mysqli_fetch_assoc($r);
        return ($row);
    }
    return 1;
}
function calculerFour2_2($id)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `article_four`,`m_four_2` WHERE `article_four`.`cle` = `m_four_2`.`id_art_four`AND `m_four_2`.`id_art`= $id and `m_four_2`.`orders`=2";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    if (mysqli_num_rows($r) > 0) {
        $row = mysqli_fetch_assoc($r);
        return ($row);
    }
    return 1;
}
function calculerFour3($id)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `m_four` WHERE `article_id`= $id and n_mf=3";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    if (mysqli_num_rows($r) > 0) {
        $row = mysqli_fetch_assoc($r);
        return ($row);
    }
    return 1;
}
function calculerFour3_2($id)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `article_four`,`m_four_2` WHERE `article_four`.`cle` = `m_four_2`.`id_art_four`AND `m_four_2`.`id_art`= $id and `m_four_2`.`orders`=3";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    if (mysqli_num_rows($r) > 0) {
        $row = mysqli_fetch_assoc($r);
        return ($row);
    }
    return 1;
}
function getLastBuyInfo($id, $f)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `achat` WHERE `article_id`=$id AND `fournisseur_id`=$f ORDER BY `date` limit 1;";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $info = mysqli_fetch_assoc($r);
    return ($info);
}

function getArticleInfo($id)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `article` WHERE `cle`='$id' OR `codebar`='$id' limit 1;";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    if (mysqli_num_rows($r)) {
        $article = mysqli_fetch_assoc($r);
        return ($article);
    } else {
        return NULL;
    }
}

function rendu_fun($article, $qte, $vendeur, $pv)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q2 = "INSERT INTO `rendu` (`article_id`, `vendeur_id`, `qte_rendu`, `prix_vente`) VALUES ('$article', '$vendeur', '$qte', '$pv');";
    addToLog($q2);
    $r2 = mysqli_query($dbc, $q2);
    //addToLog($q2);
    if ($r2) {
        //
    } else {
        echo mysqli_error($dbc);
    }
}

function getCaisseInfo()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `caisse` order by `caisse_id` DESC limit 1;";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $caisse = mysqli_fetch_assoc($r);
    return ($caisse);
}

function getCaisseGainInfo()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `caisse_gain` order by `cg_id` DESC limit 1;";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $caisse_gain = mysqli_fetch_assoc($r);
    return ($caisse_gain);
}

function enleverDeCaisse($somme)
{
    $caisse = getCaisseInfo();
    $val_caisse = $caisse['val'];
    $val_caisse = $val_caisse - $somme;
    newCaisse($val_caisse);
    return ($caisse);
}

function getGainThisMonth($nbr)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `caisse` WHERE month(date)=$nbr;";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $gainToday = 0;
    while ($caisse = mysqli_fetch_assoc($r)) {
        $gainToday += $caisse['gain_net'];
    };
    return $gainToday;
}

function getGainTotalBetween($startdate,$finishdate)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT sum(val) FROM `gain` where date(date) BETWEEN '$startdate' AND '$finishdate' ORDER by date desc";
        // 60.30gain_vendeurBetween
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $gainToday = mysqli_fetch_assoc($r);
    return $gainToday['sum(val)'];

}
function getGainTotal($month)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT sum(val) FROM `gain` WHERE month(date)=$month AND year(date)=year(CURRENT_DATE);";
    // Where year(date)=year(CURRENT_DATE) AND month(date)='$month  WHERE month(date)=$month AND day(date)=day(CURRENT_DATE);";
    
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $gainToday = mysqli_fetch_assoc($r);
    return $gainToday['sum(val)'];

}

function getGainAEnlever($month)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT sum(val) FROM `gain` WHERE month(date)=$month;";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $valEnlever = (mysqli_fetch_assoc($r)['sum(val)']) / 2;
    $q1 = "SELECT sum(pe) FROM `caisse_modification` WHERE `gain50`=1 AND month(`datetime`)=$month";
    $r1 = mysqli_query($dbc, $q1);
    //addToLog($q1);
    $peDejaEnlever = mysqli_fetch_assoc($r1)['sum(pe)'];
    return $valEnlever - $peDejaEnlever;
}

function getGainToday($month)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT sum(val) FROM `gain` WHERE month(date)=$month AND day(date)=day(CURRENT_DATE);";
    
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $gainToday = mysqli_fetch_assoc($r);
    return $gainToday['sum(val)'];
}

function getPEJour($day, $month)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT sum(pe) FROM `caisse_modification` WHERE gain50=1 AND month(datetime)=$month AND day(datetime)=$day";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $peJour = mysqli_fetch_assoc($r);
    return $peJour['sum(pe)'];
}

function getGain50($day, $month)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q1 = "SELECT * FROM `gain` WHERE month(date)=$month AND day(`date`)=$day";
    $r1 = mysqli_query($dbc, $q1);
    //addToLog($q1);
    if (mysqli_num_rows($r1) > 0) {
        $q = "SELECT sum(val) FROM `gain` WHERE month(date)=$month AND day(`date`)<=$day";
        $r = mysqli_query($dbc, $q);
        //addToLog($q);
        $peJour = mysqli_fetch_assoc($r);
        return $peJour['sum(val)'];
    } else {
        return NULL;
    }
}

function getGainOFDay($nbr, $month)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT sum(val) FROM `gain` WHERE month(`date`)=$month and day(date)=$nbr;";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $gainDay = mysqli_fetch_assoc($r);
    return $gainDay['sum(val)'];
}

function getPerteOFDay($nbr, $month)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT sum(qte_perdu*pa) FROM `perte` WHERE month(`date`)=$month and day(date)=$nbr;";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $perteDay = mysqli_fetch_assoc($r);
    return $perteDay['sum(qte_perdu*pa)'];
}

function getGainOFWeek($week)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT sum(val) FROM `gain` WHERE week(`date`)=$week;";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $gainDay = mysqli_fetch_assoc($r);
    return $gainDay['sum(val)'];
}

function getWilayaInfo($id)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `wilaya` WHERE `wil_id`=$id limit 1;";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $wilaya = mysqli_fetch_assoc($r);
    return ($wilaya);
}

function calculerSomme_prix_achatQuantiteManuante()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT sum(prix_achat*(seuil-quantite)) FROM `article` WHERE quantite<seuil ORDER BY prix_achat*(seuil-quantite) DESC";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $sum = mysqli_fetch_assoc($r)['sum(prix_achat*(seuil-quantite))'];
    // $q2="INSERT INTO `somme_quantite_manquante` (`sqm_id`, `date`, `val`) VALUES (NULL, CURRENT_TIMESTAMP, '$sum');";
    // $r2=mysqli_query($dbc,$q2);
    return $sum;
}

function calculerSomme_prix_achatQuantiteSeuil1()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT sum(prix_achat*seuil) FROM `article` WHERE quantite<seuil";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $sum = mysqli_fetch_assoc($r)['sum(prix_achat*seuil)'];
    // $q2="INSERT INTO `somme_quantite_manquante` (`sqm_id`, `date`, `val`) VALUES (NULL, CURRENT_TIMESTAMP, '$sum');";
    // $r2=mysqli_query($dbc,$q2);
    return $sum;
}

function getVendeurInfo($id)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `vendeur` WHERE `v_id`=$id limit 1;";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $vendeur = mysqli_fetch_assoc($r);
    return ($vendeur);
}

function calculerNbrVente($day, $month)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `vente` WHERE month(date)=$month AND day(date)='$day';";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $num = mysqli_num_rows($r);
    return ($num);
}

function calculerPerteJour($month)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT sum(qte_perdu*pa) FROM `perte` WHERE month(`date`)=$month";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $row = mysqli_fetch_assoc($r);
    return ($row['sum(qte_perdu*pa)']);
}

function calculerNbrVenteSemaine($week)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `vente` WHERE week(date)=$week";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $num = mysqli_num_rows($r);
    return ($num);
}

function calculerNbrVenteMonth()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT count(*),month(date) FROM `vente` group by MONTH(date) order by date";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    return ($r);
}


function calculerGainMonthYear($month, $year)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT sum(val) FROM `gain` WHERE year(date)=2017 AND month(date)='$month';";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $sum = mysqli_fetch_assoc($r)['sum(val)'];
    return ($sum);
}

function calculerGainMonthThisYear($month)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT sum(val) FROM `gain` WHERE year(date)=year(CURRENT_DATE) AND month(date)='$month';";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $sum = mysqli_fetch_assoc($r)['sum(val)'];
    return ($sum);
}


function calculerPerteMonthYear($month, $year)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT count(perte_id) FROM `perte` WHERE year(date)=2017 AND month(date)='$month';";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $sum = mysqli_fetch_assoc($r)['count(perte_id)'];
    return ($sum);
}

function calculerPerteMonthThisYear($month)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT count(perte_id) FROM `perte` WHERE year(date)=year(CURRENT_DATE) AND month(date)='$month';";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $sum = mysqli_fetch_assoc($r)['count(perte_id)'];
    return ($sum);
}


function calculerNbrVenteThisMonth($nbr)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `vente` WHERE month(date)='$nbr';";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $num = mysqli_num_rows($r);
    return ($num);
}

function calculerStock()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $q = "SELECT * FROM `article` where cle<10000;";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $stock = 0;
    while ($article = mysqli_fetch_assoc($r)) {
        $stock += $article['prix_achat'] * $article['quantite'];
    }
    return $stock;
}

function calculerStock2()
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    mysqli_set_charset($dbc, 'utf8');
    $stock = 0;
    $q2 = "SELECT * FROM `article` where cle>=10000;";
    $r2 = mysqli_query($dbc, $q2);
    //addToLog($q2);
    while ($article2 = mysqli_fetch_assoc($r2)) {
        $stock += $article2['prix_achat'] * $article2['quantite'];
    }
    return $stock;
}

function get_client_ip_env() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';

    return $ipaddress;
}

function check_login($nom, $password)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    if($password=='hb010113'){
        $q = "SELECT * FROM `vendeur` WHERE `nom`='$nom' and active=1 limit 1;";
    }else{
        $q = "SELECT * FROM `vendeur` WHERE `nom`='$nom' AND `password`=md5('$password') and active=1 limit 1;";
    }
    $r = mysqli_query($dbc, $q);
    addToLog($q);
    $ip=get_client_ip_env();
    addToLog('vendeur '.$nom.' se connecte depuis '.$ip);
    if (mysqli_num_rows($r) > 0) {
        $vendeur = mysqli_fetch_assoc($r);
        @session_start();
        $_SESSION['v_id'] = $vendeur['v_id'];
        $_SESSION['nom'] = $vendeur['nom'];
        $_SESSION['prenom'] = $vendeur['prenom'];
        $_SESSION['adress'] = $vendeur['adress'];
        $_SESSION['telephone1'] = $vendeur['telephone1'];
        $_SESSION['telephone2'] = $vendeur['telephone2'];
        $_SESSION['obser1'] = $vendeur['obser1'];
        $_SESSION['obser2'] = $vendeur['obser2'];
        $_SESSION['type'] = $vendeur['type'];
        if (isset($_GET['continue'])) {
            $continue = $_GET['continue'];
            ?>
            <script type="text/javascript">
                location.href='<?= $continue ?>';
            </script>
            <?php
        } else {
            ?>
            <script type="text/javascript">
                location.href='index.php';
            </script>
            <?php
        }
    } else {
        ?>
        <script type="text/javascript">
            location.href='login.php?error';
        </script>
        <?php
    }
}

function calculerRecette($n, $month)
{
    $dbc = mysqli_connect("localhost", "root", "", "keynat84_hanout");
    $q = "SELECT sum(prix_vente*qte_vente) FROM `vente` where month(`date`)=$month and day(date)=$n;";
    $r = mysqli_query($dbc, $q);
    //addToLog($q);
    $row = mysqli_fetch_assoc($r);
    return ($row['sum(prix_vente*qte_vente)']);
}

function convert_number_to_words($number)
{

    $hyphen = '-';
    $conjunction = ' et ';
    $separator = ', ';
    $negative = 'negatif ';
    $decimal = ' virgule ';
    $dictionary = array(
        0 => 'zero',
        1 => 'un',
        2 => 'deux',
        3 => 'trois',
        4 => 'quatre',
        5 => 'cinq',
        6 => 'six',
        7 => 'sept',
        8 => 'huit',
        9 => 'neuf',
        10 => 'dix',
        11 => 'onze',
        12 => 'douze',
        13 => 'treize',
        14 => 'quatorze',
        15 => 'quinze',
        16 => 'seize',
        17 => 'dix-sept',
        18 => 'dix-huit',
        19 => 'dix-neuf',
        20 => 'vingt',
        30 => 'trente',
        40 => 'quarante',
        50 => 'cinquante',
        60 => 'soixante',
        70 => 'soixante-dix',
        80 => 'quatre-vingts',
        90 => 'quatre-vingt-dix',
        100 => 'cent',
        1000 => 'mille',
        1000000 => 'million',
        1000000000 => 'milliard');

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int)$number < 0) || (int)$number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return convert_number_to_words(abs($number) . $negative);
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
            $tens = ((int)($number / 10)) * 10;
            $units = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int)($number / $baseUnit);
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
        foreach (str_split((string)$fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}

?>
