<?php

$config["local"][0] = "127.0.0.1";

$config['db'] = array(
	'adapter'   => 'mysql',
	'host'          => 'localhost',
	'username'      => '',
	'password'      => '',
	'dbname'        => ''
);

$config['path'] = array(
	'application'  => '/system/',
	'controller'   => '/system/Controller/',
	'library'      => '/system/library/',
	'templates'	   => '/system/View/templates',
	'cache'        => '/system/View/cache',
	'layouts'      => '/system/View/layouts/',
	'system'       => '/system/system/',
    'public'       => '/'
);

$config['keywords'] = array(
	'keywords'	    => 'HelpDesk',
	'description'	=> 'HelpDesk'
);

$config['year'] = array('2011', '2012');

$config['mailSender'] = 'helpdesk@example.com';
?>
