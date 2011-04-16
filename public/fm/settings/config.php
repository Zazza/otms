<?php
$config['path'] = array(
	'library'		=> '../../library/',
	'cache'			=> '../../application/View/cache/',
	'templates'		=> 'templates/',
	'layouts'		=> 'layouts/'
);
$config['root'] = dirname(__FILE__) . '/../../';

$config['upload'] = '/upload/';

$config['url'] = '/fm';

$config['sizeLimit'] = 2097152;
$config['allSize'] = 104857600;

$config['memcached'] = TRUE;

$config['allowedExtensions'] = array();

// thumbnails
$config['rgb']=0xFFFFFF;
$config['quality']=100;

// drop elements
$config['drop'][0] = '#jHtmlAreaDrop';
?>