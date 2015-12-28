<?php
  if(isset($_GET["id"])){
    $elected = new elected($_GET["id"]);
    if(!$elected->isvalid) die(json_encode(array("status"=>"invalid_parameter")));
    $committees=array();
    $absences=array();
    $reviews=array();
    $radars=array();
    $dont_pay=array();
    foreach ($elected->committees as $c) {
      $committees[]=array("id"=>$c->id, "name"=>$c->name);
    }
    foreach ($elected->absences as $a) {
      $session=$a->session;
      $committee=$session->committee;
      $absences[]=array(
        "id"=>$a->id,
        "certified"=>($a->certified?true:false),
        "committee"=>array("id"=>$committee->id, "name"=>$committee->name),
        "from"=>$session->start,
        "to"=>$session->end,
        "cause"=>($a->cause_approved?$a->cause:false)
      );
    }
    foreach ($elected->reviews as $r) {
      $session = $r->session;
      $committee = $session->committee;
      $reviews[]=array(
        "id"=>$r->id,
        "committee"=>array("id"=>$committee->id, "name"=>$committee->name),
        "session"=>array("from"=>$session->from, "to"=>$session->to),
        "text"=>$c->name
      );
    }
    foreach ($elected->radars as $r) {
      $radars[]=array(
        "id"=>$r->id,
        "geolocation"=>array(
          "latitude"=>$r->latitude,
          "longitude"=>$r->longitude
        ),
        "time"=>$r->time
      );
    }
    foreach ($elected->dont_pay as $d) {
      if($d->approved) $dont_pay[]=array(
        "id"=>$d->id,
        "date"=>$d->date,
        "count"=>$d->count
      );
    }
    die(json_encode(array(
      "status"=>"success",
      "elected"=>array(
        "id"=>$elected->id,
        "displayname"=>$elected->displayname,
        "description"=>$elected->description,
        "image"=>$elected->image,
        "score"=>$elected->score,
        "count_absences"=>$elected->count_total_absence,
        "count_presences"=>$elected->count_total_presence,
        "count_sessions"=>$elected->count_total_sessions,
        "must_be_present"=>($elected->must_be_present?true:false),
        "committees"=>$committees,
        "absences"=>$absences,
        "reviews"=>$reviews,
        "radars"=>$radars,
        "dont_pays"=>$dont_pay
      )
    )));
  }else die(json_encode(array("status", "parameter_required")));
?>
