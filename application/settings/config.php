<?php

$config['db'] = array(
	'adapter'   => 'mysql',
	'host'          => 'localhost',
	'username'      => '',
	'password'      => '',
	'dbname'        => ''
);

$config['path'] = array(
	'application'  => 'application/',
	'controller'   => 'application/Controller/',
	'library'      => 'library/',
	'templates'	   => 'application/View/templates',
	'cache'        => 'application/View/cache',
	'layouts'      => 'application/View/layouts/',
	'system'       => 'application/system/',
    'public'       => 'public/'
);

$config['keywords'] = array(
	'keywords'	    => 'HelpDesk',
	'description'	=> 'HelpDesk'
);

$config['year'] = array('2011', '2012');

$config['mailSender'] = 'helpdesk@example.com';
?>
