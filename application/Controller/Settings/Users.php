<?php
class Controller_Settings_Users extends Controller_Index {
    
    public function __construct($registry) {
		parent::__construct($registry, "settings", "users");
	}
	
	public function index($args) {
        if ($this->registry["auth"]) {
            if ($this->registry["ui"]["admin"]) {
                
                $this->view->setTitle("Пользователи");
                
                $this->view->setLeftContent($this->view->render("left_settings", array()));
               
                if (isset($args[1])) {
                    
                    if ($args[1] == "adduser") {
                        $group = $this->user->getGroups();
                        $this->view->settings_adduser(array("group" => $group));
                        
                    } elseif ($args[1] == "addgroup") {
                        $this->view->settings_addgroup();
                    } elseif ($args[1] == "gedit") {
                        if (isset($args[2])) {
                    
                            if(isset($_POST['submit_group'])) {
                                $this->user->editGroupName($args[2], $_POST["group"]);
                                
                                $this->view->refresh(array("timer" => "1", "url" => "/settings/users/"));
                            } else {
                            
                                $gname = $this->user->getGroupName($args[2]);
                            
                                $this->view->settings_editgroup(array("gid" => $args[2], "gname" => $gname));
                            }
                        }
                    } elseif ($args[1] == "edit") {
                        if (isset($args[2])) {
                            
                            $data = $this->user->getUserInfo($args[2]);
                            $group = $this->user->getGroups();
                            
                            if(isset($_POST['submit'])) {
                                $validate = new Model_Validate($this->registry);
                        
                                $err = array();
                                if ($txt = $validate->email($_POST["email"])) { $err[] = $txt; };
                                if ($txt = $validate->name($_POST["name"])) { $err[] = $txt; };
                                if ($txt = $validate->soname($_POST["soname"])) { $err[] = $txt; };
                                if ($data["pass"] != $_POST["pass"]) {
                                    if ($txt = $validate->password($_POST["pass"])) { $err[] = $txt; };
                                }

                                if (count($err) == 0) {
                                    $uid = $this->user->editUser($args[2], $_POST["name"], $_POST["soname"], $_POST["email"]);
                                    if ($data["pass"] != $_POST["pass"]) {
                                        $this->user->editUserPass($args[2], $_POST["pass"]);
                                    }
                                    $this->user->editUserPriv($args[2], $_POST["priv"], $_POST["group_name"]);
                                    
                                    $this->view->refresh(array("timer" => "1", "url" => "/settings/users/"));
                                } else {
                                    $_POST["uid"] = $data["uid"];
                                    $this->view->settings_edituser(array("group" => $group, "err" => $err, "post" => $_POST));
                                }
                            } else {
                                if ($data["admin"]) {
                                    $data["priv"] = "admin";
                                } elseif ($data["readonly"]) {
                                    $data["priv"] = "readonly";
                                }
                                $this->view->settings_edituser(array("post" => $data, "group" => $group));
                            }
                        }
                    }
                } else {
                    
                    if(isset($_POST['submit'])) {
                        $validate = new Model_Validate($this->registry);
                        
                        $err = array();
                        if ($txt = $validate->login($_POST["login"])) { $err[] = $txt; };
                        if ($txt = $validate->email($_POST["email"])) { $err[] = $txt; };
                        if ($txt = $validate->name($_POST["name"])) { $err[] = $txt; };
                        if ($txt = $validate->soname($_POST["soname"])) { $err[] = $txt; };
                        if ($txt = $validate->password($_POST["pass"])) { $err[] = $txt; };

                        if (count($err) == 0) {
                            $uid = $this->user->addUser($_POST["login"], $_POST["pass"], $_POST["name"], $_POST["soname"], $_POST["email"]);
                            $this->user->addUserPriv($uid, $_POST["priv"], $_POST["group_name"]);
                            
                            $this->view->refresh(array("timer" => "1", "url" => "/settings/users/"));
                        } else {
                            $group = $this->user->getGroups();
                            $this->view->settings_adduser(array("group" => $group, "err" => $err, "post" => $_POST));
                        }
                    } else {
                    
                        if(isset($_POST['submit_group'])) {
                            $this->user->addGroups($_POST["new_group"]);
                        }
                        
                        $list = $this->user->getUsersList();
                        $group = $this->user->getGroups();
                        
                        $this->view->settings_users(array("list" => $list, "group" => $group));
                    }
                }
            }
        }
                
        $this->view->showPage();
	}
}
?>