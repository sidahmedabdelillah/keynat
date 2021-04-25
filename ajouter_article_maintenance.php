<?php

require_once('connect_hanout.php');
require_once('includes/function_inc.php');
if (isset($_GET['submitted'])){
	$m_id=$_GET['m_id'];
	$article_id=mysqli_real_escape_string($dbc,trim($_GET['cle']));
	$pv=mysqli_real_escape_string($dbc,trim($_GET['pv']));
	$qte=mysqli_real_escape_string($dbc,trim($_GET['qte']));
  @session_start();
  $vendeur=$_SESSION['v_id'];
	// $q1="select * from article_maintenance where article_id=$article_id and m_id=$m_id limit 1";
	// $r1=mysqli_query($dbc,$q1);
	// if(mysqli_num_rows($r1)==0){
		$q="INSERT INTO `article_maintenance` (`article_id`, `m_id`, `pv`, `qte`, `v_id`) VALUES ('$article_id', '$m_id', '$pv', '$qte', '$vendeur');";
	// }else{
	// 	$q="UPDATE article_maintenance set pv=$pv, qte=$qte, v_id=$vendeur where article_id=$article_id and m_id=$m_id";
	// }
	$r=mysqli_query ($dbc, $q);
	if($r){
    header('location:list_maintenance.php?code='.$m_id);
  }else{
    echo mysqli_error($dbc);
  }
}else{
	include('includes/header.html');?>

	<div class="col-md-offset-5">
	    <h2>Ajouter un article</h2><br>
	</div>
	<div class="col-md-10 col-md-offset-1">


	<form class="form-horizontal" method="get" action="ajouter_article_maintenance.php">
	    <div class="form-group">
	        <div>
	            <input type="text" class="form-control" id="cle" placeholder="Article" name="cle" required onchange="getInfo();getInfo();">
	        </div>
	    </div>
			<span id="spanpv">
	    <div class="form-group">
	            <input type="text" class="form-control" id="qte" placeholder="Quantité" name="qte" value="1"
	                   required>
	    </div>

			<div class="form-group">
	            <input type="text" class="form-control" id="pv" placeholder="Prix Vente" name="pv"
	                   required>
	    </div>
		</span>

	    <div class="form-group">
        <input type="hidden" name="submitted" value="TRUE">
				<input type="hidden" name="m_id" value="<?= $_GET['m_id'] ?>">
        <button type="submit" class="btn btn-default">Ajouter Article de maintenance</button>
	    </div>
	</form>
	</div>


	<?php
	include('includes/footer.html');
}

?>

<script type="text/javascript">
function getInfo() {
		var article = document.getElementById('cle').value;
		if (window.XMLHttpRequest) {
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
		} else { // code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function () {
				if (this.readyState == 4 && this.status == 200) {
						document.getElementById("spanpv").innerHTML = this.responseText;
				}
		}
		xmlhttp.open("GET", "article_info_maintenance.php?id=" + article, true);
		xmlhttp.send();
}
</script>
