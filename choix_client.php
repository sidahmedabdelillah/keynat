<?php
session_start();
if(isset($_GET['client']) AND $_GET['client']!==""){
  $client=$_GET['client'];
  $_SESSION['client']=$client;
}
?>
