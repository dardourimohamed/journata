<?php
  if(!$user || $user->type!="citizen") die(json_encode(array("status"=>"login_required")));
  if(isset($_POST["elected"])){
    $elected=new elected($_POST["elected"]);
    if(!$elected->isvalid) die(json_encode(array("status"=>"invalid_elected")));
    $elected->set_worst($user, session::last_general_meeting());
    die(json_encode(array("status"=>"success")));
  }
?>
