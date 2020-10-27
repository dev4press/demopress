<div class="d4p-content">
    <div class="d4p-update-info">
		<?php

		demopress_settings()->set( 'install', false, 'info' );
		demopress_settings()->set( 'update', false, 'info', true );

		?>

        <div class="d4p-install-block">
            <h4>
				<?php _e( "All Done", "demopress" ); ?>
            </h4>
            <div>
				<?php _e( "Installation completed.", "demopress" ); ?>
            </div>
        </div>

        <div class="d4p-install-confirm">
            <a class="button-primary" href="<?php echo d4p_panel()->a()->panel_url( 'about' ) ?>&install"><?php _e( "Click here to continue", "demopress" ); ?></a>
        </div>
    </div>
	<?php echo demopress()->recommend( 'install' ); ?>
</div>