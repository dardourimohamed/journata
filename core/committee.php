<?php
    class committee{

        private $id;

        public function __construct($nid){
            $this->id = $nid;
        }

        public function __set($name,$value){
            global $db;
            if ($this->id != NULL) {
                switch($name){

                    default :
                        $db->query("update committee set ".$name."=".($value===null?"NULL":"'".$db->real_escape_string($value)."'")." where (id='".$this->id."')");
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
                    case 'electeds':
                        $list=array();
                        $q=$db->query("select elected.id from elected_committee, elected where (elected_committee.id_committee='".$this->id."' and elected.id=elected_committee.id_elected)");
                        while($r=$q->fetch_row()){
                          $list[]=new elected($r[0]);
                        }
                        return $list;
                    break;
                    default:
                        $q=$db->query("select ".$name." from committee where (id='".$this->id."')");
			                  $r=$q->fetch_row();
                        return $r[0];
                    break;
                }
            }else{
                return NULL;
            }
        }

        public function assign_elected($elected){
          global $db;
          $db->query("insert ignore into elected_committee (id_elected, id_committee) values('".$elected->id."', '".$this->id."')");
        }

        public function unassign_elected($elected){
          global $db;
          $db->query("delete from elected_committee where (id_elected='".$elected->id."' and id_committee='".$this->id."')");
        }

        public static function create($param){
            global $db;
            $db->query("insert into committee (col) values('".$db->real_escape_string($param)."')");
            return new committee($db->insert_id);
        }

        public function delete(){
            global $db;
            $db->query("delete from committee where (id='".$this->id."')");
        }

    }
?>
