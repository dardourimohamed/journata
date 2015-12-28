<?php
  if(!$user || $user->type!="iwatchadmin") die("status"=>"login_required");
  if(isset($_POST["elected"]) && isset($_POST["text"])){
    $elected=new elected($_POST["elected"]);
    if(!$elected->isvalid) die(json_encode(array("status"=>"invalid_elected")));
    $elected->delete();
    die(json_encode(array("status"=>"success")));
  }else die(json_encode(array("status"=>"parameter_required")));
?>
