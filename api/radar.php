<?php
  if(isset($_POST["elected"]) && isset($_POST["latitude"]) && isset($_POST["longitude"])){
    $elected = new elected($_POST["elected"]);
    if(!$elected->isvalid) die(json_encode(array("status"=>"invalid_elected")));
    if(!$elected->must_be_present) die(json_encode(array("status"=>"elected_out_of_work")));
    $elected->radar($_POST["latitude"], $_POST["longitude"]);
    die(json_encode(array("status"=>"success")));
  }else die(json_encode(array("status"=>"parameter_required")));
?>
