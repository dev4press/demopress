<?php

use function Dev4Press\v35\Functions\panel;

?>
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
				<?php _e( "Update completed.", "demopress" ); ?>
            </div>
        </div>

        <div class="d4p-install-confirm">
            <a class="button-primary" href="<?php echo panel()->a()->panel_url( 'about' ) ?>&update"><?php _e( "Click here to continue", "demopress" ); ?></a>
        </div>
    </div>
	<?php echo demopress()->recommend( 'update' ); ?>
</div>