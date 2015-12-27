<?php
  if(isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["email"]) && isset($_POST["tel"])){
    $new_user = citizen::create($_POST["username"], $_POST["password"]);
    if($new_user instanceof citizen){
      $_SESSION["user"] = serialize($new_user);
      die(json_encode(array(
        "status"=>"success",
        "params"=>array(
          "displayname"=>$new_user->displayname,
          "type"=>"citizen"
        )
      )));
    }else die(json_encode($new_user));
  }else die(json_encode(array("status", "parameter_required")));
?>
