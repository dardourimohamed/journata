<?php
  $list=array();
  foreach (elected::get_all() as $e) {
    $list[]=array(
      "id"=>$e->id,
      "displayname"=>$e->displayname,
      "description"=>$e->description,
      "image"=>$e->image,
      "score"=>$e->score
    );
  }
  die(json_encode($list));
?>
