<?php
	class elected{

		private $id;

		public function __construct($nid){
			$this->id = $nid;
		}

		public function __set($name,$value){
			global $db;
			if ($this->id != NULL) {
				switch($name){
					case "password":
						$db->query("update elected set ".$name."=".($value===null?"NULL":"ENCRYPT('".$db->real_escape_string($value)."')").", reset_password_token=NULL where (id='".$this->id."')");
					break;
					default :
						$db->query("update elected set ".$name."=".($value===null?"NULL":"'".$db->real_escape_string($value)."'")." where (id='".$this->id."')");
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
							$q=$db->query("select count(*) from elected where (id='".$this->id."')");
							$r=$q->fetch_row();
							return $r[0]==1;
					break;
					case 'displayname':
							return "@".$this->username;
					break;
					case 'score':
							$q=$db->query("select sum(toporworst) from top_worst where (id_elected='".$this->id."')");
							$r=$q->fetch_row();
							return $r[0];
					break;
					case 'committees':
							$list=array();
							$q=$db->query("select elected_committee.id_committee from elected_committee, committee where (id_elected='".$this->id."' and elected_committee.id_committee=committee.id)");
							while($r=$q->fetch_row()){
								$list[]=new committee($r[0]);
							}
							return $list;
					break;
					case 'absences':
							$list=array();
							$q=$db->query("select id from absence where (id_elected='".$this->id."')");
							while($r=$q->fetch_row()){
								$list[]=new absence($r[0]);
							}
							return $list;
					break;
					case 'reviews':
							$list=array();
							$q=$db->query("select id from reviews where (id_elected='".$this->id."')");
							while($r=$q->fetch_row()){
								$list[]=new review($r[0]);
							}
							return $list;
					break;
					case 'dont_pay':
							$list=array();
							$q=$db->query("select id from dont_pay where (id_elected='".$this->id."')");
							while($r=$q->fetch_row()){
								$list[]=new dont_pay($r[0]);
							}
							return $list;
					break;
					case 'radars':
							$list=array();
							$q=$db->query("select id from radar where (id_elected='".$this->id."')");
							while($r=$q->fetch_row()){
								$list[]=new radar($r[0]);
							}
							return $list;
					break;
					case 'count_total_absence':
							$q=$db->query("select count(*) from absence where (id_elected='".$this->id."')");
							$r1=$q->fetch_row();
							return $r[0];
					break;
					case 'count_total_presence':
							$q=$db->query("select count(session.id) from elected_committee, session, absence where (elected_committee.id_elected='".$this->id."' and absence.id_elected='".$this->id."' and elected_committee.id_committee=session.id_committee and session.id<>absence.id_session)");
							$r=$q->fetch_row();
							return $r[0];
					break;
					case 'count_total_sessions':
							$q=$db->query("select count(session.id) from elected_committee, session where (elected_committee.id_elected='".$this->id."' and elected_committee.id_committee=session.id_committee)");
							$r1=$q->fetch_row();
							return $r[0];
					break;
					case 'must_be_present':
							$q=$db->query("select count(elected.id) from session, elected_committee, elected where (session.start<NOW() and session.end>NOW() and session.id_committee=elected_committee.id_committee and elected_committee.id_elected='".$this->id."')"));
							$r=$q->fetch_row();
							return $r[0]>0;
					break;
					default:
							$q=$db->query("select ".$name." from elected where (id='".$this->id."')");
							$r=$q->fetch_row();
							return $r[0];
					break;
				}
			}else{
					return NULL;
			}
		}

		public function radar($latitude, $longitude){
				global $db;
				$db->query("insert into radar (id_elected, latitude, longitude, time) values ('".$this->id."', '".$latitide."', '".$longitude."', NOW())");
				return new radar($db->insert_id);
		}

		public static function must_be_present(){
				global $db;
				$list=array();
				$q=$db->query("select elected.id from session, elected_committee, elected where (session.start<NOW() and session.end>NOW() and session.id_committee=elected_committee.id_committee and elected_committee.id_elected=elected.id)") or die($db->error);
				while ($r=$q->fetch_row()) {
						$list[]=new elected($r[0]);
				}
				return $list;
		}

		public static function top($count=5){
				global $db;
				$list=array();
				foreach (elected::get_all() as $e) {
						$s = $e->score;
						if($s>0) $list[]=array("elected"=>$e, "score"=>$s);
				}
				usort($list, function($a, $b){
						return $b["score"] - $a["score"];
				});
				return array_slice($list, 0, $count);
		}

		public static function worst($count=5){
				global $db;
				$list=array();
				foreach (elected::get_all() as $e) {
						$s = $e->score;
						if($s<0) $list[]=array("elected"=>$e, "score"=>($s*(-1)));
				}
				usort($list, function($a, $b){
						return $b["score"] - $a["score"];
				});
				return array_slice($list, 0, $count);
		}

		public function assign_to($committee){
			global $db;
			$db->query("insert ignore into elected_committee (id_committee, id_elected) values('".$committee->id."', '".$this->id."')");
		}

		public function unassign_from($committee){
			global $db;
			$db->query("delete from elected_committee where (id_committee='".$committee->id."' and id_elected='".$this->id."')");
		}

		public static function get_all(){
				global $db;
				$q=$db->query("select id from elected");
				while($r=$q->fetch_row()){
						$list[]=new elected($r[0]);
				}
				return $list;
		}

		public static function username_exists($username){
				global $db;
				$q=$db->query("select count(username) from citizen where (username='".$username."')");
				$r1=$q->fetch_row();
				$q=$db->query("select count(username) from iwatchadmin where (username='".$username."')");
				$r2=$q->fetch_row();
				$q=$db->query("select count(username) from elected where (username='".$username."')");
				$r3=$q->fetch_row();
				return ($r1[0] + $r2[0] + $r3[0])>0;
		}

		public static function create($username,$password){
				global $db;
				if(elected::username_exists($username)) return array("status"=>"username_exists");
				$db->query("insert into elected (username,password) values('".$db->real_escape_string($username)."', ENCRYPT('".$db->real_escape_string($password)."'))");
				return new elected($db->insert_id);
		}

		public function delete(){
				global $db;
				$db->query("delete from elected where (id='".$this->id."')");
		}

		public static function login($username, $password, $ip){
			global $db;

			// security params
			$allowed_attempts = 5;
			$waiting_minutes = 15;

			if( $ip == NULL ) return array("status"=>"restricted_host");

			$q=$db->query("select id, password from elected where (username='".$username."')");
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
						return new elected($r[0]);
					}
				}
			}
		}
	}
?>
