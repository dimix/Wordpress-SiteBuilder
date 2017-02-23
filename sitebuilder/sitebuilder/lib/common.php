<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

define('DS', '/');

require_once(__DIR__ . DS . 'SiteBuilderFormHelper.php');
require_once(__DIR__ . DS . 'Exceptions' . DS . 'exceptions.php');

function debug($var, $die = false)
{
	echo '<pre>';
	print_r($var);
	echo '</pre>';
	
	if ($die) { die; }
}

?>