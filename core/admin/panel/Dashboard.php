<?php

namespace Dev4Press\Plugin\DemoPress\Admin\Panel;

use Dev4Press\Plugin\DemoPress\Traits\Panel as TraitPanel;
use Dev4Press\v35\Core\UI\Admin\PanelDashboard;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Dashboard extends PanelDashboard {
	use TraitPanel;

	protected $form = true;

	public function __construct( $admin ) {
		parent::__construct( $admin );

		$this->sidebar_links = array(
			                       'top' => array(
				                       'cleanup' => array(
					                       'icon'  => 'ui-trash',
					                       'class' => 'button-primary',
					                       'url'   => $this->a()->panel_url( 'tools', 'cleanup' ),
					                       'label' => __( "Remove Generated Data", "demopress" )
				                       )
			                       )
		                       ) + $this->sidebar_links;

		$this->subpanels = array(
			'index' => array(
				'title' => __( "Generators", "demopress" ),
				'icon'  => 'ui-sun'
			)
		);

		$groups_done = array();

		foreach ( demopress()->get_generator_groups() as $group => $break ) {
			foreach ( demopress()->generators as $code => $generator ) {
				if ( $generator['settings']['group'] != $group ) {
					continue;
				}

				$slug = $generator['slug'];

				$this->subpanels[ $slug ] = array(
					'title' => $generator['label'],
					'icon'  => $generator['settings']['icon'],
					'info'  => $generator['description']
				);

				if ( ! in_array( $group, $groups_done ) ) {
					$this->subpanels[ $slug ]['break']      = $break['label'];
					$this->subpanels[ $slug ]['break-icon'] = $break['icon'];

					$groups_done[] = $group;
				}
			}
		}
	}

	public function enqueue_scripts() {
		$this->local_enqueue_scripts( $this->a() );
	}

	public function form_tag_open() {
		return '<form method="post" action="" id="' . $this->a()->plugin_prefix . '-form-generator" enctype="multipart/form-data" autocomplete="off">';
	}
}
