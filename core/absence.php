<?php
    class absence{

        private $id;

        public function __construct($nid){
            $this->id = $nid;
        }

        public function __set($name,$value){
            global $db;
            if ($this->id != NULL) {
                switch($name){
                    case 'elected':
                        $this->id_elected=$value->id;
                    break;
                    case 'session':
                        $this->id_session=$value->id;
                    break;
                    default :
                        $db->query("update absence set ".$name."=".($value===null?"NULL":"'".$db->real_escape_string($value)."'")." where (id='".$this->id."')");
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
                    case 'elected':
                      return new elected($this->id_elected);
                    break;
                    case 'session':
                      return new session($this->id_session);
                    break;
                    default:
                        $q=$db->query("select ".$name." from absence where (id='".$this->id."')");
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
            $db->query("insert into absence (col) values('".$db->real_escape_string($param)."')");
            return new absence($db->insert_id);
        }

        public function delete(){
            global $db;
            $db->query("delete from absence where (id='".$this->id."')");
        }

    }
?>
