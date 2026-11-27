<?php

use Dev4Press\v56\Core\Options\Render;
use function Dev4Press\v56\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="d4p-content">
	<?php

	if ( demopress_gen()->is_idle() ) {
		if ( demopress_admin()->subpanel == 'index' ) {
			?>

            <div class="d4p-features-wrapper">
				<?php

				panel()->include_element( 'subpanels', 'settings', array( 'class' => '_is-settings' ) );

				?>
            </div>

			<?php
		} else {
			$generator = demopress()->get_generator( demopress_admin()->subpanel );

			if ( is_wp_error( $generator ) ) {
				echo esc_html( $generator->get_error_message() );
			} else {
				settings_fields( 'demopress-generator' );

				?>

                <div class="d4p-generator-control">
                <input type="hidden" name="demopress_handler" value="postback"/>
                <input type="hidden" name="demopress_value[demo-generator-type]" value="<?php echo esc_attr( $generator->name ); ?>"/>

				<?php

				include( DEMOPRESS_PATH . 'forms/shared/generator.php' );

				$generator->show();

				Render::i( panel()->a()->n(), panel()->a()->plugin_prefix )->prepare( demopress_admin()->subpanel, $generator->settings )->render();

				?></div><?php
			}
		}
	} else {
		$_title  = '';
		$_status = '';

		if ( demopress_gen()->is_running() ) {
			$_title  = __( "Generator Task Running", "demopress" );
			$_status = 'running';
		} else if ( demopress_gen()->is_finished() ) {
			$_title  = __( "Generator Task Finished", "demopress" );
			$_status = 'finished';
		} else if ( demopress_gen()->is_error() ) {
			$_title  = __( "Generator Task Error", "demopress" );
			$_status = 'error';
		}

		?>

        <div style="clear: both"></div>
        <div class="d4p-panel-break d4p-clearfix">
            <h1><?php echo esc_html( $_title ); ?></h1>
        </div>
        <div style="clear: both"></div>

        <div class="d4p-generator-status">
            <div class="demopress-generator-panel demopress-gen-status-<?php echo esc_attr( $_status ); ?>">
                <div class="demopress-gen-header">
					<?php esc_html_e( "Generator", "demopress" ); ?>:
                    <strong><?php echo esc_html( demopress()->get_generator_label( demopress_gen()->type ) ); ?></strong>
                    <br/><?php esc_html_e( "Started", "demopress" ); ?>:
                    <strong><?php echo esc_html( date( 'c', absint( demopress_gen()->started ) ) ); ?></strong>
					<?php if ( ! demopress_gen()->is_running() ) { ?><br/><?php esc_html_e( "Ended", "demopress" ); ?>:
                        <strong><?php echo esc_html( date( 'c', absint( demopress_gen()->ended ) ) ); ?></strong><?php } ?>
                </div>
                <div class="demopress-gen-status">
                    <pre><?php echo esc_html( implode( PHP_EOL, demopress_gen()->format_log_list() ) ); ?></pre>
                </div>
                <p>
					<?php esc_html_e( "This page automatically checks for the generator status every 5 seconds. If the process gets broken on the server side, the plugin will attempt auto-recovery.", "demopress" ); ?>
                </p>
                <div class="demopress-gen-loader">
                    <i class="d4p-icon d4p-ui-spinner d4p-icon-spin"></i> <?php esc_html_e( "Checking progress...", "demopress" ); ?>
                </div>
            </div>
        </div>

		<?php
	}

	?>
</div>
