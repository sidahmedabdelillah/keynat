<?php
session_start();
if(!isset($_SESSION['prenom'])){
    $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
require_once('connect_hanout.php');
require_once('includes/header.html');

if(isset($_POST['article'])){
  $article=$_POST['article'];
  $target_dir = "img/";
  $target_file = $target_dir . $article.".jpg";
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
  // Check if image file is a actual image or fake image
  if(isset($_POST["submit"])) {
      $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
      if($check !== false) {
          echo "File is an image - " . $check["mime"] . ".";
          $uploadOk = 1;
      } else {
          echo "File is not an image.";
          $uploadOk = 0;
      }
  }
  // Check if file already exists
  if (file_exists($target_file)) {
      echo "Sorry, file already exists.";
      $uploadOk = 0;
  }
  // Check file size
  if ($_FILES["fileToUpload"]["size"] > 500000) {
      echo "Sorry, your file is too large.";
      $uploadOk = 0;
  }
  // Allow certain file formats
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  && $imageFileType != "gif" ) {
      echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
      $uploadOk = 0;
  }
  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
      echo "Sorry, your file was not uploaded.";
  // if everything is ok, try to upload file
  } else {
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
          //echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
      } else {
          echo "Sorry, there was an error uploading your file.";
      }
  }

}
?>


    <h1 align="center"><?= NOM ?> Declarer Perte</h1><br>
    <form class="" action="modifier_image.php" method="post" enctype="multipart/form-data">
    <div class="col-md-6 text-center">
          Choisir un article:<input type="number" id="article" name="article" class="form-control" autofocus required onchange="getDesigniation('article','spanv1')";>
    </div>

    <div class="col-md-6">
      Image:<input type="file" id="fileToUpload" name="fileToUpload" class="form-control" required><br>
    </div>
        <div class="col-md-6" id='spanv1'>

        </div>
    <div class="form-actions">
      <input type="submit" class="btn btn-primary" value="Declarer">
    </div>
    </div>
</form>


    <?php
    require_once('includes/footer.html');
    ?>
<script type="text/javascript">

    function getDesigniation(article,span) {
        var article1 = document.getElementById(article).value;
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        } else { // code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function() {
            if (this.readyState==4 && this.status==200) {
                document.getElementById(span).innerHTML=this.responseText;
            }
        }
        xmlhttp.open("GET","article_designiation.php?id="+article1,true);
        xmlhttp.send();
    }
</script>
