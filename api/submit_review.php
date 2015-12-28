<?php
  if(!$user || $user->type!="citizen") die(json_encode(array("status"=>"login_required")));
  if(isset($_POST["elected"]) && isset($_POST["text"])){
    $elected=new elected($_POST["elected"]);
    if(!$elected->isvalid) die(json_encode(array("status"=>"invalid_elected")));
    review::create($_POST["text"], $elected, session::last_general_meeting());
    die(json_encode(array("status"=>"success")));
  }else die(json_encode(array("status"=>"parameter_required")));
?>
