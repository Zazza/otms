<?php
class Controller_Ajax_Users extends Controller_Ajax_Index {

	public function __construct($registry, $action, $args) {
		parent::__construct($registry, $action, $args);
	}

    public function delGroup($params) {
        $gid = $params["gid"];
        
        $this->user->delGroup($gid);
    }
    
    public function delUser($params) {
        $uid = $params["uid"];
        
        $this->user->delUser($uid);
    }
    
    public function getUser($params) {
        $id = $params["id"];
        $type = $params["type"];
        
        if ($type == "u") {
            
            $data = $this->user->getUserInfo($id);
            echo "<p><span id='udesc[" . $data["uid"] . "]' style='font-size: 11px; margin-right: 10px'>" . $data["name"] . " " . $data["soname"] . "</span>";
            echo '<input id="uhid[' . $data["uid"] . ']" type="hidden" name="ruser[]" value="' . $data["uid"] . '" /></p>';
            
        } elseif ($type == "g") {
            
            $data = $this->user->getUserInfoFromGroup($id);
            foreach ($data as $part) {
                echo "<p><span id='udesc[" . $part["uid"] . "]' style='font-size: 11px; margin-right: 10px'>" . $part["name"] . " " . $part["soname"] . "</span>";
                echo '<input id="uhid[' . $part["uid"] . ']" type="hidden" name="ruser[]" value="' . $part["uid"] . '" /></p>';
            }
            
        } elseif ($type == "all") {
            
            $data = $this->user->getUsersList();
            foreach ($data as $part) {
                echo "<p><span id='udesc[" . $part["id"] . "]' style='font-size: 11px; margin-right: 10px'>" . $part["name"] . " " . $part["soname"] . "</span>";
                echo '<input id="uhid[' . $part["id"] . ']" type="hidden" name="ruser[]" value="' . $part["id"] . '" /></p>';
            }
            
        }
    }
    
    public function spam($params) {
        $tid = $params["tid"];
        
        $this->user->spam($tid);
    }
}
?>