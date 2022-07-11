<?php
/*
@wordpress-plugin
* Plugin Name: 	WP Fworm
* Plugin URI: 	https://docs.wordpress-framework.com/docs/orm
* Description:  ORM para manejar de forma fácil y sencilla los procesos correspondientes al manejo de bases de datos.
* Version:      1.0
* Author: 		Flikimax	
* Author URI: 	https://flikimax.com
* License: GPLv2 or later
*/

if ( !defined('ABSPATH') ) {
	exit;
}

define('WP_FWORM_NAME', 'WP Fworm');
define('WP_FWORM_VERSION', '1.0');

require_once __DIR__ . '/vendor/autoload.php';
