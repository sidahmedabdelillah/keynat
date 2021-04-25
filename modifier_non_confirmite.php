<?php //hebergement

require_once('connect_hanout.php');
require_once('includes/function_inc.php');
if (isset($_GET['responsable'])){
	$responsable=mysqli_real_escape_string($dbc,trim($_GET['responsable']));
	$action=mysqli_real_escape_string($dbc,trim($_GET['action']));
	$obser1=mysqli_real_escape_string($dbc,trim($_GET['obser1']));
	$classement=mysqli_real_escape_string($dbc,trim($_GET['classement']));
	$action_correct=mysqli_real_escape_string($dbc,trim($_GET['action_correct']));
  $nc_id=$_GET['nc_id'];
	$q="SELECT quand from non_confirmite where nc_id=$nc_id";
	$r=mysqli_query($dbc,$q);
	if(mysqli_fetch_assoc($r)['quand']==NULL){
			$q="UPDATE `non_confirmite` SET `qui_fait_action` = '$responsable', `action` = '$action', `obser1` = '$obser1', `classement` = '$classement', `action_correct` = '$action_correct', `quand` = NOW() WHERE `nc_id` = $nc_id;";
	}else{
		$q="UPDATE `non_confirmite` SET `qui_fait_action` = '$responsable', `action` = '$action', `obser1` = '$obser1', `classement` = '$classement', `action_correct` = '$action_correct' WHERE `nc_id` = $nc_id;";
	}
	addToLog($q);
	$r=mysqli_query ($dbc, $q);
	if($r){
    header('location:list_non_conformite.php?code='.$nc_id);
  }else{
    echo mysqli_error($dbc);
  }
}

?>
