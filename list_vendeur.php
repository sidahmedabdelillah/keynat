<?php
session_start();
if (!isset($_SESSION['prenom'])) {
    $url = $_SERVER['REQUEST_URI'];
  ?><script type="text/javascript">location.href='login.php?continue=<?= $url ?>';</script><?php
}
$page_title = 'Liste des Vendeurs';
require_once('includes/function_inc.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
require_once('connect_hanout.php');
$page_title = "Liste Vendeur";
require_once('includes/header.html');
if(isset($_GET['desactivate'])){
  $v_id=$_GET['desactivate'];
  desactiverVendeur($v_id);
}
if(isset($_GET['activate'])){
  $v_id=$_GET['activate'];
  activerVendeur($v_id);
}

if(isset($_POST['submitted'])){
  $v_id=$_POST['v_id'];
  $nom=$_POST['nom'];
  $prenom=$_POST['prenom'];
  $adress=$_POST['adress'];
  $type=$_POST['type'];
  $telephone1=$_POST['telephone1'];
  $telephone2=$_POST['telephone2'];
  $obser1=$_POST['obser1'];
  $obser2=$_POST['obser2'];
  $type=$_POST['type'];
echo '</br></br></br>';
  $pourcentage=$_POST['pourcentage'];
  $q="UPDATE `vendeur` SET `nom`='$nom',`prenom`='$prenom',`adress`='$adress',`telephone1`='$telephone1',`telephone2`='$telephone2',`obser1`='$obser1',`obser2`='$obser2',`type`='$type'";
  if(!empty($_POST['pass'])){
    $password=md5($_POST['pass']);
    $q.=",`password`='$password'";
  }
  $q.=",`pourcentage`='$pourcentage' WHERE v_id='$v_id'";
  echo $q;
  $r=mysqli_query($dbc,$q);
  if($r){
    ?>
    <script type="text/javascript">
      alert('Informations de Vendeur Modifiées');
    </script>
    <?php
  }else{
    echo mysqli_error($dbc);
  }
}
?>


<div class="col-xs-12">
    <h1 align="center"><?= NOM ?> Liste des vendeurs</h1><br>

    <div class="col-xs-8">
        <form>
            <div class="input-group">
                <div class="col-xs-4">
                    <input type="text" class="form-control" placeholder="Recherche par Désignation" id="searchBar"
                           onkeydown="filterTable()">
                </div>

                <div class="col-xs-4">
                    <input type="text" class="form-control" placeholder="Recherche par Désignation" id="searchBar2"
                           onkeydown="filterTable()">
                </div>

                <div class="col-xs-4">
                    <input type="text" class="form-control" placeholder="Recherche par Désignation" id="searchBar3"
                           onkeydown="filterTable()">
                </div>

                <div class="input-group-btn">
                    <button class="btn btn-default" type="submit">
                        <i class="glyphicon glyphicon-search"></i>
                        <button class="btn btn-default" type="button" onclick="resetBtn();">Reset</button>
                    </button>
                </div>

            </div>
        </form>
    </div>
    <div class="col-xs-4">
        <form>
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Recherche par Code" id="searchBarCode" onkeydown="filterTableCode()" autofocus name="code">
                <div class="input-group-btn">
                    <button class="btn btn-default" type="submit">
                        <i class="glyphicon glyphicon-search"></i>
                        <button class="btn btn-default" type="button" onclick="resetBtn();">Reset</button>
                    </button>
                </div>
            </div>

        </form>
    </div>

    <br><br><br>
    <table class="table table-hover" style="table-layout:fixed;" id="articleTable">
        <tr>
            <th>Clé</th>
            <th class="col-xs-3">Vendeur</th>
            <th>Type</th>
            <th>Actif</th>
            <th>Modifier</th>
        </tr>
        <?php

        $q = "SELECT * FROM `vendeur`";
        $q .= " order by `v_id`";
        addToLog($q);
        $r = mysqli_query($dbc, $q);
        while ($row = mysqli_fetch_assoc($r)) {
            ?>
            <tr>
                <td><?= $row['v_id'] ?></td>
                <td style="word-wrap: break-word;"><?= $row['prenom'] ?></td>
                <td><?= getTypeVendeur($row['type']) ?></td>
                <td><?= ifVendeurActif($row['v_id']) ?></td>
                <td>
                    <button type="button" class="btn btn-danger btn-md" data-toggle="modal"
                            data-target="#dialog<?= $row['v_id'] ?>">Modifier
                    </button>
                </td>
            </tr>
            <div class="modal fade" id="dialog<?= $row['v_id'] ?>" role="dialog" tabindex="-1">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title"><?= $row['prenom'] ?></h4>
                        </div>
                        <form  action="list_vendeur.php" method="post">
                        <div class="modal-body">


                            <ul class="list-group">
                                Nom d'utilisateur:
                                <li class="list-group-item"><input type="text"
                                                                   name="nom"
                                                                   value="<?= $row['nom'] ?>"
                                                                   class="form-control"></li>
                                Nom Complet:
                                <li class="list-group-item"><input type="text"
                                                                   name="prenom"
                                                                   value="<?= $row['prenom'] ?>"
                                                                   class="form-control"></li>
                               Mot de passe:
                               <li class="list-group-item"><input type="password"
                                                                  name="pass"
                                                                  class="form-control"></li>
                                Pourcentage:
                                <li class="list-group-item"><input type="text"
                                                                   name="pourcentage" value="<?= $row['pourcentage'] ?>"
                                                                   class="form-control"></li>
                                Adresse:
                                <li class="list-group-item"><input type="text"
                                                                   name="adress"
                                                                   value="<?= $row['adress'] ?>"
                                                                   class="form-control"></li>
                                Type de Vendeur:
                                <li class="list-group-item">
                                <select class="form-control" name="type">
                                  <option value="<?= @$row['type'] ?>"><?= @getTypeVendeur($row['type']) ?></option>
                                  <option value="1">Administrateur</option>
                                  <option value="2">Vendeur type 1</option>
                                  <option value="3">Vendeur type 2</option>
                                </select>
                              </li>

                                Téléphone 1:
                                <li class="list-group-item"><input type="text"
                                                                   name="telephone1"
                                                                   value="<?= $row['telephone1'] ?>"
                                                                   class="form-control"></li>
                                Téléphone 2:
                                <li class="list-group-item"><input type="text"
                                                                   name="telephone2"
                                                                   value="<?= $row['telephone2'] ?>"
                                                                   class="form-control"></li>
                                Obser 1:
                                <li class="list-group-item"><input type="text"
                                                                   name="obser1"
                                                                   value="<?= $row['obser1'] ?>"
                                                                   class="form-control"></li>
                                Obser 2:
                                <li class="list-group-item"><input type="text"
                                                                   name="obser2"
                                                                   value="<?= $row['obser2'] ?>"
                                                                   class="form-control"></li>
                                                                   <input type="hidden" name="submitted" value="TRUE">
                                                                   <input type="hidden" name="v_id" value="<?= $row['v_id'] ?>">
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" style="float:left;">Modifier Vendeur</button>
                            <?php if(ifVendeurActif($row['v_id'])=='Compte Actif'){?>
                              <a href="list_vendeur.php?desactivate=<?= $row['v_id'] ?>"><button type="button" class="btn btn-danger" style="float:left;">Désactiver Vendeur
                              </button></a>
                              <?php
                            }else{?>
                              <a href="list_vendeur.php?activate=<?= $row['v_id'] ?>"><button type="button" class="btn btn-success" style="float:left;">Activer Vendeur
                              </button></a>
                              <?php
                            }?>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                        </div>
                        </form>
                    </div>

                </div>
            </div>
            <?php
        }

        ?>

    </table>

    <?php
    require_once('includes/footer.html');
    ?>
