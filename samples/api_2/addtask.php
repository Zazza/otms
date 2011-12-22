<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="style.css" rel="stylesheet" type="text/css" />
<title>API SAMPLE</title>
</head>
<body>
<p><a id="back" href="http://testing.server/otms_module/samples/api_2/">На главную</a></p>
<?php
	require_once 'config.php';

	if (isset($_POST["addTask"])) {
		if ($curl = curl_init()) {
			$text = rawurlencode($_POST["task"]);
			
			$dourl = $url . "/api/?action=addTask&login=" . $login . "&password=" . $password . "&oid=" . $oid . "&text=" . $text;
			if (isset($rall)) {
				$dourl = $dourl . "&rall=1";
			}
			if (isset($ruser)) {
				$str = implode("&ruser[]=", $ruser);
				$dourl = $dourl . "&ruser[]=" . $str;
			}
			if (isset($gruser)) {
				$str = implode("&gruser[]=", $gruser);
				$dourl = $dourl . "&gruser[]=" . $str;
			}

			curl_setopt($curl, CURLOPT_URL, $dourl);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			print_r(curl_exec($curl));
			curl_close($curl);
		}
	}
?>
<form action="http://testing.server/otms_module/samples/api_2/addtask.php" method="post">
	<textarea name="task" id="taskarea"></textarea>
	<p><input type="submit" value="Создать" name="addTask" /></p>
</form>

</body>
</html>