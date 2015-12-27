<?php
  if(isset($_POST["username"]) && isset($_POST["password"])){
    $response = citizen::login($_POST["username"], $_POST["password"], gf::getClientIP());
    if($response instanceof citizen){
      $_SESSION["user"]=serialize($response);
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
        $_SESSION["user"]=serialize($response);
        die(json_encode(array(
          "status" => "success",
          "params" => array(
            "displayname" => $response->displayname,
            "type" => "iwatchadmin",
          )
        )));
      }elseif($response["status"]=="username_error"){
        $response = elected::login($_POST["username"], $_POST["password"], gf::getClientIP());
        if($response instanceof elected){
          $_SESSION["user"]=serialize($response);
          die(json_encode(array(
            "status" => "success",
            "params" => array(
              "displayname" => $response->displayname,
              "type" => "elected",
            )
          )));
        }else die(json_encode($response));
      }else die(json_encode($response));
    }else die(json_encode($response));
  }else die(json_encode(array("status", "parameter_required")));
?>
