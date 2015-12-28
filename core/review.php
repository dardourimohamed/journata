<?php
    class review{

        private $id;

        public function __construct($nid){
            $this->id = $nid;
        }

        public function __set($name,$value){
            global $db;
            if ($this->id != NULL) {
                switch($name){
                    default :
                        $db->query("update reviews set ".$name."=".($value===null?"NULL":"'".$db->real_escape_string($value)."'")." where (id='".$this->id."')");
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
                    case 'session':
                        return new session($this->id_session);
                    break;
                    default:
                        $q=$db->query("select ".$name." from reviews where (id='".$this->id."')");
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
            $db->query("insert into reviews (col) values('".$db->real_escape_string($param)."')");
            return new review($db->insert_id);
        }

        public function delete(){
            global $db;
            $db->query("delete from reviews where (id='".$this->id."')");
        }

    }
?>
