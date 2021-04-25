<?php
require_once('includes/header.html');
if (!isset($_SESSION['prenom'])) {
    $url = $_SERVER['REQUEST_URI'];
    ?>
<script type="text/javascript">
location.href = 'login.php?continue=<?= $url ?>';
</script>
<?php
}
if(isset($_GET['restored'])){?>
<script type="text/javascript">
window.open('', '_parent', '');
window.close();
</script>
<?php }
$page_title = 'Vente';
require_once('includes/function_inc.php');
require_once('connect_hanout.php');
?>
<div class="col-md-12">
    <h1 align="center"><?= NOM ?> Vente</h1><br>
    <div class="col-md-3"></div>
    <div class="col-md-9">
        <form>
            <div class="input-group row">
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Categorie" onblur="getCategorie()"
                        id="categorie">
                </div>

                <div class="col-md-6">
                    <input type="number" class="form-control" placeholder="Niveau" id="niveau" onblur="getCategorie()">
                </div>
            </div>
        </form>
    </div>

    <br><br><br>
    <div id='articleTableDiv'>
        <?php
        if (isset($_GET['searchBarCode']) &&!empty($_GET['searchBarCode'])){
       ?>
        <table class="table table-hover" style="table-layout:fixed;" id="articleTable">
            <?php
            while ($articles = mysqli_fetch_assoc($r)) {
              ?>
            <?php } ?>
    </div>
    <?php
    }
    ?>
    </table>
</div>
<?php
require_once('includes/footer.html');
?>
<script type="text/javascript">
function getCategorie() {
    var niveau = parseInt(document.getElementById('niveau').value);
    var categorie = document.getElementById('categorie').value;
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
    xmlhttp.open("GET", "get_categorie.php?niveau=" + niveau + "&categorie=" + categorie, true);
    xmlhttp.send();
}

function modif(cat){
    const categorie = document.getElementById('categorie' + cat).value ;
    var level = parseInt(document.getElementById('level' + cat).value);
    var parent  = parseInt(document.getElementById('parent' + cat).value);
    var fd = new FormData();
    var files = $(`#img${cat}`)[0].files;

    // Check file selected or not
    if(files.length > 0 ){
    fd.append('img',files[0]);}
    fd.append('categorie' , categorie)
    fd.append('level' , level)
    fd.append('parent' , parent)
    fd.append('cle' , cat)
    fd.append('submited' , true)
    $.ajax({
      url: 'get_categorie.php',
      type: 'post',
      data: fd,
      contentType: false,
      processData: false,
      success: function (output) {
                  alert(output);
              },error: function (r) {
                  alert(r);
              }
   });

$(`#dialog${cat}`).modal('toggle')
getCategorie()
}

    function suprimerCategorie(cat) {
            const url =  "&cle="+ cat +'&delete=' + "true"
          $.ajax({ 
              url: 'get_categorie.php',
              type: 'get' ,
              data: url ,
              success: function (output) {
                  alert("categorie suprimer");
                  console.log(output)
              },error: function () {
                  alert('erreur ');
              }
          });
          $(`#dialog${cat}`).modal('toggle')
          getCategorie()
        
    }

getCategorie()
</script>