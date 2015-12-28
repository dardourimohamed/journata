<?php
$rslt=array();
foreach (elected::worst() as $e) {
  $rslt[]=array(
    "id"=>$e["elected"]->id,
    "name"=>$e["elected"]->name,
    "description"=>$e["elected"]->description,
    "image"=>$e["elected"]->image,
    "score"=>$e["score"],
  );
}
die(json_encode($rslt));
?>
