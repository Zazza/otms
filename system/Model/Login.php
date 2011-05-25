<?php
class Model_Login extends Model_Index {
	
	private function generateCode($length=6) {
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
	$code = "";
	$clen = strlen($chars) - 1;  
	while (strlen($code) < $length) {
		$code .= $chars[mt_rand(0,$clen)];  
	}
		return $code;
	}
	
	public function login($login, $pass) {
		$sql = "SELECT * FROM users WHERE login = :login AND pass != '' LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":login" => $login);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
		
		if (count($data) == 1) {
            if ($data[0]["pass"] === md5(md5($pass))) {

                 $loginSession = & $_SESSION["login"];
                 $loginSession["id"] = $data[0]["id"];
            
                 return TRUE;
            } else {
                 return FALSE;
            }
		} else {
			return FALSE;
		}
	}
}
?>