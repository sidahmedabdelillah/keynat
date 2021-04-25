<?php
session_start();
require_once('includes/function_inc.php');
require_once('connect_hanout.php');

if(!isset($_SESSION['prenom'])){
  $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
if(isset($_GET['id'])){
  $client=$_GET['id'];
}else{
  header('location:list_client.php');
}
$clientInfo=getClientInfo($client);
$page_title=$clientInfo['nom']." ".$clientInfo['prenom'];
require_once('includes/header.html');
if(isset($_GET['page'])){
    $page=$_GET['page'];
    $begin=(($page-1)*10);
    if($page>1){
        $begin++;
    }
    $end=($page*10);
}else{
    $begin = 0;
    $end = 10;
}
?>
<style>
    .pagination {
        display: inline-block;
        padding-left: 0;
        margin: 20px 0;
        border-radius: 4px
    }

    .pagination > li {
        display: inline
    }

    .pagination > li > a, .pagination > li > span {
        position: relative;
        float: left;
        padding: 6px 12px;
        margin-left: -1px;
        line-height: 1.42857143;
        color: #337ab7;
        text-decoration: none;
        background-color: #fff;
        border: 1px solid #ddd
    }

    .pagination > li:first-child > a, .pagination > li:first-child > span {
        margin-left: 0;
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px
    }

    .pagination > li:last-child > a, .pagination > li:last-child > span {
        border-top-right-radius: 4px;
        border-bottom-right-radius: 4px
    }

    .pagination > li > a:focus, .pagination > li > a:hover, .pagination > li > span:focus, .pagination > li > span:hover {
        z-index: 2;
        color: #23527c;
        background-color: #eee;
        border-color: #ddd
    }

    .pagination > .active > a, .pagination > .active > a:focus, .pagination > .active > a:hover, .pagination > .active > span, .pagination > .active > span:focus, .pagination > .active > span:hover {
        z-index: 3;
        color: #fff;
        cursor: default;
        background-color: #337ab7;
        border-color: #337ab7
    }

    .pagination > .disabled > a, .pagination > .disabled > a:focus, .pagination > .disabled > a:hover, .pagination > .disabled > span, .pagination > .disabled > span:focus, .pagination > .disabled > span:hover {
        color: #777;
        cursor: not-allowed;
        background-color: #fff;
        border-color: #ddd
    }

    .pagination-lg > li > a, .pagination-lg > li > span {
        padding: 10px 16px;
        font-size: 18px;
        line-height: 1.3333333
    }

    .pagination-lg > li:first-child > a, .pagination-lg > li:first-child > span {
        border-top-left-radius: 6px;
        border-bottom-left-radius: 6px
    }

    .pagination-lg > li:last-child > a, .pagination-lg > li:last-child > span {
        border-top-right-radius: 6px;
        border-bottom-right-radius: 6px
    }

    .pagination-sm > li > a, .pagination-sm > li > span {
        padding: 5px 10px;
        font-size: 12px;
        line-height: 1.5
    }

    .pagination-sm > li:first-child > a, .pagination-sm > li:first-child > span {
        border-top-left-radius: 3px;
        border-bottom-left-radius: 3px
    }

    .pagination-sm > li:last-child > a, .pagination-sm > li:last-child > span {
        border-top-right-radius: 3px;
        border-bottom-right-radius: 3px
    }
    .form-actions {
        margin: 0;
        background-color: transparent;
        text-align: center;
    }
</style>

<div class="col-xs-12">
<h1 align="center">Les transactions de <?= $clientInfo['nom']." ".$clientInfo['prenom'] ?></h1><br>
<h1 align="center">Les Ventes</h1><br>
<table class="table table-hover">
<tr>
  <th>Facture Vente</th>
  <th>Total</th>
  <th>Versement</th>
  <th>Date</th>
</tr>

<?php
$q="select * from facture_vente where client_id=$client order by facture_vente_id desc limit $begin,$end;";
addToLog($q);
$r=mysqli_query($dbc,$q);
$nbr=mysqli_num_rows($r);
if($nbr>0){
while($facture=mysqli_fetch_assoc($r)){
 ?>
<tr>
  <td><a href="facture_vente.php?id=<?= $facture['facture_vente_id']?>"><button class="btn btn-default"><?= $facture['facture_vente_id']?></button></a></td>
  <td><?= getTotalFactureVente($facture['facture_vente_id']) ?></td>
  <td><?= getVersementFacture($facture['facture_vente_id'])['val'] ?></td>
  <td><?= $facture['date_vente']?></td>
</tr>
<?php }}else{?>
  <h3 style="color:red">Pas de facture pour ce client</h3>
<?php }?>
</table>
<div class="form-actions">
    <ul class="pagination">
        <?php
        $pageNbr=intval($nbr/10);
        if(($nbr%10)<>0){
            $pageNbr++;
        }
        $pageNbr++;
        for ($i = 1; $i <= $pageNbr; $i++) {
            $url=$_SERVER['PHP_SELF']."?id=".$client."&page=".$i;
            if($_SERVER['REQUEST_URI']===$url){
                $active='class="active"';
            }else{
                $active="";
            }
            echo '<li '.$active.'><a href="'.$url.'">'.$i.'</a></li>';
        }
        ?>
    </ul>
</div>
</div>
<?php
require_once('includes/footer.html');
 ?>
