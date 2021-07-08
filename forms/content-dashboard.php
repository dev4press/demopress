<?php

use Dev4Press\v35\Core\Options\Render;
use function Dev4Press\v35\Functions\panel;

?>

<div class="d4p-content">
	<?php

	if ( demopress_gen()->is_idle() ) {
		if ( demopress_admin()->subpanel == 'index' ) {
			?>
            <div class="d4p-generators-list"><?php

			foreach ( demopress()->get_generator_groups() as $group => $obj ) {
				if ( demopress()->has_generators_for_group( $group ) ) {

					?>

                    <div style="clear: both"></div>
                    <div class="d4p-panel-break d4p-clearfix">
                        <h1>
                            <i class="d4p-icon d4p-<?php echo $obj['icon']; ?> d4p-icon-fw"></i> <?php echo $obj['label']; ?>
                        </h1>
                    </div>
                    <div style="clear: both"></div>

					<?php

					foreach ( demopress()->generators as $code => $generator ) {
						if ( $generator['settings']['group'] != $group ) {
							continue;
						}

						$url = admin_url( 'options-general.php?page=demopress&panel=dashboard&subpanel=' . $generator['slug'] );

						?>
                        <div class="d4p-options-panel">
                        <i aria-hidden="true" class="<?php echo $generator['settings']['icon']; ?> d4p-icon-fw"></i>
                        <h5><?php echo $generator['label']; ?></h5>
                        <div>
                            <em><?php echo $generator['description']; ?></em>
                            <a class="button-primary" href="<?php echo $url; ?>"><?php _e( "Generate", "demopress" ); ?></a>
                        </div>
                        </div><?php
					}
				}
			}

			?></div><?php
		} else {
			$generator = demopress()->get_generator( demopress_admin()->subpanel );

			if ( is_wp_error( $generator ) ) {
				echo $generator->get_error_message();
			} else {
				settings_fields( 'demopress-generator' );

				?>

                <div class="d4p-generator-control">
                <input type="hidden" name="demopress_handler" value="postback"/>
                <input type="hidden" name="demopress_value[demo-generator-type]" value="<?php echo $generator->name; ?>"/>

				<?php

				include( DEMOPRESS_PATH . 'forms/shared/generator.php' );

				$generator->show();

				Render::instance( panel()->a()->n(), panel()->a()->plugin_prefix )->prepare( demopress_admin()->subpanel, $generator->settings )->render();

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
            <h1><?php echo $_title; ?></h1>
        </div>
        <div style="clear: both"></div>

        <div class="d4p-generator-status">
            <div class="demopress-generator-panel demopress-gen-status-<?php echo $_status; ?>">
                <div class="demopress-gen-header">
					<?php _e( "Generator", "demopress" ); ?>:
                    <strong><?php echo demopress()->get_generator_label( demopress_gen()->type ); ?></strong>
                    <br/><?php _e( "Started", "demopress" ); ?>:
                    <strong><?php echo date( "c", demopress_gen()->started ); ?></strong>
					<?php if ( ! demopress_gen()->is_running() ) { ?><br/><?php _e( "Ended", "demopress" ); ?>:
                        <strong><?php echo date( "c", demopress_gen()->ended ); ?></strong><?php } ?>
                </div>
                <div class="demopress-gen-status">
                    <pre><?php echo join( D4P_EOL, demopress_gen()->format_log_list() ); ?></pre>
                </div>
                <p>
					<?php _e( "This page automatically checks for the generator status every 5 seconds. If the process gets broken on the server side, the plugin will attempt auto-recovery.", "demopress" ); ?>
                </p>
                <div class="demopress-gen-loader">
                    <i class="d4p-icon d4p-ui-spinner d4p-icon-spin"></i> <?php _e( "Checking progress...", "demopress" ); ?>
                </div>
            </div>

            <script type="text/javascript">
                jQuery(document).ready(function() {
                    if (jQuery(".demopress-gen-status-running").length === 1) {
                        window.wp.demopress.generator.log.init();
                    }
                });
            </script>
        </div>

		<?php
	}

	?>
</div>
