<?php
$config['path'] = array(
	'library'		=> '../system/library/',
	'cache'			=> '../system/View/cache/',
	'templates'		=> 'templates/',
	'layouts'		=> 'layouts/'
);

$config['root'] = dirname(__FILE__) . '/../../';

$config['upload'] = 'upload/';

$uri = substr($_SERVER["REQUEST_URI"], 0, strpos($_SERVER["REQUEST_URI"], "index.php?main")-1);
$config['url'] = $uri;

$config['sizeLimit'] = 2097152;
$config['allSize'] = 104857600;

$config['memcached'] = TRUE;

$config['allowedExtensions'] = array();

$config['rgb']=0xFFFFFF;
$config['quality']=100;

$config['drop'][0] = '.jHtmlArea';
?>