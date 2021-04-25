<?php
session_start();
if (!isset($_SESSION['prenom'])) {
    $url = $_SERVER['REQUEST_URI'];
  ?><script type="text/javascript">location.href='login.php?continue=<?= $url ?>';</script><?php
}
$page_title = 'Vente';
require_once('includes/function_inc.php');
siPermis($_SESSION['type'],basename(__FILE__, '.php'));
require_once('connect_hanout.php');
require_once('includes/header.html');
?>
<div class="col-md-12">
    <h1 align="center"><?= NOM ?> Vente + article en Quantité Zéro</h1><br>
    <div class="col-md-9">
        <form>
            <div class="input-group">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Recherche par Categorie" id="searchBar4"
                           onblur="getArticles();">
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Recherche par Categorie" id="searchBar5"
                           onblur="getArticles();">
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Recherche par Categorie" id="searchBar6"
                           onblur="getArticles();">
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
    <div class="col-md-3">
        <form>
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Code" name="searchBarCode" id="searchBarCode"
                       onkeyup="getArticles();" autofocus>
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
    <div id='articleTableDiv'>
    </table>
</div>
<?php
require_once('includes/footer.html');
?>
<script type="text/javascript">
    function getArticles() {
        var article4 = document.getElementById('searchBar4').value;
        var article5 = document.getElementById('searchBar5').value;
        var article6 = document.getElementById('searchBar6').value;
        var cle = parseInt(document.getElementById('searchBarCode').value);
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else { // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("articleTableDiv").innerHTML = this.responseText;
            }
        }
        xmlhttp.open("GET", "1_24articles.php?zero&des5=" + article5 + "&des4=" + article4+ "&des6=" + article6+ "&cle=" + cle, true);
        xmlhttp.send();
    }
</script>
    
