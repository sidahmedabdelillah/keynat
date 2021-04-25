<?php

echo "inside";


if(isset($_GET['type'], $_GET['path'], $_POST['data'])){
	send_request($_GET['type'], $_GET['path'], $_POST['data']);
}

function send_request($type, $path, $data){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://keynatech.cocktillo.com/keynatech'.$path);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

	$headers = array();
	$headers[] = 'Token-Key: 123';
	$headers[] = 'Content-Type: application/x-www-form-urlencoded' ;
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	echo $result = curl_exec($ch);
	if (curl_errno($ch)) {
	    echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);
}


?>