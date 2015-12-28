<?php
    class session{

        private $id;

        public function __construct($nid){
            $this->id = $nid;
        }

        public function __set($name,$value){
            global $db;
            if ($this->id != NULL) {
                switch($name){
                    case 'committee':
                        $this->id_committee=$value->id;
                    break;
                    default :
                        $db->query("update session set ".$name."=".($value===null?"NULL":"'".$db->real_escape_string($value)."'")." where (id='".$this->id."')");
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
                    case 'committee':
                        return new committee($this->id_committee);
                    break;
                    case 'absences':
                        $list=array();
                        $q=$db->query("select id from absence where (id_session='".$this->id."')");
                        while($r=$q->fetch_row()){
                          $list[]=new absence($r[0]);
                        }
                        return $list;
                    break;
                    default:
                        $q=$db->query("select ".$name." from session where (id='".$this->id."')");
			                  $r=$q->fetch_row();
                        return $r[0];
                    break;
                }
            }else{
                return NULL;
            }
        }

        public static function create($param){
            global $db;
            $db->query("insert into session (col) values('".$db->real_escape_string($param)."')");
            return new session($db->insert_id);
        }

        public function delete(){
            global $db;
            $db->query("delete from session where (id='".$this->id."')");
        }

    }
?>
