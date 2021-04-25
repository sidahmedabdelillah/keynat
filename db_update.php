<?php

require_once('connect_hanout.php');
require_once('includes/function_inc.php');

    $q = "SELECT * FROM `status`";

    $r = mysqli_query($dbc, $q);
    if ($r) {
        $status = mysqli_fetch_all($r);

            $path="/public/api/update/status";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://keynatech.cocktillo.com/keynatech'.$path);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($status));

            $headers = array();
            $headers[] = 'Token-Key: 123';
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            echo $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);


    } else {
        echo mysqli_error($dbc);
    }


    /*************************************************/


    $q = "SELECT * FROM `maintenance` WHERE pass != '' limit 500";

    $r = mysqli_query($dbc, $q);
    if ($r) {
        $status = mysqli_fetch_all($r);

            $path="/public/api/update/maintenances";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://keynatech.cocktillo.com/keynatech'.$path);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($status));

            $headers = array();
            $headers[] = 'Token-Key: 123';
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            echo $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);


    } else {
        echo mysqli_error($dbc);
    }


?>
