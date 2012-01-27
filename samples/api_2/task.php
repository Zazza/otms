<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="style.css" rel="stylesheet" type="text/css" />
<title>API SAMPLE</title>
</head>
<body>
<p><a id="back" href="http://testing.server/otms_module/samples/api_2/">На главную</a></p>
<div id="task">
<?php
	require_once 'config.php';

	if (isset($_POST["addComment"])) {
		if ($curl = curl_init()) {
			$text = rawurlencode($_POST["comment"]);
			
			$dourl = $url . "/api/?action=addComment&login=" . $login . "&password=" . $password . "&oid=" . $oid . "&tid=" . $_GET["tid"] . "&text=" . $text;
			
			curl_setopt($curl, CURLOPT_URL, $dourl);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_exec($curl);		
			curl_close($curl);
		}
	}
	
	if (isset($_POST["closeTask"])) {
		if ($curl = curl_init()) {
			$dourl = $url . "/api/?action=closeTask&login=" . $login . "&password=" . $password . "&oid=" . $oid . "&tid=" . $_GET["tid"];
		
			curl_setopt($curl, CURLOPT_URL, $dourl);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_exec($curl);		
			curl_close($curl);
		}
	}
	
	if ($curl = curl_init()) {
		$dourl = $url . "/api/?action=getTask&login=" . $login . "&password=" . $password . "&oid=" . $oid . "&tid=" . $_GET["tid"];

		curl_setopt($curl, CURLOPT_URL, $dourl);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$out = curl_exec($curl);
		curl_close($curl);
		
		$out = json_decode($out, true);
		
		foreach($out as $key=>$val) {
			if ($key == "task") {
				$id = $val["id"];
				$text = $val["text"];
				$open_data = $val["open_data"];
				$close_data = $val["close_data"];
				$author = $val["author"];
				$numComments = $val["numComments"];
				$newComments = $val["newComments"];
				
				if ($close_data == "0000-00-00 00:00:00") {
					?>
						<div class="otask">
							<div class="tid">
								<a href="task.php?tid=<?php echo $id; ?>">№<?php echo $id; ?></a>
								<form action="http://testing.server/otms_module/samples/api_2/task.php?tid=<?php echo $_GET["tid"]; ?>" method="post">
									<p><input type="submit" value="Закрыть" name="closeTask"></p>
								</form>
							</div>
							<div class="tright"><div class="ttext"><?php echo $text; ?></div>
								<div class="tsub"><?php echo $author; ?></div><div class="tsub"><?php echo $open_data; ?></div>
								<div class="tcom">Комментарии: <?php echo $numComments; ?> [<?php echo $newComments; ?>]</div>
							</div>
						</div>
					<?php
					} else {
						?>
						<div class="ctask">
							<div class="tid"><a href="task.php?tid=<?php echo $id; ?>">№<?php echo $id; ?></a></div>
							<div class="tright"><div class="ttext"><?php echo $text; ?></div>
								<div class="tsub"><?php echo $author; ?></div><div class="tsub"><?php echo $open_data; ?></div>
								<div class="tclose">Закрыта: <?php echo $close_data; ?></div>
								<div class="tcom">Комментарии: <?php echo $numComments; ?> [<?php echo $newComments; ?>]</div>
							</div>
						</div>
						<?php
					}
			}
			
			if ($key == "comments") {
				foreach($val as $ckey=>$cval) {
				?>
				<div class='textcom'>
					<div class='over'>
						<div class='textcomsub'><?php echo $cval["author"]; ?></div>
						<div class='textcomsub'><?php echo $cval["date"]; ?></div>
					</div>
					<div><?php echo $cval["text"]; ?></div>
				</div>
				<?php
				}
			}
		}
	}
?>
</div>
<div id="comments"></div>

<?php if ($close_data == "0000-00-00 00:00:00") { ?>
<form action="http://testing.server/otms_module/samples/api_2/task.php?tid=<?php echo $_GET["tid"]; ?>" method="post">
	<textarea name="comment" id="comment"></textarea>
	<p><input type="submit" value="Написать" name="addComment" /></p>
</form>
<?php } ?>

</body>
</html>