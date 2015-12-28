<?php

	// preparing url
	$url=explode("/",reset(explode("?",urldecode($_SERVER["REQUEST_URI"]))));
	array_shift($url);

	// selecting page based on url
	switch(strtolower($url[0])){ // level 1 at /

		case "api":
			switch (strtolower($url[1])) { // level 2 at api
				case 'login':die(include"api/login.php");break; //  : /api/login
				case 'register':die(include"api/register.php");break; // : /api/register
				case 'logout': // /api/logout
					session_destroy();
					$user=null;
					die(json_encode(array("status"=>"success")));break;
				case 'must_be_present':die(include"api/must_be_present.php");break; //  : /api/must_be_present
				case 'top_elected':die(include"api/top_elected.php");break; //  : /api/top_elected
				case 'worst_elected':die(include"api/worst_elected.php");break; //  : /api/worst_elected
				case 'radar':die(include"api/radar.php");break; //  : /api/radar
				case 'list_elected':die(include"api/list_elected.php");break; //  : /api/list_elected
				case 'elected': //  : /api/elected
					if(isset($url[2])) $_GET["id"]=$url[2]; // accept passing id elected at level 3
					die(include"api/elected.php");
				break;
				case 'submit_top':die(include"api/submit_top.php");break; //  : /api/submit_top
				case 'submit_worst':die(include"api/submit_worst.php");break; //  : /api/submit_top

				default:die(json_encode(array("status"=>"url_error")));break; // : /api/*
			}
		break;
		default:$req_page="pages/404/controller.php";break; // : /*
	}

	// running selected page
	$is_ajax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH']=="XMLHttpRequest"); // is ajax request
	include ($is_ajax ? $req_page : "master/controller.php");
?>
