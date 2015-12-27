<?php

	class gf {

		public static function utf8ize($mixed) {
		    if (is_array($mixed)) {
		        foreach ($mixed as $key => $value) {
		            $mixed[$key] = gf::utf8ize($value);
		        }
		    } else if (is_string ($mixed)) {
		        return utf8_encode($mixed);
		    }
		    return $mixed;
		}

		public static function getClientIP(){

			if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)){
		        return  $_SERVER["HTTP_X_FORWARDED_FOR"];
		    }else if (array_key_exists('REMOTE_ADDR', $_SERVER)) {
		        return $_SERVER["REMOTE_ADDR"];
		    }else if (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
		        return $_SERVER["HTTP_CLIENT_IP"];
		    }
		    return null;
		}

		public static function generate_guid(){
			if (function_exists('com_create_guid')){
	            return com_create_guid();
	        }else{
	            mt_srand((double)microtime()*10000);
	            $charid = strtoupper(md5(uniqid(rand(), true)));
	            $uuid =  substr($charid, 0, 8).substr($charid, 8, 4).substr($charid,12, 4).substr($charid,16, 4).substr($charid,20,12);
	            return $uuid;
	        }
		}

		public static function randomString($length = 10) {
		    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ$£*-+=)_-(&!:?;.,<>#~[]@|{}';
		    $charactersLength = strlen($characters);
		    $randomString = '';
		    for ($i = 0; $i < $length; $i++) {
		        $randomString .= $characters[rand(0, $charactersLength - 1)];
		    }
		    return $randomString;
		}

	}
?>
