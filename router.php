<?php

	// preparing url
	$url=explode("/",reset(explode("?",urldecode($_SERVER["REQUEST_URI"]))));
	array_shift($url);

	// selecting page based on url
	switch(strtolower($url[0])){ // level 1
		case "api":
			switch (strtolower($url[1])) { // level 2 at api
				case 'login':die(include"api/login.php");break; //  : /api/login
				case 'register':die(include"api/register.php");break; // : /api/register
				case 'logout': // /api/logout
					session_destroy();
					$user=null;
					die();break;
				default:die(json_encode(array("status"=>"url_error")));break; // : /api/*
			}
		default:$req_page="pages/404/controller.php";break; // : /*
	}

	// running selected page
	$is_ajax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH']=="XMLHttpRequest"); // is ajax request
	include ($is_ajax ? $req_page : "master/controller.php");
?>
