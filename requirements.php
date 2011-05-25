<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Проверка</title>
<style type="text/css">
div {
    padding: 4px 8px;
    margin: 2px 4px;
}; 
</style>
</head> 
<body>

<h3>Проверка наличия необходимых модулей</h3>

<?php
$flag = TRUE;

if (version_compare(phpversion(), '5.1.0', '<') == true) {
    echo "<div style='color: red'>PHP version must be > 5.1</div>";
    $flag = FALSE;
} else {
    echo "<div style='color: green'>PHP version satisfy the requirements</div>";
}

if (!class_exists('PDO')) {
    echo "<div style='color: red'>PDO disabled</div>";
    $flag = FALSE;
} else {
    echo "<div style='color: green'>PDO enabled</div>";
}

if(!extension_loaded('pdo_mysql')) {
    echo "<div style='color: red'>pdo_mysql disabled</div>";
    $flag = FALSE;
} else {
    echo "<div style='color: green'>pdo_mysql enabled</div>";
}

if(!extension_loaded('mbstring')) {
    echo "<div style='color: red'>mbstring disabled</div>";
    $flag = FALSE;
} else {
    echo "<div style='color: green'>mbstring enabled</div>";
}

$amods = apache_get_modules();
$mod_rewrite = FALSE;
foreach($amods as $part) {
    if ($part == "mod_rewrite") {
        $mod_rewrite = TRUE;
    }
}

if (!$mod_rewrite) {
    echo "<div style='color: red'>mod_rewrite disabled</div>";
    $flag = FALSE;
} else {
    echo "<div style='color: green'>mod_rewrite enabled</div>";
}

if (!function_exists('json_encode')) {
    echo "<div style='color: red'>json disabled</div>";
    $flag = FALSE;
} else {
    echo "<div style='color: green'>json enabled</div>";
}

if (!extension_loaded('curl')) {
    echo "<div style='color: red'>curl disabled</div>";
    $flag = FALSE;
} else {
    echo "<div style='color: green'>curl enabled</div>";
}
?>

<?php
if ($flag) {
?>
<div>
1) Создайте базу данных для работы и импортируйте файл sheme.sql.
<br />
2) Отредактируйте файл /system/settings/config.php:
</div>


<blockquote style="border: 1px solid #AAA; padding: 10px"><code><font size="2" face="Courier New" color="black">$config[<font color="#A31515">'db'</font>] = array(<br>&nbsp;&nbsp;&nbsp;&nbsp;<font color="#008000">// бд</font><br>&nbsp;&nbsp;<font color="#A31515">'adapter'</font>&nbsp; =&#62; <font color="#A31515">'mysql'</font>,<br>&nbsp;&nbsp;&nbsp;&nbsp;<font color="#008000">// ip адрес или имя сервера бд</font><br>&nbsp;&nbsp;<font color="#A31515">'host'</font>&nbsp;&nbsp;&nbsp;=&#62; <font color="#A31515">'localhost'</font>,<br>&nbsp;&nbsp;&nbsp;&nbsp;<font color="#008000">// логин для доступа к базе</font><br>&nbsp;&nbsp;<font color="#A31515">'username'</font>&nbsp;=&#62; <font color="#A31515">''</font>,<br>&nbsp;&nbsp;&nbsp;&nbsp;<font color="#008000">// пароль для доступа к базе</font><br>&nbsp;&nbsp;<font color="#A31515">'password'</font>&nbsp;=&#62; <font color="#A31515">''</font>,<br>&nbsp;&nbsp;&nbsp;&nbsp;<font color="#008000">// имя созданной базы</font><br>&nbsp;&nbsp;<font color="#A31515">'dbname'</font>&nbsp;&nbsp;=&#62; <font color="#A31515">''</font><br>);<br><br><font color="#008000">// email, от которого будут приходить почтовые уведомления</font><br>$config[<font color="#A31515">'mailSender'</font>] = <font color="#A31515">'helpdesk@example.com'</font>;</font><br><br><font size="1" color="gray">* This source code was highlighted with <a href="http://virtser.net/blog/post/source-code-highlighter.aspx"><font size="1" color="gray">Source Code Highlighter</font></a>.</font></code></blockquote>

<div style='margin-top: 20px'>
3) Готово! Удалите файл requirements.php, для работы OTMS.
</div>

<?php
} else {
    echo "<div style='margin-top: 20px; color: red'>Необходимо установить все модули!</div>";
}
?>

</body>
</html>