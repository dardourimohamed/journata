<?php
    class radar{

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
                    default :
                        $db->query("update radar set ".$name."=".($value===null?"NULL":"'".$db->real_escape_string($value)."'")." where (id='".$this->id."')");
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
                    default:
                        $q=$db->query("select ".$name." from radar where (id='".$this->id."')");
			                  $r=$q->fetch_row();
                        return $r[0];
                    break;
                }
            }else{
                return NULL;
            }
        }

        public static function create($elected){
            global $db;
            $db->query("insert into radar (id_elected) values('".$db->real_escape_string($elected->id)."')");
            return new radar($db->insert_id);
        }

        public function delete(){
            global $db;
            $db->query("delete from radar where (id='".$this->id."')");
        }

    }
?>
