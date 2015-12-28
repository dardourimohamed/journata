<?php
  $rslt=array();
  foreach (elected::top() as $e) {
    $rslt[]=array(
      "id"=>$e["elected"]->id,
      "name"=>$e["elected"]->name,
      "state"=>$e["elected"]->state,
      "image"=>$e["elected"]->image,
      "score"=>($e["score"]?$e["score"]:0),
    );
  }
  die(json_encode($rslt));
?>
