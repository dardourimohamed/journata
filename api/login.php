<?php
  if(isset($_POST["username"]) && isset($_POST["password"])){
    $response = citizen::login($_POST["username"], $_POST["password"], gf::getClientIP());
    if($response instanceof citizen){
      die(json_encode(array(
        "status" => "success",
        "params" => array(
          "displayname" => $response->displayname,
          "type" => "citizen",
        )
      )));
    }elseif($response["status"]=="username_error"){
      $response = iwatchadmin::login($_POST["username"], $_POST["password"], gf::getClientIP());
      if($response instanceof iwatchadmin){
        die(json_encode(array(
          "status" => "success",
          "params" => array(
            "displayname" => $response->displayname,
            "type" => "iwatchadmin",
          )
        )));
      }else die(json_encode($response));
    }else die(json_encode($response));
  }else die(json_encode(array("status", "parameter_required")));
?>
