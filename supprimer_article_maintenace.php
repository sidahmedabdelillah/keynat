<?php
require_once('connect_hanout.php');
require_once('includes/function_inc.php');
if (isset($_GET['m_id'])){
    $m_id=mysqli_real_escape_string($dbc,trim($_GET['m_id']));
    $article_id=mysqli_real_escape_string($dbc,trim($_GET['cle']));
    $q="delete from article_maintenance where article_id=$article_id and m_id=$m_id limit 1";
    $r=mysqli_query($dbc,$q);
    if($r){
        header('location:list_maintenance.php?code='.$m_id);
    }else{
        echo mysqli_error($dbc);
    }
}

?>
