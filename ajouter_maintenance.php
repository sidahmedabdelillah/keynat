<?php
session_start();
if (!isset($_SESSION['prenom'])) {
    $url = $_SERVER['REQUEST_URI'];
  ?><script type="text/javascript">location.href='login.php?continue=<?= $url ?>';</script><?php
}
require_once('includes/function_inc.php');
siPermis($_SESSION['type'], basename(__FILE__, '.php'));
require_once('connect_hanout.php');
//include('includes/request.php');

if (isset($_GET['submitted'])) {
    $marque = strtolower($_GET['marque']);
    $vendeur = $_SESSION['v_id'];
    $responsable = $_SESSION['v_id'];
    $nom = strtolower($_GET['nom']);
    $telephone = $_GET['telephone'];
    $problem = strtolower($_GET['problem']);
    if (!empty($_GET['remarque'])) {
        $remarque = strtolower($_GET['remarque']);
    } else {
        $remarque = NULL;
    }
    if (!empty($_SESSION['client'])) {
        $client = $_SESSION['client'];
    } else {
        $client = 0;
    }
    
    $letter = strtoupper(substr(md5(time()), 0, 5));
    $a = $letter;
    $q = "INSERT INTO `maintenance` (`marque`, `nom`, `telephone`, `problem`, `remarque`, `v_id`, `responsable`, `etat`, `pass`, `client`) VALUES ('$marque', '$nom', '$telephone', '$problem', '$remarque', '$vendeur', '$responsable', 'En Attente', '$a','$client');";
    addToLog($q);
    $r = mysqli_query($dbc, $q);
    if ($r) {

        $last_id = mysqli_insert_id($dbc);
        $data = "code=$last_id&name=$nom&phone=$telephone&brand=$marque&included=nothing&problem=$problem&notes=kkk&password=$letter";
        $path="/public/api/maintenance/store";

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


        $nombre_status= $_GET['nombre_status'];

        for ($i=1; $i <= $nombre_status; $i++) { 

        $status_name = 'nouveau_status_'.$i;
        $nouveau_status=mysqli_real_escape_string($dbc,trim($_GET[$status_name]));
        if(!empty($nouveau_status)){

            $q="SELECT * FROM vendeur WHERE v_id = '$vendeur'";
            $r=mysqli_query ($dbc, $q);
            $responsable = mysqli_fetch_assoc($r);
            $responsable = $responsable['prenom'];

            $q="INSERT INTO status (maintenance_id, status_number ,status_text, status, responsable) VALUES ('$last_id', '$i' ,'$nouveau_status', 'En Attente', '$responsable')";
            $r=mysqli_query ($dbc, $q);

            
            $local_id = mysqli_insert_id($dbc);

            $data = "code=$last_id&local_id=$local_id&status_number=$i&status_text=$nouveau_status&status=En Attente&created_by=$responsable";
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
           // echo $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);


        }
        }


        //send_request("post", "/public/api/maintenance/store/", "code=$last_id&name=$nom&phone=$telephone&brand=$marque&included=&problem=$problem&notes=&password=$letter");


        ?>
        <script>
          //  alert('Maintenance Ajouté sous la clé <?=mysqli_insert_id($dbc)?>');
        //    window.location = "print_maintenance.php?id=<?=mysqli_insert_id($dbc)?>";

        </script>

        <?php
    } else {
        echo mysqli_error($dbc);
    }
}
$page_title = "Ajouter Maintenance";
include('includes/header.html');

?>

<div class="col-md-offset-5">
    <h2>Ajouter une maintenance</h2><br>
</div>
<div class="col-md-12 text-center">
    <?php
    if (isset($_SESSION['client'])) {
        echo '<h3 style="color: blue;">Client choisi: ' . getClientInfo($_SESSION['client'])['nom'] . " " . getClientInfo($_SESSION['client'])['prenom'] . '</h3>';
    }
    ?>
    <button class="btn btn-default" data-toggle="modal" data-target="#fourModal">Choisir client</button>
    <a href="includes/annuler.php?client&maintenance">
        <button class="btn btn-danger">Supprimer Client</button>
    </a>
    <br><br>
