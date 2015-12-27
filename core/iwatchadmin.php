<?php
	class iwatchadmin{

		private $id;

		public function __construct($nid){
			$this->id = $nid;
		}

		public function __set($name,$value){
			global $db;
			if ($this->id != NULL) {
				switch($name){
					case "password":
						$db->query("update iwatchadmin set ".$name."=".($value===null?"NULL":"ENCRYPT('".$db->real_escape_string($value)."')").", reset_password_token=NULL where (id='".$this->id."')");
					break;
					default :
						$db->query("update iwatchadmin set ".$name."=".($value===null?"NULL":"'".$db->real_escape_string($value)."'")." where (id='".$this->id."')");
					break;
				}
			}
		}

		public function __get($name){
			global $db;
			if ($this->id != NULL) {
				switch($name){
					case "id":
						return $this->id;
					break;
					case "isvalid":
						$q=$db->query("select count(*) from iwatchadmin where (id='".$this->id."')");
						$r=$q->fetch_row();
						return $r[0]==1;
					break;
					case 'displayname':
						return "@".$this->username;
					break;
					default:
						$q=$db->query("select ".$name." from iwatchadmin where (id='".$this->id."')");
						$r=$q->fetch_row();
						return $r[0];
					break;
				}
			}else{
				return NULL;
			}
		}

		public static function username_exists($username){
			global $db;
			$q=$db->query("select count(username) from iwatchadmin where (username='".$username."')");
			$r=$q->fetch_row();
			return $r[0]>0;
		}

		public static function create($username,$password){
			global $db;
			if(iwatchadmin::username_exists($username)) return array("status"=>"username_exists");
			$db->query("insert into iwatchadmin (username,password) values('".$db->real_escape_string($username)."', ENCRYPT('".$db->real_escape_string($password)."'))");
			return new iwatchadmin($db->insert_id);
		}

		public function delete(){
			global $db;
			$db->query("delete from iwatchadmin where (id='".$this->id."')");
		}

		public static function login($username, $password, $ip){
			global $db;

			// security params
			$allowed_attempts = 5;
			$waiting_minutes = 15;

			if( $ip == NULL ) return array("status"=>"restricted_host");

			$q=$db->query("select id, password from iwatchadmin where (username='".$username."')");
			if($q->num_rows==0){
				return array("status"=>"username_error");
			}else{
				$r=$q->fetch_row();
				$db->query("delete from restricted_ip where (TIMESTAMPDIFF(MINUTE,restriction_time,NOW())>=".$waiting_minutes.")");

				$ch_ip=$db->query("select attempts, TIMESTAMPDIFF(MINUTE,NOW(),DATE_ADD(restriction_time, INTERVAL ".$waiting_minutes." MINUTE)) from restricted_ip where (ip_address='".$ip."')");

				$ch_r=$ch_ip->fetch_row();
				$attempts = ($ch_ip->num_rows > 0 ? ($ch_r[0] + 1) : 1);

				if( $attempts > $allowed_attempts ) return array("status" => "waiting_restriction_time", "remaining_time" => $ch_r[1]);
				else {

					if (!hash_equals($r[1], crypt($password, $r[1]))) {
						$db->query("INSERT INTO restricted_ip (ip_address) values('".$ip."') ON DUPLICATE KEY UPDATE attempts=attempts+1, restriction_time=NOW()");
						$ch_ip=$db->query("select TIMESTAMPDIFF(MINUTE,NOW(),DATE_ADD(restriction_time, INTERVAL ".$waiting_minutes." MINUTE)) from restricted_ip where (ip_address='".$ip."')");
						$ch_r=$ch_ip->fetch_row();
						if( $attempts >= $allowed_attempts ) return array("status" => "waiting_restriction_time", "remaining_time" => $ch_r[0]);
						return array("status" => "password_error", "remaining_attempts" => ($allowed_attempts - $attempts));
					} else {
						$db->query("delete from restricted_ip where (ip_address='".$ip."')");
						return new iwatchadmin($r[0]);
					}
				}
			}
		}
	}
?>
