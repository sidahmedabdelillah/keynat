<?php
session_start();
if (!isset($_SESSION['prenom'])) {
    $url=$_SERVER['REQUEST_URI'];
header("location:login.php?continue=$url");
}
require_once('includes/function_inc.php');
$page_title = "Statistique";
require_once('includes/header.html');
require_once('connect_hanout.php');
?>
<br>
<div class="form-actions">
    <a href="statistique/vente_jour.php" target="stat_frame">
        <button class="btn btn-default">Vente par jour</button>
    </a>
    <a href="statistique/vente_semaine.php" target="stat_frame">
        <button class="btn btn-default">Vente par Semaine</button>
    </a>
    <a href="statistique/vente_mois.php" target="stat_frame">
        <button class="btn btn-default">Vente par mois</button>
    </a><br><br>
    <a href="statistique/gain_jour.php" target="stat_frame">
        <button class="btn btn-default">Gain par jour</button>
    </a>
    <a href="statistique/gain_semaine.php" target="stat_frame">
        <button class="btn btn-default">Gain par semaine</button>
    </a>
    <a href="statistique/gain_mois.php" target="stat_frame">
        <button class="btn btn-default">Gain par mois</button>
    </a><br><br>
    <a href="statistique/capital_jour.php" target="stat_frame">
        <button class="btn btn-default">Capital par jour</button>
    </a>
    <a href="statistique/capital_journee.php" target="stat_frame">
        <button class="btn btn-default">Capital par journée</button>
    </a>
    <a href="statistique/capital_mois.php" target="stat_frame">
        <button class="btn btn-default">Capital par mois</button>
    </a>
    <a href="statistique/capital_total.php" target="stat_frame">
        <button class="btn btn-default">Capital Total</button>
    </a><br><br>
    <a href="statistique/perte_jour.php" target="stat_frame">
        <button class="btn btn-default">Perte par Jour</button>
    </a>
    <a href="statistique/perte_mois.php" target="stat_frame">
        <button class="btn btn-default">Perte par mois</button>
    </a><br><br>
    <a href="statistique/nbr_article.php" target="stat_frame">
        <button class="btn btn-default">Nbr Atricle</button>
    </a>
    <a href="statistique\sum_qnt.php" target="stat_frame">
        <button class="btn btn-default">Somme Quantite</button>
    </a>
    <br><br>
    <a href="statistique/courbe_caisse_qte_manque.php" target="stat_frame">
        <button class="btn btn-default">Courbe Caisse / Quantité Manquante</button>
    </a>
    <a href="statistique/courbe_caisse_seuil1.php" target="stat_frame">
        <button class="btn btn-default">Courbe Caisse / Seuil1</button>
    </a>
    <a href="statistique/courbe_nbr_article_seuil.php" target="stat_frame">
        <button class="btn btn-default">Courbe Nbr Article / Seuil1</button>
    </a>
    <br><br>
    <a href="statistique/courbe_salaire_vendeur.php" target="stat_frame">
        <button class="btn btn-default">Courbe Salaire des vendeurs</button>
    </a>
</div>
<div class="embed-responsive embed-responsive-16by9">
    <iframe class="embed-responsive-item" src="" allowfullscreen name="stat_frame"></iframe>
</div>
<?php include_once('includes/footer.html'); ?>
