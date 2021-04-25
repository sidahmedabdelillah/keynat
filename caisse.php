<?php
session_start();
require_once('includes/function_inc.php');
require_once('connect_hanout.php');
if (!isset($_SESSION['prenom'])) {
    $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
$v_id=$_SESSION['v_id'];
if (isset($_GET['submitted'])) {
    $p5 = $_GET['5'];
    $p10 = $_GET['10'];
    $p20 = $_GET['20'];
    $p50 = $_GET['50'];
    $p100 = $_GET['100'];
    $p200 = $_GET['200'];
    $p500 = $_GET['500'];
    $p1000 = $_GET['1000'];
    $p2000 = $_GET['2000'];
    $caisse = $_GET['caisse'];
    $caisser = $_GET['caisser'];
    $pc = $_GET['pc'];
    $diff = $_GET['diff'];
    $q="INSERT INTO `caisse_verification` (`p5`, `p10`, `p20`, `p50`, `p100`, `p200`, `p500`, `p1000`, `p2000`, `caisse`, `caisser`, `pc`, `diff`, `v_id`) VALUES ($p5, $p10, $p20, $p50, $p100, $p200, $p500, $p1000, $p2000, $caisse, $caisser, $pc, $diff, $v_id);";
    addToLog($q);
    $r=mysqli_query($dbc,$q);
    if($r){
        //
    }else{
        echo mysqli_error($dbc);
    }
}
$page_title="Verfier la caisse";
include('includes/header.html');
?>
<div class="col-md-10 col-md-offset-1">
    <?php
    $q="SELECT * FROM `caisse_verification` ORDER BY `caisse_verification`.`id` DESC limit 1;";
    addToLog($q);
    $r=mysqli_query($dbc,$q);
    $row=mysqli_fetch_assoc($r);
    $date=date_create($row['date']);
     ?>
    <h2 align="center">Vérification de caisse</h2><br>
    <h4 align="center">Derniere Vérification de caisse le: <?= date_format($date, 'Y-m-d') ?> à <?= date_format($date, 'H:i:s') ?> <br><br>Par: <?= getVendeurInfo($row['v_id'])['prenom'] ?><br><br>Résultat: <?= $row['diff'] ?></h4>

    <form class="form-horizontal" action="caisse.php">

        <div class="form-group">
            <label class="control-label col-md-2" for="5">5DA:</label>
            <div class="col-md-9">
                <input type="number" class="form-control" value="<?= $row['p5'] ?>" onchange="calcul();" id="5" name="5" required>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-2" for="10">10DA:</label>
            <div class="col-md-9">
                <input type="number" class="form-control" value="<?= $row['p10'] ?>" onchange="calcul();" id="10" name="10" required>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-2" for="20">20DA:</label>
            <div class="col-md-9">
                <input type="number" class="form-control" value="<?= $row['p20'] ?>" onchange="calcul();" id="20" name="20" required>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-2" for="50">50DA:</label>
            <div class="col-md-9">
                <input type="number" class="form-control" value="<?= $row['p50'] ?>" onchange="calcul();" id="50" name="50" required>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-2" for="100">100DA:</label>
            <div class="col-md-9">
                <input type="number" class="form-control" value="<?= $row['p100'] ?>" onchange="calcul();" id="100" name="100" required>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-2" for="200">200DA:</label>
            <div class="col-md-9">
                <input type="number" class="form-control" value="<?= $row['p200'] ?>" onchange="calcul();" id="200" name="200" required>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-2" for="500">500DA:</label>
            <div class="col-md-9">
                <input type="number" class="form-control" value="<?= $row['p500'] ?>" onchange="calcul();" id="500" name="500" required>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-2" for="1000">1000DA:</label>
            <div class="col-md-9">
                <input type="number" class="form-control" value="<?= $row['p1000'] ?>" onchange="calcul();" id="1000" name="1000" required>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-2" for="2000">10DA:</label>
            <div class="col-md-9">
                <input type="number" class="form-control" value="<?= $row['p2000'] ?>" onchange="calcul();" id="2000" name="2000" required>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-2" for="caisse">Caisse:</label>
            <div class="col-md-9">
                <input type="number" class="form-control" value="<?= $row['caisse'] ?>" onchange="calcul();" id="caisse" name="caisse"
                       required>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-2" for="caisser">Caisse Reel:</label>
            <div class="col-md-9">
                <input type="number" class="form-control" id="caisser" readonly value="0" name="caisser" required>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-2" for="pc">PC:</label>
            <div class="col-md-9">
                <input type="number" class="form-control" id="pc" name="pc" value="<?= getCaisseInfo()['val']+getCaisseInfo()['val2'] ?>"
                       readonly required>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-2" for="diff">Difference Caisse PC - Caisse Reel:</label>
            <div class="col-md-9">
                <input type="number" class="form-control" id="diff" readonly name="diff" value="0" required style="border-color:blue;">
            </div>
        </div>
<!--
        <div class="form-group">
            <label class="control-label col-md-2" for="diff">Ancienne difference:</label>
            <div class="col-md-5">
                <input type="number" class="form-control" id="a_diff" readonly name="a_diff" value="1745" required style="border-color:green;">
            </div>
            <div class="col-md-4">
              <input type="number" class="form-control" id="manque" readonly name="manque" value="0" required>
            </div>
        </div>
-->
        <div class="form-group">
            <div class="col-sm-offset-2 col-md-9">
                <input type="hidden" name="submitted" value="TRUE">
                <button type="submit" class="btn btn-default">Enregister</button>
            </div>
        </div>
    </form>
</div>
<script>
    function calcul() {
        var a5 = parseFloat(document.getElementById('5').value);
        var a10 = parseFloat(document.getElementById('10').value);
        var a20 = parseFloat(document.getElementById('20').value);
        var a50 = parseFloat(document.getElementById('50').value);
        var a100 = parseFloat(document.getElementById('100').value);
        var a200 = parseFloat(document.getElementById('200').value);
        var a500 = parseFloat(document.getElementById('500').value);
        var a1000 = parseFloat(document.getElementById('1000').value);
        var a2000 = parseFloat(document.getElementById('2000').value);
        var caisse = parseFloat(document.getElementById('caisse').value);
        //var a_diff = parseFloat(document.getElementById('a_diff').value);
        var pc = parseFloat(document.getElementById('pc').value);
        var caisseTotal = (a5 * 5) + (a10 * 10) + (a20 * 20) + (a50 * 50) + (a100 * 100) + (a200 * 200) + (a500 * 500) + (a1000 * 1000) + (a2000 * 10) + caisse;
        document.getElementById('caisser').value = caisseTotal;
        document.getElementById('diff').value =caisseTotal - pc;
        var manque = (caisseTotal - pc) - a_diff;
        /*document.getElementById('manque').value = manque;
        if(manque==0){
          document.getElementById('manque').style["border-color"] = "green";
        }else{
          document.getElementById('manque').style["border-color"] = "red";
        }*/
    }
    calcul();
</script>
<?php
include('includes/footer.html');
?>
