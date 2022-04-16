<?php
/*
@wordpress-plugin
* Plugin Name: 	WP Fworm
* Plugin URI: 	https://flikimax.com
* Description:  ORM para WordPress.	
* Version:      0.4
* Author: 		Flikimax	
* Author URI: 	https://flikimax.com
* License: GPLv2 or later
*/

if ( !defined('ABSPATH') ) {
	exit;
}

define('WP_FWORM_NAME', 'WP Fworm');
define('WP_FWORM_VERSION', '0.1');

require_once __DIR__ . '/vendor/autoload.php';

use Fworm\BaseModels\Pruebas;
