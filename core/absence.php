<?php
    class absence{

        private $id_elected;
        private $id_session;

        public function __construct($nid_elected, $nid_session){
            $this->id_elected = $nid_elected;
            $this->id_session = $nid_session;
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
                        $db->query("update absence set ".$name."=".($value===null?"NULL":"'".$db->real_escape_string($value)."'")." where (id_elected='".$this->id_elected."' and id_session='".$this->id_session."')");
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
                        $q=$db->query("select ".$name." from absence where (id_elected='".$this->id_elected."' and id_session='".$this->id_session."')");
			                  $r=$q->fetch_row();
                        return $r[0];
                    break;
                }
            }else{
                return NULL;
            }
        }

        public static function create($elected, $absence){
            global $db;
            $db->query("insert into absence (id_elected, id_absence) values('".$elected->id."', '".$absence->id."')");
            return new absence($db->insert_id);
        }

        public function delete(){
            global $db;
            $db->query("delete from absence where (id_elected='".$this->id_elected."' and id_session='".$this->id_session."')");
        }

    }
?>
