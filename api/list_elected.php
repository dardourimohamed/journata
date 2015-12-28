<?php
  $list=array();
  foreach (elected::get_all() as $e) {
    $list[]=array(
      "id"=>$e->id,
      "name"=>$e->name,
      "state"=>$e->state,
      "image"=>$e->image,
      "score"=>($e->score?$e->score:0)
    );
  }
  die(json_encode($list));
?>
