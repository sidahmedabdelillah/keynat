<?php
session_start();
if (!isset($_SESSION['prenom'])) {
    $url = $_SERVER['REQUEST_URI'];
  ?><script type="text/javascript">
location.href = 'login.php?continue=<?= $url ?>';
</script><?php
}
require_once('includes/function_inc.php');
require_once('connect_hanout.php');


// Check if image file is a actual image or fake image




if (isset($_POST['submitted'])) {

    $categorie = $_POST['categorie'];
    $level  = $_POST['niveau'];
    if($level == "1" OR $level == 1){
        $q = "INSERT INTO categorie ( categorie ,  level  ) VALUES ('$categorie', '$level' );";
    }else{
        $parent = $_POST['parent'] ;
        $q = "INSERT INTO categorie ( categorie ,  level  , parent ) VALUES ('$categorie' , '$level' , '$parent');";
    }
    

    addToLog($q);
    $r = mysqli_query($dbc, $q);
    if ($r) {
         echo "<script>alert('categorie ajouter $categorie');</script>";
     } else {
        $err = mysqli_error($dbc) ;
         echo mysqli_error($dbc);
     }

     $target_dir = "img/img_categorie/";
     $target_file = $target_dir . basename($_FILES["img"]["name"]);
     $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
     $inserted_id =  mysqli_insert_id($dbc) ;
     $target_file = $target_dir . $inserted_id . "." . $imageFileType;
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
}
include('includes/header.html');
?>

<div class="col-md-offset-5">
    <h2>Ajouter une Categorie</h2><br>
</div>
<div class="col-md-10 col-md-offset-1">


    <form class="form-horizontal" method="post" action="ajoute_categorie.php" enctype="multipart/form-data"> 
        <div class="form-group">
            <label class="control-label col-md-2" for="four">Categorie:</label>
            <div class="col-md-9">
                <input type="text" class="form-control" id="categorie" placeholder="categorie" name="categorie"
                    required>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-2" for="wilaya">Niveau :</label>
            <div class="col-md-9">
                <input onChange="change()" type="number" class="form-control" id="niveau" placeholder="Niveau"
                    name="niveau" required>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-2" for="wilaya">Parent</label>
            <div class="col-md-9">
                <select id="parent" name="parent" class="form-control form-control-sm">

                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="img">Image Categorie </label>
            <div class="col-md-9">
                <input type="file" name="img" class="form-control-file" id="img">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-md-9">
                <input type="hidden" name="submitted" value="TRUE">
                <button type="submit" class="btn btn-default">Ajoute Categorie </button>
            </div>
        </div>
    </form>
</div>


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
function change() {
    const level = $("#niveau").val();
    console.log(level)
    const selected = option.filter((op) => {
        return op.level == level - 1;
    })
    const selectElement = $("#parent")
    selectElement.empty()
    $.each(selected, function(index, cat) {
        $('#parent').append($('<option/>', {
            value: cat.cle,
            text: cat.categorie
        }));
    });
}
</script>

<?php
include('includes/footer.html');
?>