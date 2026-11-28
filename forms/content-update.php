<?php

use function Dev4Press\v56\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="d4p-content">
    <div class="d4p-setup-wrapper">
        <div class="d4p-update-info">
			<?php

			demopress_settings()->set( 'install', false, 'info' );
			demopress_settings()->set( 'update', false, 'info', true );

			?>

            <div class="d4p-install-block">
                <h4>
					<?php esc_html_e( "All Done", "demopress" ); ?>
                </h4>
                <div>
					<?php esc_html_e( "Update completed.", "demopress" ); ?>
                </div>
            </div>

            <div class="d4p-install-confirm">
                <a class="button-primary" href="<?php echo esc_url( panel()->a()->panel_url( 'about' ) . '&update' ); ?>"><?php esc_html_e( 'Click here to continue', 'demopress' ); ?></a>
            </div>
        </div>
    </div>
</div>
