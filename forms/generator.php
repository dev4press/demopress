<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$panels = array(
	'index' => array(
		'title' => __( "Index", "demopress" ),
		'icon'  => 'cogs',
		'info'  => __( "The list of all available generators.", "demopress" )
	),
);

foreach ( demopress()->generators as $code => $generator ) {
	$panels[ $code ] = array(
		'title' => $generator['label'],
		'icon'  => $generator['settings']['icon'],
		'info'  => $generator['description']
	);
}

$groups = array(
	'core'    => __( "WordPress Core", "demopress" ),
	'plugins' => __( "Third Party Plugins", "demopress" )
);

require_once( DEMOPRESS_PATH . 'forms/shared/top.php' );

?>

    <form method="post" action="">
        <div class="d4p-content-left">
			<?php if ( $_task == 'index' && demopress_gen()->is_idle() ) { ?>
                <div class=" d4p-plugin-dashboard">
                    <div class="d4p-dashboard-badge" style="background-color: #583d48">
                        <div aria-hidden="true" class="d4p-plugin-logo">
                            <i class="d4p-icon d4p-plugin-icon-gd-demo-data-generator"></i></div>
                        <h3><?php echo demopress_admin()->title(); ?></h3>

                        <h5>
							<?php

							_e( "Version", "demopress" );
							echo ': ' . demopress_settings()->info->version;

							if ( demopress_settings()->info->status != 'stable' ) {
								echo ' - <span class="d4p-plugin-unstable" style="color: #fff; font-weight: 900;">' . strtoupper( demopress_settings()->info->status ) . '</span>';
							}

							?>
                        </h5>
                    </div>

                    <div class="d4p-buttons-group">
                        <a class="button-secondary" href="options-general.php?page=demopress&panel=settings"><i aria-hidden="true" class="fa fa-cogs fa-fw"></i> <?php _e( "Settings", "demopress" ); ?>
                        </a>
                        <a class="button-secondary" href="options-general.php?page=demopress&panel=tools"><i aria-hidden="true" class="fa fa-wrench fa-fw"></i> <?php _e( "Tools", "demopress" ); ?>
                        </a>
                        <a class="button-secondary" href="options-general.php?page=demopress&panel=about"><i aria-hidden="true" class="fa fa-info-circle fa-fw"></i> <?php _e( "About", "demopress" ); ?>
                        </a>
                    </div>
                </div>
			<?php } else if ( demopress_gen()->is_idle() ) { ?>
                <div class="d4p-panel-scroller d4p-scroll-active">
                    <div class="d4p-panel-title">
                        <i aria-hidden="true" class="fa fa-cogs"></i>
                        <h3><?php _e( "Generator", "demopress" ); ?></h3>
                        <h4>
                            <i aria-hidden="true" class="fa fa-<?php echo $panels[ $_task ]['icon']; ?>"></i> <?php echo $panels[ $_task ]['title']; ?>
                        </h4>
                    </div>
                    <div class="d4p-panel-info">
						<?php echo $panels[ $_task ]['info']; ?>
                    </div>
                    <div class="d4p-panel-buttons">
                        <input type="submit" value="<?php _e( "Run Generator", "demopress" ); ?>" class="button-primary">
                    </div>
                    <div class="d4p-return-to-top">
                        <a href="#wpwrap"><?php _e( "Return to top", "demopress" ); ?></a>
                    </div>
                </div>
			<?php } else { ?>
                <div class="d4p-panel-scroller d4p-scroll-active">
                    <div class="d4p-panel-title">
                        <i aria-hidden="true" class="fa fa-cogs"></i>
                        <h3><?php _e( "Generator", "demopress" ); ?></h3>
                        <h4><i aria-hidden="true" class="fa fa-play"></i> <?php _e( "Status", "demopress" ) ?></h4>
                    </div>
					<?php if ( demopress_gen()->is_running() ) { ?>
                        <div class="d4p-panel-info">
							<?php _e( "The generator is currently running. You can use the button below to stop it. If you choose to stop it, you must know that the stop is not immidiate, it can take up to 15 seconds for the running process to get the stop message.", "demopress" ) ?>
                        </div>
                        <div class="d4p-panel-buttons">
                            <a href="<?php echo wp_nonce_url( admin_url( 'options-general.php?page=demopress&panel=generator&demopress_handler=getback&action=stoptask' ), 'demopress-task-stop' ); ?>" style="text-align: center" class="button-secondary"><?php _e( "Stop Generator", "demopress" ); ?></a>
                        </div>
					<?php } else if ( demopress_gen()->is_finished() || demopress_gen()->is_error() ) { ?>
                        <div class="d4p-panel-info">
							<?php _e( "The generator is has finished the last task. Use the button below to reset the last task data.", "demopress" ) ?>
                        </div>
                        <div class="d4p-panel-buttons">
                            <a href="<?php echo wp_nonce_url( admin_url( 'options-general.php?page=demopress&panel=generator&demopress_handler=getback&action=resettask' ), 'demopress-task-reset' ); ?>" style="text-align: center" class="button-secondary"><?php _e( "Reset Generator", "demopress" ); ?></a>
                        </div>
					<?php } ?>
                </div>
			<?php } ?>
        </div>
        <div class="d4p-content-right">
			<?php

			if ( demopress_gen()->is_idle() ) {
			if ( $_task == 'index' ) {

			foreach ( $groups as $group => $title ) {
				if ( ! demopress()->has_generators_for_group( $group ) ) {
					continue;
				}

				?>
                <div style="clear: both"></div>
                <div class="d4p-panel-break d4p-clearfix">
                    <h4><?php echo $title; ?></h4>
                </div>
                <div style="clear: both"></div><?php

			foreach ( demopress()->generators as $code => $generator ) {
				if ( $generator['settings']['group'] != $group ) {
					continue;
				}

				$url = 'options-general.php?page=demopress&panel=generator&task=' . $code;

				?>
            <div class="d4p-options-panel">
                <i aria-hidden="true" class="<?php echo $generator['settings']['icon']; ?>"></i>
                <h5><?php echo $generator['label']; ?></h5>
                <div>
                    <em><?php echo $generator['description']; ?></em>
                    <a class="button-primary" href="<?php echo $url; ?>"><?php _e( "Generate", "demopress" ); ?></a>
                </div>
            </div><?php
			}
			}
			} else {
				d4p_includes( array(
					array( 'name' => 'functions', 'directory' => 'admin' ),
					array( 'name' => 'settings', 'directory' => 'admin' )
				), DEMOPRESS_D4PLIB_PATH );

				$generator = demopress()->get_generator( $_task );

			if ( is_wp_error( $generator ) ) {
				echo $generator->get_error_message();
			} else {

				?>

				<?php settings_fields( 'demopress-generator' ); ?>
            <input type="hidden" name="demopress_handler" value="postback"/>
            <input type="hidden" name="demopress_value[type]" value="<?php echo $generator->name; ?>"/>
			<?php

			d4p_includes( array(
				array( 'name' => 'functions', 'directory' => 'admin' ),
				array( 'name' => 'settings', 'directory' => 'admin' )
			), DEMOPRESS_D4PLIB_PATH );

			include( DEMOPRESS_PATH . 'forms/shared/generator.php' );

			$generator->show();

			$render       = new d4pSettingsRender( $_task, $generator->settings );
			$render->base = 'demopressgen';
			$render->render();

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
                    <h4><?php echo $_title; ?></h4>
                </div>
                <div style="clear: both"></div>
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
						<?php _e( "This page automatically checks for the generator status every 5 seconds. If the process gets broken on the server side, the plugin will attempt autorecovery.", "demopress" ); ?>
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

				<?php
			}

			?>
        </div>
    </form>
<?php

require_once( DEMOPRESS_PATH . 'forms/shared/bottom.php' );
