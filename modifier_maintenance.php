<?php //hebergement

require_once('connect_hanout.php');
require_once('includes/function_inc.php');
if (isset($_POST['article'])){
	$article= $_POST['article'];
	$nombre_status = $article.'_nombre_status';
	$nombre_status= $_POST[$nombre_status];
	$nombre_status_editer= $article.'_nombre_status_editer';
	$nombre_status_editer= $_POST[$nombre_status_editer];
	$status_supprimer= $article.'_status_supprimer';
	$status_supprimer= $_POST[$status_supprimer];
	$marque=mysqli_real_escape_string($dbc,trim($_POST['marque']));
	$nom=mysqli_real_escape_string($dbc,trim($_POST['nom']));
	$telephone=mysqli_real_escape_string($dbc,trim($_POST['telephone']));
	$problem=mysqli_real_escape_string($dbc,trim($_POST['problem']));
	$etat=mysqli_real_escape_string($dbc,trim($_POST['etat']));
	$responsable=mysqli_real_escape_string($dbc,trim($_POST['responsable']));



$q="UPDATE `maintenance` SET `marque` = '$marque', `nom` = '$nom', `telephone` = '$telephone', `problem` = '$problem', `remarque` = '$remarque', `etat` = '$etat', `responsable` = '$responsable' WHERE `maintenance`.`m_id` = $article;";
	addToLog($q);
	$r=mysqli_query ($dbc, $q);
	if($r){
    header('location:list_maintenance.php?code='.$article);
  }else{
    echo mysqli_error($dbc);
  }



	for ($i=1; $i <= $nombre_status; $i++) { 

		$status_name = $article.'_nouveau_status_'.$i;
		$nouveau_status=mysqli_real_escape_string($dbc,trim($_POST[$status_name]));
		if(!empty($nouveau_status)){
			$q="SELECT * FROM status WHERE maintenance_id = '$article'";
			$r=mysqli_query ($dbc, $q);
			$rowcount=mysqli_num_rows($r) + 1;

			$vendeur = $_SESSION['v_id'];

			$q="SELECT * FROM vendeur WHERE v_id = '$vendeur'";
            $r=mysqli_query ($dbc, $q);
            $responsable = mysqli_fetch_assoc($r);
            $responsable = $responsable['prenom'];

			$q="INSERT INTO status (maintenance_id, status_number ,status_text, status, responsable) VALUES ('$article', '$rowcount' , '$nouveau_status', '$etat', '$responsable')";
			$r=mysqli_query ($dbc, $q);

			$data = "code=$article&status_number=$rowcount&status_text=$nouveau_status&status=$etat&created_by=$responsable";
            $path="/public/api/maintenance/status/store";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://keynatech.cocktillo.com/keynatech'.$path);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $headers = array();
            $headers[] = 'Token-Key: 123';
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            //echo $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
		}
	}

	$nombre_status_editer = explode(',', $nombre_status_editer);

	foreach($nombre_status_editer as $id) { 
		$status_name = $article.'_status_text_exst_'.$id;
		$edited_status=mysqli_real_escape_string($dbc,trim($_POST[$status_name]));
		if(!empty($edited_status)){

			$q="UPDATE status SET status_text = '$edited_status' where id = '$id'";
			$r=mysqli_query ($dbc, $q);

			$q="SELECT * FROM status WHERE id = '$id'";
			$r=mysqli_query ($dbc, $q);
			$fetch = mysqli_fetch_assoc($r);
			$status_number = $fetch['status_number'];

			$q="SELECT * FROM maintenance WHERE m_id = '$article'";
			$r=mysqli_query ($dbc, $q);
			$fetch = mysqli_fetch_assoc($r);
			$etat = $fetch['etat'];

			$vendeur = $_SESSION['v_id'];

			$q="SELECT * FROM vendeur WHERE v_id = '$vendeur'";
            $r=mysqli_query ($dbc, $q);
            $responsable = mysqli_fetch_assoc($r);
            $responsable = $responsable['prenom'];


			$data = "code=$article&status_number=$status_number&status_text=$edited_status&status=$etat&edited_by=$responsable";
            $path="/public/api/maintenance/status/update/$article";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://keynatech.cocktillo.com/keynatech'.$path);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $headers = array();
            $headers[] = 'Token-Key: 123';
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            //echo $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);

		}
	}

	$status_supprimer = explode(',', $status_supprimer);

	foreach($status_supprimer as $id) { 
		if(!empty($status_supprimer)){
		$q="UPDATE status SET status = 'supprimé' where id = '$id'";
			$r=mysqli_query ($dbc, $q);

			$q="SELECT * FROM status WHERE id = '$id'";
			$r=mysqli_query ($dbc, $q);
			$fetch = mysqli_fetch_assoc($r);
			$status_number = $fetch['status_number'];

			$vendeur = $_SESSION['v_id'];

			$q="SELECT * FROM vendeur WHERE v_id = '$vendeur'";
            $r=mysqli_query ($dbc, $q);
            $responsable = mysqli_fetch_assoc($r);
            $responsable = $responsable['prenom'];

			$data = "code=$article&status_number=$status_number&status=Supprimé&deleted_by=$responsable";
            $path="/public/api/maintenance/status/update/$article";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://keynatech.cocktillo.com/keynatech'.$path);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $headers = array();
            $headers[] = 'Token-Key: 123';
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            //$result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
        }
	}


	
}

?>
