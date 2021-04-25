<?php
require_once('includes/function_inc.php');
require_once('connect_hanout.php');
$q1="select * from article";
$r1=mysqli_query($dbc,$q1);
while($row=mysqli_fetch_assoc($r1)){
  $id=$row['cle'];
$q="SELECT * FROM `achat` WHERE `article_id`=$id ORDER BY `date` DESC limit 3";
$r=mysqli_query($dbc,$q);
if(mysqli_num_rows($r)>0){
  $array=[];
  while($row=mysqli_fetch_assoc($r)){
    $array[]=$row;
  }
  usort($array, function ($item1, $item2) {
      return $item1['prix_achat_fournisseur'] <=> $item2['prix_achat_fournisseur'];
  });
  $four1_achat=@$array['0']['achat_id'];
  $four2_achat=@$array['1']['achat_id'];
  $four3_achat=@$array['2']['achat_id'];


  for ($i=1; $i <= 3 ; $i++) {
    if($i==1){
      $four_achat=$four1_achat;
    }elseif ($i==2) {
      $four_achat=$four2_achat;
    }elseif ($i==3) {
      $four_achat=$four3_achat;
    }
    $q="select * from m_four where article_id=$id and n_mf=$i";
    $r=mysqli_query($dbc,$q);
    if($r){
      if(mysqli_num_rows($r)>0){
        $q="UPDATE `m_four` SET `achat_id`=$four_achat WHERE article_id=$id and n_mf=$i";
        $r=mysqli_query($dbc,$q);
        if($r){

        }else{
          echo mysqli_error($dbc);
        }
      }else{
        $q="INSERT INTO `m_four`(`article_id`, `achat_id`, `n_mf`) VALUES ('$id','$four_achat','$i')";
        $r=mysqli_query($dbc,$q);
        if($r){

        }else{
          echo mysqli_error($dbc);
        }
      }
    }else{
      echo mysqli_error($dbc);
  }
  }
}

}
?>
