<?php

use Dev4Press\v50\Core\Quick\KSES;
use function Dev4Press\v50\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="d4p-about-minor">
    <h3><?php _e( "Maintenance and Security Releases", "demopress" ); ?></h3>
    <p>
        <strong><?php _e( "Version", "demopress" ); ?> <span>2.1</span></strong> &minus;
        New builders. Few fixes.
    </p>
    <p>
		<?php

		/* translators: Changelog subpanel information. %s: Subpanel URL. */
		echo KSES::standard( sprintf( __( 'For more information, see <a href=\'%s\'>the changelog</a>.', 'coreactivity' ), esc_url( panel()->a()->panel_url( 'about', 'changelog' ) ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		?>
    </p>
</div>
