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
				case 'submit_worst':die(include"api/submit_worst.php");break; //  : /api/submit_worst
				case 'approve_dont_pay':die(include"api/approve_dont_pay.php");break; //  : /api/approve_dont_pay
				case 'dont_pay_suggestion':die(include"api/dont_pay_suggestion.php");break; //  : /api/dont_pay_suggestion
				case 'elected_add':die(include"api/elected_add.php");break; //  : /api/elected_add
				case 'elected_delete':die(include"api/elected_delete.php");break; //  : /api/elected_delete
				case 'submit_review':die(include"api/submit_review.php");break; //  : /api/submit_review

				default:die(json_encode(array("status"=>"url_error")));break; // : /api/*
			}
		break;
		default:die(json_encode(array("status"=>"url_error")));break; // : /*
	}
?>
