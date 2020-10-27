<?php

namespace Dev4Press\Plugin\DemoPress\Admin\Panel;

use Dev4Press\Core\UI\Admin\PanelSettings;
use Dev4Press\Plugin\DemoPress\Traits\Panel as TraitPanel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Settings extends PanelSettings {
	use TraitPanel;

	public $settings_class = '\\Dev4Press\\Plugin\\DemoPress\\Admin\\Settings';

	public function __construct( $admin ) {
		parent::__construct( $admin );

		$this->subpanels = $this->subpanels + array(
				'global' => array(
					'title'      => __( "Global Control", "gd-topic-polls" ),
					'icon'       => 'ui-sliders',
					'break'      => __( "Builder", "demopress" ),
					'break-icon' => 'ui-code',
					'info'       => __( "From this panel you can control basic options related to all builders.", "gd-topic-polls" )
				),
				'api_keys' => array(
					'title'      => __( "API Keys", "gd-topic-polls" ),
					'icon'       => 'ui-key',
					'info'       => __( "From this panel you can control API keys needed by some data builders.", "gd-topic-polls" )
				)
			);
	}

	public function enqueue_scripts() {
		$this->local_enqueue_scripts( $this->a() );
	}
}
