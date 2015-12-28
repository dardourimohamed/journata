<?php
$rslt=array();
foreach (elected::worst() as $e) {
  $rslt[]=array(
    "id"=>$e["elected"]->id,
    "displayname"=>$e["elected"]->displayname,
    "description"=>$e["elected"]->description,
    "image"=>$e["elected"]->image,
    "score"=>$e["score"],
  );
}
die(json_encode($rslt));
?>
