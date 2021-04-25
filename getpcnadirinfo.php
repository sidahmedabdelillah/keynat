<?php
if(isset($_GET['url'])){
  $url=$_GET['url'];
}
//$url="https://www.ouedkniss.com/asus-rog-gl752v-i7-6e-17-3-gtx-960m-4-oran-mascara-sidi-bel-abbes-alger-centre-mostaganem-algerie-informatique-d10441393";
$html = file_get_contents( $url);

libxml_use_internal_errors( true);
$doc = new DOMDocument;
$doc->loadHTML( $html);
$xpath = new DOMXpath( $doc);

// A name attribute on a <div>???
$node = $xpath->query( '//div[@id="GetDescription"]')->item( 0);
$text= $node->textContent; // This will print **GET THIS TEXT**

preg_match('/Marque et modèle :(.*?)Caractéristique /i', $text, $match);
$marque=$match[1];
preg_match('/Ram :(.*?)>/i', $text, $match);
$ram=$match[1];
preg_match('/Système :(.*?)>/i', $text, $match);
$system=$match[1];
preg_match('/Processeur:(.*?)>/i', $text, $match);
$processeur=$match[1];
preg_match('/Carte graphique :(.*?)>/i', $text, $match);
$carte_graphique=$match[1];
preg_match('/Disque dur :(.*?)>/i', $text, $match);
$dd= $match[1];
preg_match('/Taille et Type d’écran :(.*?)>/i', $text, $match);
$ecran=$match[1];
preg_match('/Batterie :(.*?)>/i', $text, $match);
$batterie=$match[1];
preg_match('/Webcam intégré :(.*?)>/i', $text, $match);
$webcam=$match[1];
preg_match('/Lecteur CD\/DVD graveur :(.*?)>/i', $text, $match);
$graveur=$match[1];
preg_match('/Lecteur carte mémoire SD :(.*?)>/i', $text, $match);
$sd= $match[1];
preg_match('/Lecteur carte SIM :(.*?)>/i', $text, $match);
$sim= $match[1];
preg_match('/Lecteur d’empreinte :(.*?)>/i', $text, $match);
$empreinte= $match[1];
preg_match('/Wi Fi \/ Bluetooth :(.*?)>/i', $text, $match);
$wifi= $match[1];
preg_match('/Port USB :(.*?)>/i', $text, $match);
$usb= $match[1];
preg_match('/Port HDMI :(.*?)>/i', $text, $match);
$hdmi= $match[1];
preg_match('/Port VGA :(.*?)>/i', $text, $match);
$vga= $match[1];
preg_match('/Poids :(.*?)>/i', $text, $match);
$poids= $match[1];
preg_match('/Couleur :(.*?)>/i', $text, $match);
$couleur= $match[1];
preg_match('/Etat :(.*?)Accessoires/i', $text, $match);
$etat= $match[1];


$node = $xpath->query( '//span[@itemprop="price"]')->item( 0);
$text= $node->textContent; // This will print **GET THIS TEXT**
preg_match('/(.*?)DA/i', $text, $match);
$prix=$match[1];
?>

<div class="form-group">
  <label class="control-label col-md-2" for="nom">Marque:</label>
  <div class="col-md-9">
    <input class="form-control" id="marque" placeholder="Marque" name="marque" required value="<?= $marque ?>">
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-2" for="telephone">Processeur:</label>
  <div class="col-md-9">
    <input class="form-control" id="processeur" placeholder="Processeur" name="processeur" required value="<?= $processeur ?>">
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-2" for="telephone">Prix:</label>
  <div class="col-md-9">
    <input class="form-control" id="prix" placeholder="Prix" name="prix" required value="<?= $prix ?>">
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-2" for="marque">Carte Graphique:</label>
  <div class="col-md-9">
    <input type="text" class="form-control" id="carte_graphique" placeholder="Carte Graphique" name="carte_graphique" required value="<?= $carte_graphique ?>">
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-2" for="problem">Ram:</label>
  <div class="col-md-9">
    <input class="form-control" id="ram" placeholder="Ram" name="ram" required value="<?= $ram ?>">
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-2" for="remarque">Disque Dur:</label>
  <div class="col-md-9">
    <input class="form-control" id="dd" placeholder="Disque Dur" name="dd" value="<?= $dd ?>">
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-2" for="remarque">Ecran:</label>
  <div class="col-md-9">
    <input class="form-control" id="ecran" placeholder="Ecran" name="ecran" value="<?= $ecran ?>">
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-2" for="remarque">Batterie:</label>
  <div class="col-md-9">
    <input class="form-control" id="batterie" placeholder="Batterie" name="batterie" value="<?= $batterie ?>">
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-2" for="graveur">Lecteur CD/DVD graveur:</label>
  <div class="col-md-9">
    <input class="form-control" id="graveur" placeholder="Graveur" name="graveur" value="<?= $graveur ?>">
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-2" for="graveur">Lecteur carte mémoire SD:</label>
  <div class="col-md-9">
    <input class="form-control" id="sd" placeholder="Lecteur carte mémoire SD" name="sd" value="<?= $sd ?>">
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-2" for="graveur">Lecteur carte SIM:</label>
  <div class="col-md-9">
    <input class="form-control" id="sim" placeholder="Lecteur carte SIM" name="sim" value="<?= $sim ?>">
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-2" for="graveur">Lecteur d’empreinte:</label>
  <div class="col-md-9">
    <input class="form-control" id="empreinte" placeholder="Lecteur d’empreinte" name="empreinte" value="<?= $empreinte ?>">
  </div>
</div>


<div class="form-group">
  <label class="control-label col-md-2" for="poids">Poids:</label>
  <div class="col-md-9">
    <input class="form-control" id="poids" placeholder="Poids" name="poids" value="<?= $poids ?>">
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-2" for="remarque">Etat:</label>
  <div class="col-md-9">
    <input class="form-control" id="etat" placeholder="Etat" name="etat" value="<?= $etat ?>">
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-2" for="remarque">Webcam:</label>
  <div class="col-md-9">
    <input class="form-control" id="webcam" placeholder="Webcam" name="webcam" value="<?= $webcam ?>">
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-2" for="remarque">Wi Fi / Bluetooth:</label>
  <div class="col-md-9">
    <input class="form-control" id="wifi" placeholder="Wi Fi / Bluetooth" name="wifi" value="<?= $wifi ?>">
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-2" for="remarque">Port HDMI:</label>
  <div class="col-md-9">
    <input class="form-control" id="hdmi" placeholder="Port HDMI" name="hdmi" value="<?= $hdmi ?>">
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-2" for="remarque">Port VGA:</label>
  <div class="col-md-9">
    <input class="form-control" id="vga" placeholder="Port VGA" name="vga" value="<?= $vga ?>">
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-2" for="remarque">Port USB:</label>
  <div class="col-md-9">
    <input class="form-control" id="usb" placeholder="Port USB" name="usb" value="<?= $usb ?>">
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-2" for="remarque">Couleur:</label>
  <div class="col-md-9">
    <input class="form-control" id="couleur" placeholder="Couleur" name="couleur" value="<?= $couleur ?>">
  </div>
</div>
