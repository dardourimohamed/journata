<?php
$rslt=array();
foreach (elected::must_be_present() as $e) {
  $rslt[]=array(
    "id"=>$e->id,
    "name"=>$e->name,
    "state"=>$e->state,
    "image"=>$e->image
  );
}
die(json_encode($rslt));
?>
