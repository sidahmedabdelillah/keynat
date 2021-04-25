<?php
require_once('includes/function_inc.php');
//$dbc = mysqli_connect("localhost", "root", "SUgrfF4tPPtywWNN", "keynat84_hanout");
$dbc = mysqli_connect("127.0.0.1", "strapi", "strapi", "keynat84_hanout");
/* check connection */
if (mysqli_connect_errno()) {
    echo "Connect failed: %s\n", mysqli_connect_error() ;
    exit();
}


/* change character set to utf8 */
if (!mysqli_set_charset($dbc, "utf8")) {
    printf("Error loading character set utf8: %s\n", mysqli_error($dbc));
    exit();
} else {
    mysqli_character_set_name($dbc);
}
?>
