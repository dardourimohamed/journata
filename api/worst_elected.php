<?php
$rslt=array();
foreach (elected::worst() as $e) {
  $rslt[]=array(
    "id"=>$e["elected"]->id,
    "name"=>$e["elected"]->name,
    "state"=>$e["elected"]->state,
    "image"=>$e["elected"]->image,
    "score"=>$e["score"],
  );
}
die(json_encode($rslt));
?>