</div>
<form class="form-horizontal" method="get" action="ajouter_maintenance.php">
                <input type="hidden" name="nombre_status" id="nombre_status">

    <?php
    if (isset($_SESSION['client'])) {
        $client_info = getClientInfo($_SESSION['client']);
        ?>
        <div class="form-group">
            <label class="control-label col-md-2" for="nom">Nom:</label>
            <div class="col-md-9">
                <input class="form-control" id="nom" placeholder="Nom" name="nom"
                       value="<?= $client_info['nom'] . ' ' . $client_info['prenom'] ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-2" for="telephone">Téléphone:</label>
            <div class="col-md-9">
                <input class="form-control" id="telephone" placeholder="Téléphone" name="telephone"
                       value="<?= $client_info['telephone1'] ?>" required>
            </div>
        </div>
    <?php } else {
        ?>
        <div class="form-group">
            <label class="control-label col-md-2" for="nom">Nom:</label>
            <div class="col-md-9">
                <input class="form-control" id="nom" placeholder="Nom" name="nom" required readonly>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-2" for="telephone">Téléphone:</label>
            <div class="col-md-9">
                <input class="form-control" id="telephone" placeholder="Téléphone" name="telephone" required>
            </div>
        </div>
    <?php } ?>
    <div class="form-group">
        <label class="control-label col-md-2" for="marque">Marque:</label>
        <div class="col-md-9">
            <input type="text" class="form-control" id="marque" placeholder="Marque" name="marque" required>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-2" for="problem">Probleme:</label>
        <div class="col-md-9">
            <input class="form-control" id="problem" placeholder="Probleme" name="problem" required>
        </div>
    </div>
        <br><br>
        <label style="margin-left: 130px;" for="status" >Les status: <a id="nouveau_status" onclick="new_status()">(ajouter)</a></label>

        <!----------  Nouveau statut  ------------>
                  <div id="nouveau_status_div" style="margin-left: 130px;">
                  <textarea name='nouveau_status_1' id='nouveau_status_1' style='margin-right:5px;display: none;' rows='2' cols='78' placeholder='Ecrivez un seul statut'></textarea>
                  <textarea name='nouveau_status_2' id='nouveau_status_2' style='margin-right:5px;display: none;' rows='2' cols='78' placeholder='Ecrivez un seul statut'></textarea>
                  <textarea name='nouveau_status_3' id='nouveau_status_3' style='margin-right:5px;display: none;' rows='2' cols='78' placeholder='Ecrivez un seul statut'></textarea>
                  <textarea name='nouveau_status_4' id='nouveau_status_4' style='margin-right:5px;display: none;' rows='2' cols='78' placeholder='Ecrivez un seul statut'></textarea>
                  <textarea name='nouveau_status_5' id='nouveau_status_5' style='margin-right:5px;display: none;' rows='2' cols='78' placeholder='Ecrivez un seul statut'></textarea>
                  <textarea name='nouveau_status_6' id='nouveau_status_6' style='margin-right:5px;display: none;' rows='2' cols='78' placeholder='Ecrivez un seul statut'></textarea>
                  </div>
 <br><br>
    <div class="form-group">
        <div class="col-sm-offset-2 col-md-9">
            <input type="hidden" name="submitted" value="TRUE">
            <button type="submit" id="add_maintenance" class="btn btn-primary">Ajouter Maintenance</button>
        </div>
    </div>
</form>

<div id="fourModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Choisir le client</h4>
            </div>
            <div class="modal-body">
                <p>
                <ul class="list-group">
                    <select class="form-control" id="client" autofocus>
                        <option value="">Choisir un client</option>
                        <?php
                        $q = "SELECT * from client;";
                        addToLog($q);
                        $r = mysqli_query($dbc, $q);
                        while ($row = mysqli_fetch_assoc($r)) {
                            echo '<option value="' . $row['client_id'] . '">' . $row['prenom'] . ' ' . $row['nom'] . '</option>';
                        }
                        ?>

                    </select>
                </ul>
                </p>
                Nouveau Client:
                <li class="list-group-item"><input id="nouv_client" class="form-control"></li>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" style="float:left;" onclick="choix_client();">Valider
                </button>
                <button type="submit" class="btn btn-default" style="float:left;" onclick="nouv_client();">Nouveau
                    Client
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

            </div>
        </div>


    </div>
</div>
<?php
include('includes/footer.html');
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script>

  var clicksNumbre = 0;

    function new_status(){
    clicksNumbre++;
    document.getElementById('nombre_status').value = clicksNumbre;
    document.getElementById('nouveau_status_'+clicksNumbre).style.display = "inline-block";
    document.getElementById('nouveau_status_'+clicksNumbre).focus();;  
  }


    $(document).ready(function () {

/*
$('#add_maintenance').click(function(){

    $.ajax
    ({ 
        url: 'http://keynatech.cocktillo.com/keynatech/public/api/maintenance/store',
        data: {'code' : 2 ,'name' : "test", 'phone' : "4320544432", 'brand' : "pc", 'included' : "nnn", 'problem' : "test", 'notes': "test", 'password' : "test"},
        type: 'post',
        headers: {"token-key": "123"},
        success: function(result)
        {
            alert(result);
        }
    });

});     
*/





        $("#client").select2({
            placeholder: "Choisir un client",
            allowClear: true
        });
    });
</script>
