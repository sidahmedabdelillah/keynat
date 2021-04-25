<?php
session_start();
if (!isset($_SESSION['prenom'])) {
    $url = $_SERVER['REQUEST_URI'];
  ?><script type="text/javascript">location.href='login.php?continue=<?= $url ?>';</script><?php
}
require_once('includes/function_inc.php');
siPermis($_SESSION['type'], basename(__FILE__, '.php'));
require_once('connect_hanout.php');

if (isset($_GET['submitted'])) {
    $q="select * from vendeur where active=1";
    $r=mysqli_query($dbc,$q);
    $array=[];
    while($row=mysqli_fetch_assoc($r)){
        if($_SESSION['v_id']==$row['v_id']){
            $array[$row['v_id']]=0;
        }else{
            $array[$row['v_id']]=$_GET[$row['v_id']];
        }
    }
    //print_r($array);
    $q = "INSERT INTO `vote` (";
    $i=1;
    $len = count($array);
    foreach ($array as $key => $value) {
        if ($i == $len) {
            $q.="`$key`";
        }else{
            $q.="`$key`,";
        }
        $i++;
    }
    $q.=") VALUES (";
    $i=1;
    foreach ($array as $key => $value) {
        if ($i == $len) {
            $q.="'$value'";
        }else{
            $q.="'$value',";
        }
        $i++;
    }
    $q.=");";
    addToLog($q);
    $r = mysqli_query($dbc, $q);
    if ($r) {
        echo "<script>alert('Vote ajouté');</script>";
    } else {
        echo mysqli_error($dbc);
    }
}
$page_title = "Ajouter Vote";
include('includes/header.html');
?>

<div class="col-md-offset-5">
    <h2>Ajouter un Vote</h2><br>
</div>
<form class="form-horizontal" action="voter.php">

    <?php
    $q = "select * from vendeur where active=1";
    $r = mysqli_query($dbc, $q);
    while ($row = mysqli_fetch_assoc($r)) { ?>
        <div class="form-group">
            <label class="control-label col-md-2" for="<?= $row['v_id'] ?>"><?= $row['prenom'] ?></label>
            <div class="col-md-9">
                <input type="number" class="form-control toSum" onchange="findTotal();" placeholder="Vote pour <?= $row['prenom'] ?>"
                       name="<?= $row['v_id'] ?>" required
                    <?php
                    if ($_SESSION['v_id'] == $row['v_id']) {
                        ?>
                        value="0" readonly
                    <?php
                    }
                    ?>>
            </div>
        </div>

        <?php
    }
    ?>
    <div class="form-group">
        <div class="col-sm-offset-2 col-md-9">
            <input type="hidden" name="submitted" value="TRUE">
            <button type="submit" class="btn btn-primary" id="sumbitBtn">Ajouter Vote</button>
        </div>
    </div>
</form>


<?php
include('includes/footer.html');
?>
<script type="text/javascript">
        function findTotal(){
            var arr = document.getElementsByClassName('toSum');
            var tot=0;
            for(var i=0;i<arr.length;i++){
                if(parseInt(arr[i].value))
                    tot += parseInt(arr[i].value);
            }
            if(tot!==100){
                document.getElementById('sumbitBtn').setAttribute('disabled','TRUE');
            }else{
                document.getElementById('sumbitBtn').removeAttribute('disabled');
            }
        }

</script>
