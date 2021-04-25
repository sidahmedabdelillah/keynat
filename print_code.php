
    <script type="text/javascript" src="js/JsBarcode.all.min.js"></script>
    <?php
    // header('Content-Type: image/png');
    // header('Content-Disposition: inline; filename="barcode.png"');
    function formatNumber($num,$length){
      $r= "".$num;
      while(strlen($r) < $length){
        $r='0'.$r;
      }
      return $r;
    }
     ?>
  <svg class="barcode"
    jsbarcode-format="auto"
    jsbarcode-value=<?= formatNumber(165,10) ?>
    jsbarcode-textmargin="0"
    jsbarcode-fontoptions="bold">
  </svg>
      <script type="text/javascript">
      JsBarcode(".barcode", "1234", {
  format: "code128",
  lineColor: "#000",
  width:4,
  height:100,
  displayValue: false
});
      </script>
