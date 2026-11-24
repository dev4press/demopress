<?php

namespace Dev4Press\Plugin\DemoPress\Admin\Panel;

use Dev4Press\v50\Core\UI\Admin\PanelAbout;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class About extends PanelAbout {
	protected $history = true;
}
