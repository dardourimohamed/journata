<?php
  if(!$user || $user->type!="iwatchadmin") die("status"=>"login_required");
  if(isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["name"]) && isset($_POST["state"]) && isset($_POST["image"])){
    $elected = elected::create($_POST["username"], $_POST["password"]);
    if($elected instanceof elected){
      $elected->name=$_POST["name"];
      $elected->state=$_POST["state"];
      $elected->image=$_POST["image"];
      die(json_encode(array("state", "success")));
    }else die(json_encode($elected));
    die(json_encode(array("status"=>"success")));
  }else die(json_encode(array("status"=>"parameter_required")));
?>
