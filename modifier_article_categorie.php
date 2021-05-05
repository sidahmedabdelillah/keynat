<?php
session_start();
if(!isset($_SESSION['prenom'])){
  $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
$page_title="Modifier";
require_once('connect_hanout.php');

if(isset($_POST["update"])){
    $categorie = $_POST["categorie"] ;
    $cle = $_POST["cle"] ;
    $querr = "UPDATE article SET cat_id = $categorie WHERE cle = $cle";
    echo $querr ;
    $r = mysqli_query($dbc , $querr);
    die();
    
}    
    // seimg

if(isset($_POST["setimg"])){
    $cle = $_POST["cle"] ;
    $target_dir = "img/img_article/";
    $target_file = $target_dir . basename($_FILES["img"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $target_file = $target_dir . $cle . "." . $imageFileType;
    $uploadOk = 1;
// Check file size
    if ($_FILES["img"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
    }

// Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
    echo "Sorry, only JPG, JPEG, PNG  files are allowed.";
    $uploadOk = 0;
    }

// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
  // if everything is ok, try to upload file
  } else {
    if (move_uploaded_file($_FILES["img"]["tmp_name"], $target_file)) {
      echo "The file ". htmlspecialchars( basename( $_FILES["img"]["name"])). " has been uploaded.";
    } else {
      echo "Sorry, there was an error uploading your file.";
        }
    }
    die();
} 



require_once('includes/header.html');

?>
<div class="col-md-12">
    <h1 align="center">Modification + désigniation vide</h1><br>

    <div class="col-md-8">
        <form>
            <div class="input-group">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Recherche par Désignation" id="searchBar"
                        onkeydown="filterTable()">
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Recherche par Désignation" id="searchBar2"
                        onkeydown="filterTable()">
                </div>
                <div class="col-md-4">
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
    <div class="col-md-4">
        <form>
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Recherche par Code" id="searchBarCode"
                    onkeydown="filterTableCode()">
                <div class="input-group-btn">
                    <button class="btn btn-default" type="submit">
                        <i class="glyphicon glyphicon-search"></i>
                        <button class="btn btn-default" type="button" onclick="resetBtn();">Reset</button>
                    </button>
                </div>
            </div>

        </form>
    </div>
    <br><br>
    <div class="col-md-12">
        <form>
            <div class="input-group">
                <div class="col-md-6">
                    <input type="number" class="form-control" placeholder="Début" name="debut"
                        value="<?= @$_GET['debut'] ?>">
                </div>
                <div class="col-md-6">
                    <input type="number" class="form-control" placeholder="Fin" name="fin" value="<?= @$_GET['fin'] ?>">
                </div>



                <div class="input-group-btn">
                    <button class="btn btn-default" type="submit">
                        <i class="glyphicon glyphicon-search"></i>
                    </button>

                </div>
            </div>
        </form>
    </div>
    <div class="col-md-12">
        <form method="post">
            <div class="input-group">
                <div class="col-md-8">
                    <input class="form-control" type="number" name="categorie">
                    <input hidden type="number"   name="fin"
                        value="<?= @$_GET['fin'] ?>" />
                    <input hidden type="number"   name="debut"
                        value="<?= @$_GET['debut'] ?>" />
                        <input hidden name="change_batch"  />

                </div>
                <div class="col-md-4">
                    <button class="btn btn-success" type="submit">Modifier categorie</button>
                </div>
            </div>
        </form>
    </div>


    <br><br><br>
    <table class="table table-hover" style="table-layout:fixed;" id="articleTable">
        <tr>
            <th>Clé</th>
            <th>Désigniation</th>
            <th>Categorie</th>
            <th>Quantité</th>
            <th>Modifier</th>
        </tr>

        <?php

       
if((isset($_POST['debut']) and !empty($_POST['debut'])) and (isset($_POST['fin']) and !empty($_POST['fin']))){
    $fin = $_POST["fin"];
    $debut = $_POST["debut"];
    $categorie = $_POST["categorie"];
    if(isset($_POST["change_batch"]) and isset($_POST["categorie"])){
        $query = "UPDATE article SET cat_id = $categorie where cle between $debut and $fin " ;
        addToLog($q);
        $r = mysqli_query($dbc , $query);
        if($r){
            echo "<alert> reusit </alert>";
        }
}
}
if((isset($_GET['debut']) and !empty($_GET['debut'])) and (isset($_GET['fin']) and !empty($_GET['fin']))){
    $fin=$_GET['fin'];
    $debut=$_GET['debut'];
    
  $q = "SELECT * from article where cle between $debut and $fin order by cle";
  addToLog($q);
  $r = mysqli_query($dbc,$q);
  $articles=mysqli_fetch_all($r, MYSQLI_ASSOC) ;
  foreach($articles as $article){
      $cat_id = $article["cat_id"];
      $cat_querry = "SELECT categorie FROM categorie WHERE cat_id = $cat_id" ;
      $cat_r = mysqli_query($dbc , $cat_querry);
      if($cat_r){
          $cat_array = mysqli_fetch_all($cat_r , MYSQLI_ASSOC);
          if(sizeof($cat_array) > 0){
              $categorie = $cat_array[0]["categorie"];
          }else{
              $categorie = '' ;
          }

      }else{
          $categorie = '' ;
      }
    ?>
        <tr>
            <td><?= $article['cle'] ?></td>
            <td style="word-wrap: break-word;"><?= $article['designiation'] ?></td>
            <td><?= $categorie ?></td>
            <td style="word-wrap: break-word;"><?= $article['quantite'] ?></td>
            <td>
                <button type="button" class="btn btn-primary btn-md" data-toggle="modal"
                    data-target="#dialog<?= $article['cle'] ?>">
                    Modifier
                </button>
            </td>
        </tr>
        <div class="modal fade" id="dialog<?= $article['cle'] ?>" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><?= $article['designiation'] ?></h4>
                    </div>
                    <div class="modal-body">
                        <p>
                        <ul class="list-group">
                            <li class="list-group-item">Designiation
                                <input type="text" class="form-control" id="designiation_a<?= $article['cle'] ?>"
                                    value="<?= $article['designiation'] ?>"
                                    <?php if($article['quantite']>0){echo 'required';}?>>
                            </li>
                            <li class="list-group-item">Clé
                                <span class="badge"><?= $article['cle'] ?></span>
                            </li>
                            <li class="list-group-item">Quantité Restante
                                <span class="badge"> <?= $article['quantite'] ?> </span>
                            </li>
                            <li class="list-group-item">Niveau :
                                <input onChange="change(<?= $article['cle'] ?>)" type="number" class="form-control"
                                    id="niveau<?= $article['cle'] ?>" placeholder="Niveau" name="niveau" required>
                            </li>
                            <li class="list-group-item">Categorie :
                                <span class="badge"> <?= $article['cat_id'] ?> </span>
                                <select id="categorie<?= $article['cle'] ?>" name="parent"
                                    class="form-control form-control-sm">
                                </select>
                            </li>
                            <li class="list-group-item">Image :
                                <input type="file" name="img" class="form-control-file" id="img<?= $article['cle'] ?>">
                            </li>

                        </ul>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" style="float:left;"
                            onclick="modif(<?= $article['cle'] ?>)">Modifier</button>
                        <button type="button" class="btn btn-primary" style="float:left;"
                            onclick="modifImg(<?= $article['cle'] ?>)">Update Image</button>

                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

                    </div>
                </div>

            </div>
        </div>
</div>
<?php
  }
}
 ?>

</table>

<?php
require_once('includes/footer.html');
 ?>
<script type="text/javascript">
function getArticles() {
    var article1 = document.getElementById('searchBar').value;
    var article2 = document.getElementById('searchBar2').value;
    var article3 = document.getElementById('searchBar3').value;
    var cle = parseInt(document.getElementById('searchBarCode').value);
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else { // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("articleTableDiv").innerHTML = this.responseText;
        }
    }
    xmlhttp.open("GET", "articles.php?vente&des1=" + article1 + "&des2=" + article2 + "&des3=" + article3 + "&cle=" +
        cle, true);
    xmlhttp.send();
}

function modif(cat) {
    const categorie = document.getElementById('categorie' + cat).value;
    var fd = new FormData();
    // Check file selected or not
    fd.append('categorie', categorie)
    fd.append('cle', cat)
    fd.append('update', true)
    $.ajax({
        url: 'modifier_article_categorie.php',
        type: 'post',
        data: fd,
        contentType: false,
        processData: false,
        success: function(output) {
            alert(output);
        },
        error: function(r) {
            alert(r);
        }
    });

}

function modifImg(cat) {
    const categorie = document.getElementById('categorie' + cat).value;
    var fd = new FormData();
    var files = $(`#img${cat}`)[0].files;
    console.log(files)
    // Check file selected or not
    if (files.length > 0) {
        fd.append('img', files[0]);
        fd.append('setimg', true)
        fd.append("cle", cat)

        $.ajax({
            url: 'modifier_article_categorie.php',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(output) {
                alert(output);
            },
            error: function(r) {
                alert(r);
            }
        });
    }
}
</script>

<?php 

$dbc = mysqli_connect("127.0.0.1", "strapi", "strapi", "keynat84_hanout");
    $query = " SELECT * from categorie " ;
    $r = mysqli_query($dbc, $query) ;
    $options = mysqli_fetch_all($r , MYSQLI_ASSOC);
    $json = json_encode($options) ;
    echo "<script>" ;
    echo "let option = $json" ;
    echo "</script>" ;
?>

<script>
function change(cle) {
    const level = $(`#niveau${cle}`).val();
    console.log(cle)
    const selected = option.filter((op) => {
        return op.level == level;
    })
    const selectElement = $(`#categorie${cle}`)
    selectElement.empty()
    $.each(selected, function(index, cat) {
        $(`#categorie${cle}`).append($('<option/>', {
            value: cat.cat_id,
            text: cat.categorie
        }));
    });
}
</script>