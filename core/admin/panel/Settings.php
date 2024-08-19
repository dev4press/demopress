<?php

namespace Dev4Press\Plugin\DemoPress\Admin\Panel;

use Dev4Press\v41\Core\UI\Admin\PanelSettings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Settings extends PanelSettings {
	public $settings_class = '\\Dev4Press\\Plugin\\DemoPress\\Admin\\Settings';

	public function __construct( $admin ) {
		parent::__construct( $admin );

		$this->subpanels = $this->subpanels + array(
				'global'   => array(
					'title'      => __( "Global Control", "demopress" ),
					'icon'       => 'ui-sliders',
					'break'      => __( "Builder", "demopress" ),
					'break-icon' => 'ui-code',
					'info'       => __( "From this panel you can control basic options related to all builders.", "demopress" )
				),
				'api_keys' => array(
					'title' => __( "API Keys", "demopress" ),
					'icon'  => 'ui-key',
					'info'  => __( "From this panel you can control API keys needed by some data builders.", "demopress" )
				)
			);
	}
}
