<?php
  if($user) die(json_encode(array("status"=>"connected", "params"=>array(
    "displayname"=>$user->displayname,
    "type"=>$user->type
  ))));
  else die(json_encode(array("status"=>"not_connected")));
?>
