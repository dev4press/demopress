<?php

/*
Plugin Name:       DemoPress
Plugin URI:        https://plugins.dev4press.com/demopress/
Description:       Easy to use plugin for generating demo content for newly created websites used during the website development and testing, before real content is created and added.
Author:            Milan Petrovic
Author URI:        https://www.dev4press.com/
Text Domain:       demopress
Version:           1.3
Requires at least: 5.1
Tested up to:      5.8
Requires PHP:      7.0
License:           GPLv3 or later
License URI:       https://www.gnu.org/licenses/gpl-3.0.html

== Copyright ==
Copyright 2008 - 2021 Milan Petrovic (email: milan@gdragon.info)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>
*/

$demopress_dirname_basic = dirname( __FILE__ ) . '/';
$demopress_urlname_basic = plugins_url( '/demopress/' );

define( 'DEMOPRESS_PATH', $demopress_dirname_basic );
define( 'DEMOPRESS_URL', $demopress_urlname_basic );
define( 'DEMOPRESS_D4PLIB_PATH', $demopress_dirname_basic . 'd4plib/' );
define( 'DEMOPRESS_D4PLIB_URL', $demopress_urlname_basic . 'd4plib/' );

require_once( DEMOPRESS_D4PLIB_PATH . 'core.php' );

require_once( DEMOPRESS_PATH . 'libs/autoload.php' );
require_once( DEMOPRESS_PATH . 'core/autoload.php' );
require_once( DEMOPRESS_PATH . 'core/bridge.php' );
require_once( DEMOPRESS_PATH . 'core/functions.php' );

demopress();
demopress_settings();

if ( D4P_ADMIN ) {
	demopress_admin();

	if ( D4P_AJAX ) {
		demopress_ajax();
	}
}
