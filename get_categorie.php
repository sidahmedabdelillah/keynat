<?php
session_start();

require_once('includes/function_inc.php') ;
// siPermis($_SESSION['type'], basename(__FILE__, '.php'));
require_once('connect_hanout.php') ;



if(isset($_POST["submited"])){
    $categorie = $_POST["categorie"] ;
    $level = $_POST["level"] ;
    $parent = $_POST['parent'] ;
    $cle = $_POST["cle"] ;
    
    $querry = "UPDATE categorie SET level = $level , categorie = '$categorie' , parent = $parent WHERE cat_id = $cle " ;
    $r = mysqli_query($dbc , $querry) ;
    if($r){
        echo "success" ;
    }else{
        echo "failed" ;
    }
    die();
}
if(isset($_POST["setimg"])){


    $cle = $_POST["cle"] ;
    $target_dir = "img/img_categorie/";
    $target_file = $target_dir . basename($_FILES["img"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $target_file = $target_dir . $cle . "." . $imageFileType ;



    
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
 
}else if(isset($_GET["delete"])){
    $cle = $_GET["cle"] ;
    $q = "DELETE FROM categorie WHERE cat_id =$cle " ;
    echo $q ;
    $r = mysqli_query($dbc , $q) ;
}else{
    $niveau = $_GET["niveau"] ;
    $categorie = $_GET["categorie"] ; 
if(is_numeric($_GET["niveau"])){
    $querry = "select * from categorie WHERE (level = $niveau AND categorie LIKE '%$categorie%') " ;
}else{
    $querry = "select * from categorie WHERE ( categorie LIKE '%$categorie%') " ;
    
}
    $r = mysqli_query($dbc , $querry);
    $categories = mysqli_fetch_all( $r , MYSQLI_ASSOC );
?>


<table class="table table-hover" style="table-layout:fixed;" id="articleTable">
    <tr>
        <th>Cle</th>
        <th>Categorie</th>
        <th>Level</th>
        <th>Editer</th>
    </tr>
    <?php 
    foreach($categories as $cat){
        ?>
    <tr>
        <td><?= $cat["cat_id"]?></td>
        <td><?= $cat["categorie"] ?></td>
        <td><?= $cat["level"] ?></td>
        <td>
            <button type="button" class="btn btn-primary btn-md" data-toggle="modal"
                data-target="#dialog<?= $cat['cat_id'] ?>">Modifier
            </button>
        </td>
    </tr>


    <div class="modal fade" id="dialog<?= $cat['cat_id'] ?>" role="dialog" tabindex="-1">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><?= $cat["categorie"] ?></h4>
                </div>
                <div class="modal-body">
                    <ul class="list-group">
                        Categorie :
                        <li class="list-group-item">
                            <input type="text" id="categorie<?= $cat['cat_id'] ?>" value="<?= $cat["categorie"]?>"
                                class="form-control">
                        </li>
                        Level :
                        <li class="list-group-item">
                            <input type="number" id="level<?= $cat['cat_id'] ?>" value="<?= $cat["level"] ?>"
                                class="form-control">
                        </li>
                        Parent :
                        <li class="list-group-item">
                            <input type="number" id="parent<?= $cat['cat_id'] ?>" value="<?= $cat["level"] ?>"
                                class="form-control">
                        </li>
                        <li class="list-group-item">
                            <input name="img" type="file" id="img<?= $cat['cat_id'] ?>" class="form-control-file">
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button onClick="modif( <?= $cat['cat_id'] ?>)" type="submit" class="btn btn-success"
                        style="float:left;"> Modifier </button>

                    <button type="button" class="btn btn-primary" style="float:left;"
                        onclick="modifImg(<?= $cat['cat_id'] ?>)">Update Image</button>
                    <button onClick="suprimerCategorie( <?= $cat['cat_id'] ?>)" type="submit" class="btn btn-danger"
                        style="float:left;"> Supprimer </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    <?php
    }
?>
</table>

<?php 
}
?>