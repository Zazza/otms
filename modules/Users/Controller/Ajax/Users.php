<?php
class Controller_Ajax_Users extends Modules_Ajax {
    public function delGroup($params) {
        $gid = $params["gid"];
        
        $this->registry["user"]->delGroup($gid);
    }
    
    public function delUser($params) {
        $uid = $params["uid"];
        
        $this->registry["user"]->delUser($uid);
    }
    
    public function getUser($params) {
        $id = $params["id"];
        $type = $params["type"];
        
        if ($type == "u") {
            
            $data = $this->registry["user"]->getUserInfo($id);
            echo "<p><span id='udesc[" . $data["uid"] . "]' style='font-size: 11px; margin-right: 10px'>" . $data["name"] . " " . $data["soname"] . "</span>";
            echo '<input id="uhid[' . $data["uid"] . ']" type="hidden" name="ruser[]" value="' . $data["uid"] . '" /></p>';
            
        } elseif ($type == "g") {

            $gname = $this->registry["user"]->getGroupName($id);
            echo '<p style="font-size: 11px; margin-right: 10px">' . $gname . '<input type="hidden" name="gruser[]" value="' . $id . '" /></p>';
        } elseif ($type == "all") {

            echo '<p style="font-size: 11px; margin-right: 10px">Все<input type="hidden" name="rall" value="1" /></p>';
        }
    }
    
    public function spam($params) {
        $tid = $params["tid"];
        
        $this->registry["user"]->spam($tid);
    }

    public function getUI($params) {
    	$uid = $params["uid"];
    	
    	$data = $this->registry["user"]->getUserInfo($uid);
    	
    	echo $this->view->render("userInfo", array("post" => $data));
    }

    public function getTree($params) {
        $pid = $params["pid"];

        $tree = $this->registry["user"]->getSubgroups($pid);
        
        echo $this->view->render("users_structure", array("tree" => $tree));
    }
    
	public function addTree($params) {
        $pid = $params["pid"];
        $name = htmlspecialchars($params["name"]);

        $this->registry["user"]->addSubgroup($pid, $name);
    }
    
	public function delCat($params) {
        $id = $params["id"];

        $this->registry["user"]->delSubgroup($id);
    }
    
	public function editCat($params) {
        $id = $params["id"];
        $name = htmlspecialchars($params["name"]);
        
        $this->registry["user"]->editCat($id, $name);
    }
    
    public function getCatName($params) {
        $id = $params["id"];
        
        $cat = $this->registry["user"]->getCatName($id);
         
        echo $cat["name"];
    }
}
?>