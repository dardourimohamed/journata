<?php
if(!$user || $user->type!="iwatchadmin") die(json_encode(array("status"=>"login_required")));
  if(isset($_POST["elected"])){
    $elected=new elected($_POST["elected"]);
    if(!$elected->isvalid) die(json_encode(array("status"=>"invalid_elected")));
    $elected->dont_pay();
    die(json_encode(array("status"=>"success")));
  }else die(json_encode(array("status"=>"parameter_required")));
?>
